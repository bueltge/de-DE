=== de_DE ===
Contributors: Bueltge
Donate link: https://www.paypal.me/FrankBueltge
Tags: german, ascii, permalink, umlaut, upload
Requires at least: 3.5
Tested up to: 5.4
Requires PHP: 5.6
Stable tag: 0.7.15
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Replace Non-ASCII characters for installs, that set the german language as primary language.

== Description ==
The solution is usally interested in installations whose set the language to de_DE, German. This Plugin, also usable as Drop In, replaces non-ASCII strings, especially german umlauts, with a alternate in permalinks, uploaded files. Also add it the german language key to the feed.

*The follow hints are only available in German language.*

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
 * Support, Fehler, Ergänzen bitte via [GitHub](https://github.com/bueltge/de_DE.php/)
 
== Installation ==

 * PHP 5.2 (aktiv getestet unter php 7.1)
 * WordPress 3.5, (aktiv getestet in der letzen stabilen Version)
 * Optional: [Normalizer class](http://php.net/manual/de/class.normalizer.php)

= Einsatz als Dropin =

 * Upload der Datei `de_DE.php` in Sprachordner, üblicherweise `wp-content/languages`
 * Das Dropin ist automatisch aktiv, sobald der Sprachschlüssel (Konstante: `WPLANG`) in der `wp-config.php`auf `de_DE` gesetzt ist oder (seit WordPress Version 4.0) die Sprache _Deutsch_ in den Einstellungen gesetzt ist.

= Einsatz als Plugin =

 * Upload des Ordners oder nur der Datei `de_DE.php` in den Plugin-Ordner der Installation, im Standard ist das `wp-content/plugins`
 * Das Plugin im Administrationsbereich --> Plugins aktivieren
 
== Changelog ==
= 0.7.15 (2018-11-13) =
* Remove setting default spellchecker, because the spellchecker of the editor is since version 3.5 no longer available.

= 0.7.14 (2017-04-07) =
* First release on wordpress.org, before only public on github
