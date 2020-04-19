# de_DE.php

## Beschreibung (Deutsch)

Diese Sprach-_DropIn_ ersetzt diverse Zeichen durch entprechende Strings in den Permalinks 
und den Namen hochgeladener Dateien. Im weiteren wird der Sprachschlüssel der Feeds gesetzt.

## Description (English)

This DropIn add special german permalink sanitize and replaces characters with appropriate 
transliterations uploads will be only needed at admin center and xmlrpc calls.

### Lösungen

 * Ersatz von Umlauten und Sonderzeichen um saubere Permalinks zu erzeugen
   * Beispiel: _Das häßliche Entlein kostet 1 €_ wird im Permalink zu _das haessliches-entlein-kostet-1-eur_
   * Hinweis: Bei Verwendung des Gutenberg Editors klappt dies nicht, da via Ajax der Permalink aus dem Titel generiert wird.
   * Beispiel: _Häßliches Entlein.png_ wird zu _haessliches-entlein.png_
 * Setzt den Sprachwert des Feed auf `de` (dafür hat WordPress keine sichtbare Option)

### Hinweise
 
 * Wenn das Plugin [Germanix](https://github.com/thefuxia/Germanix-WordPress-Plugin) aktiv ist, 
   dann wirkt dieses Plugin _de_DE.php_ nicht.
 * Seit Version 1.0.0 wird Gutenberg unterstützt, beispielsweise beim UPload von Media-Dateien via Drag&Drop. Allerdings sind nicht ausreichend Hooks vorhanden, so dass der Titel des Bildes ebenso angepasst wird. Wird also der Titel ins auberer Form benötigt, so muss dies manuel angepasst werden. Der Dateiname wird aber sauber gefiltert. 
## Einsatz als Dropin

 * Upload der Datei `de_DE.php` in Sprachordner, üblicherweise `wp-content/languages`
 * Das Dropin ist automatisch aktiv, sobald der Sprachschlüssel (Konstante: `WPLANG`) in der 
   `wp-config.php`auf `de_DE` gesetzt ist oder (seit WordPress Version 4.0) die Sprache Deutsch in den Einstellungen gesetzt ist.

## Einsatz als Plugin

 * Upload des Ordners oder nur der Datei `de_DE.php` in den Plugin-Ordner der Installation
   , im Standard ist das `wp-content/plugins`
 * Das Plugin im Administrationsbereich --> Plugins aktivieren
