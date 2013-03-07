# de_DE.php

## Beschreibung (Deutsch)

Diese Sprach-_DropIn_ ersetzt diverse Zeichen durch entprechende Strings in den Permalinks 
und den Namen hochgeladener Dateien. Im weiteren wird der Sprachschlüssel der Feeds und 
die Standard-Sprache der Rechtschreibkorrektur des TinyMCE, des visuellen Editors, gesetzt.

####Lösungen

 * Ersatz von Umlauten und Sonderzeichen um saubere Permalinks zu erzeugen
   * Beispiel: _Häßliches Entlein_ wird im Permalink zu _haessliches-entlein_
 * Dateinamen ersetzen: Sonderzeichen, Leerzeichen, Umlaute
   * Beispiel: _Häßliches Entlein.png_ wird im Permalink zu _haessliches-entlein.png_
 * Setzt die Rechtschreibkorrektur des visuellen Editors auf die deutsche Sprache
 * Setzt den Sprachwert des Feed auf `de` (dafür hat WordPress keine sichtbare Option)

####Hinweise
 
 * Wenn das Plugin [Germanix](https://github.com/toscho/Germanix-WordPress-Plugin) aktiv ist, 
   dann wirkt dieses Plugin _de_DE.php_ nicht.
 
### Einsatz als Dropin

 * Upload der Datei `de_DE.php` in Sprachordner, üblicherweise `wp-content/languages`
 * Das Dropin ist automatisch aktiv, sobald der Sprachschlüssel (Konstante: `WPLANG`) in der 
   `wp-config.php`auf `de_DE` gesetzt ist.

### Einsatz als Plugin

 * Upload des Ordners oder nur der Datei `de_DE.php` in den Plugin-Ordner der Installation
   , im Standard ist das `wp-content/plugins`
 * Das Plugin im Administrationsbereich --> Plugins aktivieren

mehr Information bei Heiko [Permalinks mit Umlauten ohne o42-clean-umlauts](http://www.code-styling.de/deutsch/permalinks-mit-umlauten-ohne-o42-clean-umlauts)
oder auf [Das WordPress-Buch](http://wordpress-buch.bueltge.de/das-wordpress-buch/downloads/extra/)

### Description (English)

This DropIn add special german permalink sanitize and replaces characters with appropriate 
transliterations uploads will be only needed at admin center and xmlrpc calls, 
pre-select also the german spell checker at TinyMCE
