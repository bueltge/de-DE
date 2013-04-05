<?php
/**
* Plugin Name: WP-DE
* Plugin URI: https://github.com/bueltge/de_DE.php
* Text Domain: wp-de
* Domain Path: /languages
* Description: TBD
* Author: Frank Bültge, Heiko Rabe, TBD
* Author URI: http://bueltge.de/
* Version: 0.1
* Last change: -
* License: GPLv3
*
*
* License:
* ==============================================================================
* Copyright 2009-2013 Frank Bueltge (email : frank@bueltge.de)
*
* This program is free software; you can redistribute it and/or
* modify it under the terms of the GNU General Public License
* as published by the Free Software Foundation; either version 3
* of the License, or (at your option) any later version.
* 
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
* 
* You should have received a copy of the GNU General Public License
* along with this program; if not, write to the Free Software
* Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA  02110-1301, USA.
*/

/**
 * WordPress Dropin that add special german permalink sanitize and replaces characters 
 *  with appropriate transliterations uploads will be only needed at admin center and 
 *  xmlrpc calls, pre-select also the german spell checker at TinyMCE
 * 
 * @version  0.7.6
 * @date     04/03/2013
 * suggestion by Heiko Rabe (www.code-styling.de), Frank Bueltge (bueltge.de), Thomas Scholz (toscho.de)
 * special german permalink sanitize will be only needed at admin center, xmlrpc calls, ajax and cron
 * avoid additional filtering at frontend html generation
 */

/**
 * Check for the plugin Germanizer with the same topic and doing nothing
 * 
 * @see    https://github.com/toscho/Germanix-WordPress-Plugin/blob/master/germanix_url.php
 * @since  09/17/2012
 */
if ( class_exists( 'Germanizer' ) )
	return NULL;

if ( ! class_exists('WP_DE') ) {

	class WP_DE {
		
		var $umlaut_chars;
		
		public function __construct() {
			$this->init();
			
			if ( is_admin() // if we are at admin center 
				|| ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) // or processing offline blog software like LiveWriter
				|| ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) // or doing on autosave 
				|| ( defined( 'DOING_AJAX' ) && DOING_AJAX ) // or create posts with ajax
				|| ( defined( 'DOING_CRON' ) && DOING_CRON ) // or doing via cron
				) {			
			// if ( is_admin() ) {
				// wp-admin
				$this->admin_init();
			}
			
			if ( defined( 'XMLRPC_REQUEST' ) && XMLRPC_REQUEST ) { // processing offline blog software like LiveWriter
				$this->xmlrpc_init();
			}
		}
		
		protected function init() {
			
			// define character set globally
			$this->umlaut_chars['in']    = array( chr(196), chr(228), chr(214), chr(246), chr(220), chr(252), chr(223), chr(128) );
			$this->umlaut_chars['ecto']  = array( 'Ä', 'ä', 'Ö', 'ö', 'Ü', 'ü', 'ß', '€' );
			$this->umlaut_chars['html']  = array( '&Auml;', '&auml;', '&Ouml;', '&ouml;', '&Uuml;', '&uuml;', '&szlig;', '&euro;' );
			$this->umlaut_chars['feed']  = array( '&#196;', '&#228;', '&#214;', '&#246;', '&#220;', '&#252;', '&#223;', '&#128;' );
			$this->umlaut_chars['utf8']  = array( 
				utf8_encode( 'Ä' ), utf8_encode( 'ä' ), utf8_encode( 'Ö' ), utf8_encode( 'ö' ), 
				utf8_encode( 'Ü' ), utf8_encode( 'ü' ), utf8_encode( 'ß' ), utf8_encode( '€' )
			);
			$this->umlaut_chars['perma'] = array( 'Ae', 'ae', 'Oe', 'oe', 'Ue', 'ue', 'ss', 'EUR' );
			
			//register activation/deactivation hooks
//			if ( function_exists('register_activation_hook') )
//				register_activation_hook( __FILE__, array( 'WP_DE', 'activate_plugin'));
//			if ( function_exists('register_uninstall_hook') )
//				register_uninstall_hook( __FILE__, array( 'WP_DE', 'deactivate_plugin') );
		}
		
		protected function admin_init() {
			// i18n
			// $this->load_plugin_textdomain();

			add_action( 'admin_init', array( &$this, 'set_rss_language')); // not sufficient to init on activation only?
			add_filter( 'mce_spellchecker_languages', array( &$this, 'spell_checker_default'));

			// only on plugin.php
			add_filter( 'plugin_row_meta', array( &$this, 'set_plugin_meta'), 10, 2);
			
			/**
			* Enable cleaning of permalinks
			* 
			* @since   0.0.1
			*/
			remove_filter( 'sanitize_title', 'sanitize_title_with_dashes', 11 );
			add_filter( 'sanitize_title', array( &$this, 'umlaut_permalinks'), 10, 2 ); // for posts and pages
			
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
			add_filter( 'sanitize_file_name', array( &$this, 'umlaut_filename'), 10, 1 );
		}
		
		protected function xmlrpc_init() {
			// referenced to: feed.php filters (extend it if necessary)
			// ---------------------------------------------------------------------------
			// wp_title_rss | the_title_rss | the_content_rss | the_excerpt_rss | 
			// comment_text_rss | the_category_rss | the_permalink_rss

			// references to export.php filters extend it if necessary): 
			// ---------------------------------------------------------------------------
			// the_content_export | the_excerpt_export

			// Window Live Writer and other offline blogging Tools needs to be corrected to UTF-8
			foreach ( 
				array( 
					'the_title', 
					'the_excerpt', 
					'the_content', 
					'comment_text', 
					'the_category', 
					'the_tags'
				) as $action ) {
				add_action( $action, array( &$this, 'umlaut_xmlrpc_content'));
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
		function umlaut_permalinks( $title, $raw_title = NULL ) {
			// global $umlaut_chars;

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
			$title = str_replace( $this->umlaut_chars['in'], $this->umlaut_chars['perma'], $title );
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
		function replace_filename( $filename ) {

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
		function umlaut_xmlrpc_content( $content ) {
			// global $umlaut_chars;

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
		function umlaut_filename( $filename ) {
			global $umlaut_chars;

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
			$filename = str_replace( $this->umlaut_chars['in'], $this->umlaut_chars['perma'], $filename );
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
		function set_rss_language() {

			if ( 'de' !== get_option( 'rss_language' ) )
				update_option( 'rss_language', 'de' );
		}
		
		/**
		* pre-select the german spell checker at TinyMCE
		* 
		* @since   0.0.1
		* @param   $langs  String
		* @return  $res    String
		*/
		function spell_checker_default( $langs ) {

			$arr = explode( ',', str_replace( '+', '', $langs ) );
			$res = array();

			foreach( $arr as $lang ) {
				$res[] = ( preg_match( '/=de$/', $lang) ? '+' . $lang : $lang );
			}

			return implode( ',', $res );
		}

		function set_plugin_meta( $links, $file ) {
			if ( $file == plugin_basename( __FILE__ ) ) {
					return array_merge(
							$links,
							array( '<a href="https://github.com/bueltge/de_DE.php/" target="_blank">GitHub</a>' )
					);
			}
			return $links;
		}
			
//		function activate_plugin() {
//			$defaults = array(
//				"posts_pages" => 1,
//				"taxonomy" => 2
//			);
//			update_option( 'wp_de', $defaults);
//		}
//
//		function deactivate_plugin() {
//			delete_option( 'wp_de');
//		}
		
	} // END class WP_DE
	
	$GLOBALS['WP_DE'] = new WP_DE();

} // END if class_exists
