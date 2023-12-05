<?php

// Define version number
$version = trim(file_get_contents('version.txt'));

?>

<!DOCTYPE html>
<html>
<head>
    <title>About</title>
    <link rel="stylesheet" href="css/candh.css">
</head>
<body>

<div class="header">
    <h1>About spacemanspiff.site</h1>
</div>

<div class="nav">
    <a href="index.php">Home</a>
    <a href="about.php">About</a>
</div>

<h1>Calvin and Hobbes</h1>
<p>
This is the complete collection of the cleanest,
hi-res Calvin and Hobbes comics available in 2015,
organized by original publishing dates,
and with an optional database.
</p>

<p>
  This archive has its origins from a PirateBay archive
  that is no longer around.
</p>

<h2>Artwork</h2>

<p>
  The artwork is from the <a href="http://www.gocomics.com/calvinandhobbes/">GoComics</a> website.
  Those CBR scans of the collected works were broken apart into 1600/1700 pixel wide JPEGs of individual strips,
  and named according to their original publishing dates.
</p>

<h2>DATABASE and PHP</h2>

<p>
The artwork can work with a PHP script that allows a full text search of the strips' dialog and occasional narrative descriptions,
a chronological slideshow, and a date-based table of contents.
</p>

<p>
The textual database comes from a transcription I can no longer locate,
but appears to be the source of the search engine at http://michaelyingling.com/random/calvin_and_hobbes/
(Note that this site doesn't have any artwork, but merely links to the low-res artwork at gocomics.com)
and also derives browsable HTML obtained from:

http://marcel-oehler.marcellosendos.ch/comics/ch/
</p>

<div class="version">
    <?php include 'footer.php';?>
</div>

</body>
</html>