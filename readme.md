# de_DE.php

## Beschreibung (Deutsch)

Dieses Sprach-_Drop-in_ ersetzt diverse Zeichen durch entsprechende Zeichenfolgen in den Permalinks und den Dateinamen hochgeladener Dateien. Des Weiteren wird der Sprachschlüssel der Feeds gesetzt.

## Description (English)

This drop-in adds special German permalink sanitization and replaces characters with appropriate transliterations. The transliteration of uploads is only active in the admin center and for XML-RPC calls.

## Details (Only in German language)
### Lösungen

*   Ersetzt Umlaute und Sonderzeichen, um saubere Permalinks zu erzeugen.
    *   Beispiel: Aus _„Das hässliche Entlein kostet 1 €“_ wird im Permalink _„das-haessliche-entlein-kostet-1-eur“_.
    *   Hinweis: Bei Verwendung des Gutenberg-Editors funktioniert dies nicht, da der Permalink via Ajax aus dem Titel generiert wird.
    *   Beispiel: Aus _„Hässliches Entlein.png“_ wird _„haessliches-entlein.png“_.
*   Setzt den Sprachwert des Feeds auf `de` (WordPress bietet hierfür keine sichtbare Option).

### Hinweise
 
 * Wenn das Plugin [Germanix](https://github.com/thefuxia/Germanix-WordPress-Plugin) aktiv ist, wirkt dieses Plugin (`de_DE.php`) nicht.
 * Seit Version 1.0.0 wird Gutenberg unterstützt, beispielsweise beim Upload von Mediendateien via Drag and Drop. Allerdings sind nicht ausreichend Hooks vorhanden, sodass der Bildtitel ebenfalls durch Gutenberg angepasst wird (und dieses Plugin dies nicht immer beeinflussen kann). Benötigt man den Titel also in einer sauberen Form, muss dieser manuell angepasst werden. Der Dateiname wird hingegen zuverlässig gefiltert.

## Einsatz als Drop-in

*   Laden Sie die Datei `de_DE.php` in den Sprachordner Ihrer WordPress-Installation hoch (üblicherweise `wp-content/languages`).
*   Das Drop-in wird automatisch aktiv, sobald entweder der Sprachschlüssel (Konstante `WPLANG`) in der `wp-config.php` auf `de_DE` gesetzt ist oder (seit WordPress Version 4.0) in den WordPress-Einstellungen „Deutsch“ als Sprache ausgewählt wurde.

## Einsatz als Plugin

*   Laden Sie entweder den Ordner (wenn Sie das gesamte Repository heruntergeladen haben) oder nur die Datei `de_DE.php` in den Plugin-Ordner Ihrer WordPress-Installation hoch (standardmäßig `wp-content/plugins`).
*   Aktivieren Sie das Plugin im WordPress-Administrationsbereich unter „Plugins“.
