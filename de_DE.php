<?php
/**
 * Plugin Name: de_DE
 * Plugin URI:  https://github.com/bueltge/de_DE.php
 * Text Domain: de_DE
 * Domain Path: /languages
 * Description: Add special german permalink sanitize and replaces characters with appropriate transliterations uploads, pre-select also the german spell checker at TinyMCE
 * Author:      Frank Bültge, Heiko Rabe, Christian Foellmann
 * Version:     0.8.0-Beta
 * License:     GPLv3
 * 
 * 
 * @version  04/05/2013
 * WordPress Dropin that add special german permalink sanitize and replaces characters 
 *  with appropriate transliterations uploads will be only needed at admin center and 
 *  xmlrpc calls, pre-select also the german spell checker at TinyMCE
 * 
 * suggestion by Heiko Rabe (www.code-styling.de), Frank Bueltge (bueltge.de), Thomas Scholz (toscho.de)
 * special german permalink sanitize will be only needed at admin center, xmlrpc calls, ajax and cron
 * avoid additional filtering at frontend html generation
 */

//avoid direct calls to this file
if ( ! function_exists( 'add_filter' ) ) {
	header('Status: 403 Forbidden');
	header('HTTP/1.1 403 Forbidden');
	exit();
}

/**
 * Check for the plugin Germanizer with the same topic and doing nothing
 * 
 * @see    https://github.com/toscho/Germanix-WordPress-Plugin/blob/master/germanix_url.php
 * @since  09/17/2012
 */
if ( class_exists( 'Germanizer' ) )
	return NULL;

if ( ! class_exists('WP_DE') ) {
	
	add_action(
		'plugins_loaded',
		array ( 'WP_DE', 'get_instance' )
	);
	
	class WP_DE {
		
		/**
		 * Plugin instance.
		 *
		 * @see get_instance()
		 * @type object
		 */
		protected static $instance = NULL;
		
		/**
		 * Array for all chars.
		 *
		 * @type Array
		 */
		public $umlaut_chars = array();
		
		public function __construct() {} // leave empty for unit tests
		
		/**
		 * Access this plugin’s working instance
		 *
		 * @wp-hook plugins_loaded
		 * @since   04/05/2013
		 * @return  object of this class
		 */
		public static function get_instance() {
			
			if ( NULL === self::$instance )
				self::$instance = new self;
			
			return self::$instance;
		}
		
		/**
		 * Used for regular plugin work.
		 *
		 * @wp-hook plugins_loaded
		 * @since   04/05/2013
		 * @return  void
		 */
		public function plugin_setup() {
			
			// processing offline blog software like LiveWriter
			if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) {
				$this->init();
				$this->xmlrpc_init();
			}
			
			// if we are at admin center 
			if ( is_admin()
				|| ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) // or processing offline blog software like LiveWriter
				|| ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) // or doing on autosave 
				|| ( defined( 'DOING_AJAX' ) && DOING_AJAX ) // or create posts with ajax
				|| ( defined( 'DOING_CRON' ) && DOING_CRON ) // or doing via cron
				) {
				$this->init();
				$this->admin_init();
			}
		}
		
		/**
		 * Set default char on public var
		 * 
		 * @wp-hook plugins_loaded
		 * @since   04/05/2013
		 * @return  void
		 */
		protected function init() {
			
			// define character set globally
			$this->umlaut_chars['in']   = array( chr(196), chr(228), chr(214), chr(246), chr(220), chr(252), chr(223), chr(128) );
			$this->umlaut_chars['ecto'] = array( 'Ä', 'ä', 'Ö', 'ö', 'Ü', 'ü', 'ß', '€' );
			$this->umlaut_chars['html'] = array( '&Auml;', '&auml;', '&Ouml;', '&ouml;', '&Uuml;', '&uuml;', '&szlig;', '&euro;' );
			$this->umlaut_chars['feed'] = array( '&#196;', '&#228;', '&#214;', '&#246;', '&#220;', '&#252;', '&#223;', '&#128;' );
			$this->umlaut_chars['utf8'] = array( 
				utf8_encode( 'Ä' ), utf8_encode( 'ä' ), utf8_encode( 'Ö' ), utf8_encode( 'ö' ), 
				utf8_encode( 'Ü' ), utf8_encode( 'ü' ), utf8_encode( 'ß' ), utf8_encode( '€' )
			);
			$this->umlaut_chars['perma'] = array( 'Ae', 'ae', 'Oe', 'oe', 'Ue', 'ue', 'ss', 'EUR' );
			
			// maybe changes via hook for custom requirements
			$this->umlaut_chars = apply_filters( 'de_DE_umlaut_chars', $this->umlaut_chars );
		}
		
		protected function admin_init() {
			
			// not sufficient to init on activation only?
			add_action( 'admin_init', array( $this, 'set_rss_language' ) );
			
			// only on plugin.php
			add_filter( 'plugin_row_meta', array( $this, 'set_plugin_meta' ), 10, 2 );
			
			/**
			 * Enable cleaning of permalinks, for posts and pages
			 * 
			 * @since   04/05/2013
			 */
			remove_filter( 'sanitize_title', 'sanitize_title_with_dashes', 11 );
			add_filter( 'sanitize_title', array( $this, 'umlaut_permalinks' ), 10, 2 );
			
			// See: https://github.com/kaffee-mit-milch/wp-permalauts/blob/master/wp-permalauts.php#L173
			// remove_filter( 'sanitize_category', 'sanitize_title_with_dashes');
			// add_filter( 'sanitize_category', array( &$this, 'umlaut_permalinks'), 10, 2 ); // for categories
			
			// See: https://github.com/kaffee-mit-milch/wp-permalauts/blob/master/wp-permalauts.php#L178
			// remove_filter( 'sanitize_term', 'sanitize_title_with_dashes');
			// add_filter( 'sanitize_term', array( &$this, 'umlaut_permalinks'), 10, 2 );

			/**
			 * Enable cleaning of filename
			 * 
			 * @since   0.0.1
			 */
			add_filter( 'sanitize_file_name', array( $this, 'umlaut_filename' ), 10, 1 );
		}
		
		/**
		 * Window Live Writer and other offline blogging Tools needs to be corrected to UTF-8
		 * Add filter function to different hooks
		 * 
		 * referenced to: feed.php filters (extend it if necessary)
		 * ---------------------------------------------------------------------------
		 * wp_title_rss | the_title_rss | the_content_rss | the_excerpt_rss | 
		 * comment_text_rss | the_category_rss | the_permalink_rss
     * 
		 * references to export.php filters extend it if necessary): 
		 * ---------------------------------------------------------------------------
		 * the_content_export | the_excerpt_export
		 * 
		 * @since   04/05/2013
		 * @return  void
		 */
		protected function xmlrpc_init() {
			
			foreach ( 
				array( 
					'the_title', 
					'the_excerpt', 
					'the_content', 
					'comment_text', 
					'the_category', 
					'the_tags'
				) as $action ) {
					
				add_action( $action, array( $this, 'umlaut_xmlrpc_content' ) );
			}
		}
		
		/**
		* Sanitizes the titles to get qualified german permalinks with correct transliteration
		* 
		* @since   0.0.1
		* @param   $title      String
		* @param   $raw_title  String
		* @return  $title
		*/
		public function umlaut_permalinks( $title, $raw_title = NULL ) {
			
			if ( ! is_null( $raw_title ) )
				$title = $raw_title;
			
			if ( seems_utf8( $title ) ) {
				$invalid_latin_chars = array( 
					chr(197).chr(146) => 'OE', chr(197).chr(147) => 'oe', chr(197).chr(160) => 'S', 
					chr(197).chr(189) => 'Z', chr(197).chr(161) => 's', chr(197).chr(190) => 'z', 
					// Euro Sign €
					chr(226).chr(130).chr(172) => 'EUR',
					// GBP (Pound) Sign £
					chr(194).chr(163) => 'GBP'
				);
				// use for custom strings
				$invalid_latin_chars = apply_filters( 'de_de_latin_char_list', $invalid_latin_chars );
			
				$title = utf8_decode( strtr( $title, $invalid_latin_chars) );
			}
			
			$title = str_replace( $this->umlaut_chars['ecto'], $this->umlaut_chars['perma'], $title );
			$title = str_replace( $this->umlaut_chars['in'],   $this->umlaut_chars['perma'], $title );
			$title = str_replace( $this->umlaut_chars['html'], $this->umlaut_chars['perma'], $title );
			$title = remove_accents( $title );
			$title = sanitize_title_with_dashes( $title );
			$title = str_replace( '.', '-', $title );
			
			return $title;
		}

		/**
		 * Replace filename
		 * 
		 * @since   0.6.0
		 * @param   $filename  String
		 * @return  $filename  String
		 */
		public function replace_filename( $filename ) {
			
			// Win Livewriter sends escaped strings
			$filename = html_entity_decode( $filename, ENT_QUOTES, 'utf-8' );
			// Strip HTML and PHP tags
			$filename = strip_tags( $filename );
			// Preserve escaped octets.
			$filename = preg_replace( '|%([a-fA-F0-9][a-fA-F0-9])|', '---$1---', $filename );
			// Remove percent signs that are not part of an octet.
			$filename = str_replace( '%', '', $filename );
			// Restore octets.
			$filename = preg_replace( '|---([a-fA-F0-9][a-fA-F0-9])---|', '%$1', $filename );
			
			$filename = remove_accents( $filename );
			
			if ( seems_utf8( $filename ) ) {
			
				if ( function_exists( 'mb_strtolower' ) )
					$filename = mb_strtolower( $filename, 'UTF-8' );
			
				$filename = utf8_uri_encode( $filename, 200 );
			}
			
			$filename = strtolower( $filename );
			$filename = preg_replace( '/&.,+?;/', '', $filename ); // kill entities
			$filename = preg_replace( '/\s+/', '-', $filename );
			$filename = preg_replace( '|-+|', '-', $filename );
			$filename = trim( $filename, '-' );
			
			return $filename;
		}

		/**
		 * Replace umlaut chars
		 * 
		 * @since   0.1.0
		 * @param   $content  String
		 * @return  $content  String
		 */
		public function umlaut_xmlrpc_content( $content ) {
			
			$content = str_replace( $this->umlaut_chars['html'], $this->umlaut_chars['utf8'], $content );
			$content = str_replace( $this->umlaut_chars['feed'], $this->umlaut_chars['utf8'], $content );
			
			return $content;
		}

		/**
		 * Filter umlaut chars of files
		 * 
		 * @since   0.6.0
		 * @param   $filename  String
		 * @return  $filename  String
		 */
		public function umlaut_filename( $filename ) {
			
			if ( seems_utf8( $filename ) ) {
				$invalid_latin_chars = array( 
					chr(197).chr(146)          => 'OE',
					chr(197).chr(147)          => 'oe',
					chr(197).chr(160)          => 'S', 
					chr(197).chr(189)          => 'Z',
					chr(197).chr(161)          => 's',
					chr(197).chr(190)          => 'z', 
					chr(226).chr(130).chr(172) => 'E' 
				);
				$filename = utf8_decode( strtr( $filename, $invalid_latin_chars) );
			}
			
			$filename = str_replace( $this->umlaut_chars['ecto'], $this->umlaut_chars['perma'], $filename );
			$filename = str_replace( $this->umlaut_chars['in'],   $this->umlaut_chars['perma'], $filename );
			$filename = str_replace( $this->umlaut_chars['html'], $this->umlaut_chars['perma'], $filename );
			$filename = $this->replace_filename( $filename );
			
			return $filename;
		}
		
		/**
		/* change rss language to de in db-table options
		 * 
		 * @since   0.7.0
		 * @return  void
		 */
		public function set_rss_language() {
			
			if ( 'de' !== get_option( 'rss_language' ) )
				update_option( 'rss_language', 'de' );
		}
		
		/**
		 * Add data to plugin meta in plugins list
		 * 
		 * @since   04/05/2013
		 * @param   $links  Array
		 * @param   $file   String
		 * @return  $links  Array
		 */
		function set_plugin_meta( $links, $file ) {
			
			if ( $file == plugin_basename( __FILE__ ) ) {
					return array_merge(
							$links,
							array( '<a href="https://github.com/bueltge/de_DE.php/" target="_blank">GitHub</a>' )
					);
			}
			
			return $links;
		}
		
	} // END class WP_DE

} // END if class_exists
