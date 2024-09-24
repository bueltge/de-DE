=== de_DE ===
Contributors: Bueltge
Donate link: https://www.paypal.me/FrankBueltge
Tags: german, ascii, permalink, umlaut, upload
Requires at least: 3.5
Tested up to: 6.6
Requires PHP: 5.6
Stable tag: 1.0.2
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Replace non-ASCII characters for installs that set the German language as a primary language.

== Description ==
The solution is usually interested in installations that set the language to de_DE, German. This Plugin, also usable as a drop-in, replaces non-ASCII strings, especially German umlauts, with an alternate in permalinks and uploaded files. Also, add the German language key to the feed.

*The following hints are only available in the German language.*

= Beschreibung =

Diese Sprach-_DropIn_ ersetzt diverse Zeichen durch entprechende Strings in den Permalinks und den Namen hochgeladener Dateien. Im weiteren wird der Sprachschlüssel der Feeds gesetzt.

= Lösungen =

 * Ersatz von Umlauten und Sonderzeichen um saubere Permalinks zu erzeugen
   * Beispiel: _Das häßliche Entlein kostet 1 €_ wird im Permalink zu _das haessliches-entlein-kostet-1-eur_
 * Dateinamen ersetzen: Sonderzeichen, Leerzeichen, Umlaute
   * Beispiel: _Häßliches Entlein.png_ wird zu _haessliches-entlein.png_
 * Setzt den Sprachwert des Feed auf `de` (dafür hat WordPress keine sichtbare Option)

= Hinweise =

 * Wenn das Plugin [Germanix](https://github.com/toscho/Germanix-WordPress-Plugin) aktiv ist, dann wirkt dieses Plugin/ Dropin `de_DE.php` nicht.
 * Support, Fehler, Ergänzen bitte via [GitHub](https://github.com/bueltge/de-DE/)

= Support und Unterstützung =
Das Plugin liegt auf [GitHub](https://wordpress.org/plugins/de_de/) und jede Hilfe ist gern gesehen.

== Installation ==

 * PHP 5.6 (aktiv getestet unter php 7.2)
 * WordPress 3.5, (aktiv getestet in der letzen stabilen Version)
 * Optional: [Normalizer class](http://php.net/manual/de/class.normalizer.php)

= Einsatz als Dropin =

 * Upload der Datei `de_DE.php` in Sprachordner, üblicherweise `wp-content/languages`
 * Das Dropin ist automatisch aktiv, sobald der Sprachschlüssel (Konstante: `WPLANG`) in der `wp-config.php`auf `de_DE` gesetzt ist oder (seit WordPress Version 4.0) die Sprache _Deutsch_ in den Einstellungen gesetzt ist.

= Einsatz als Plugin =

 * Upload des Ordners oder nur der Datei `de_DE.php` in den Plugin-Ordner der Installation, im Standard ist das `wp-content/plugins`
 * Das Plugin im Administrationsbereich --> Plugins aktivieren
 
== Changelog ==
= 1.0.2 (2024-09-24) =
* Updated encoding / decoding via mb_convert_encoding, [#20](https://github.com/bueltge/de-DE/pull/20)

= 1.0.1 (2024-06-11) =
* Fix deprecated topic for PHP >=8.2

= 1.0.0 (2020-04-19) =
* Supports Gutenberg Editor, REST API
* Refactoring Code to use it also via REST API - Leave the one-file solution to use it easily as Dropin.

= 0.7.15 (2018-11-13) =
* Remove setting the default spellchecker because the editor spellchecker has been unavailable since version 3.5.

= 0.7.14 (2017-04-07) =
* First release on wordpress.org, before only public on GitHub
