# spacemanspiff.site
Calvin and Hobbs Archive

_This is based on a PirateBay archive I found but seems to be gone now._

This is the complete collection of the cleanest, hi-res Calvin and Hobbes comics available in 2015, organized by original publishing dates, and with an optional database.

## ARTWORK

The comic artwork was painstakingly derived from the excellent scans at:

https://thepiratebay.vg/torrent/8834365/Calvin_and_Hobbes_Collection

Those CBR scans of the collected works were broken apart into 1600/1700 pixel wide JPEGs of individual strips, and named according to their original publishing dates.

The artwork is lightly compressed, and contained solely in the 1985-1995 folders. If you don't want a database, those are the only files you need.

Since the artwork is barely compressed, you may like to compress them more for faster web delivery. A PHP script called "compress.php" is included. It requires the PHP Imagick extension, and you'll need to edit the source and destination directories specified within the code, as well as possibly change the JPG compression quality from its current value of 90. You could also compress the entire structure with a Photoshop batch process, the command-line ImageMagick, or similar utilities.

## DATABASE and PHP

The artwork can work with a PHP script that allows a full text search of the strips' dialog and occasional narrative descriptions, a chronological slideshow, and a date-based table of contents.

The textual database comes from a transcription I can no longer locate, but appears to be the source of the search engine at http://michaelyingling.com/random/calvin_and_hobbes/ (Note that this site doesn't have any artwork, but merely links to the low-res artwork at gocomics.com)

and also derives browsable HTML obtained from:

http://marcel-oehler.marcellosendos.ch/comics/ch/

Note that the above 105MB archive also contains artwork, but in very low-res GIF format. This is far below the quality of this 2GB release.

Two MySQL table dumps are included. The main table is in "ch.sql", and a related table of published collection books is in "chbooks.sql"

You'll have to adjust the database connection info within the databaseOpen() function in the "misc.php" include file.
