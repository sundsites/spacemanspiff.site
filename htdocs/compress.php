<?php
// compress all comics in the source 1985, 1986, ...1995 folder structure, to the same at the destination folder structure
require('misc.php');

echo "Start at ".time()."<BR>";
for ($year = 1985; $year <= 1995; $year++)
{
	for ($month = 1; $month <= 12; $month++)
	{
		$d = @dir('PathToSource1985-1995Folder\\'.$year.'\\'.sprintf('%02d',$month));
		// echo "dir object = <PRE>";print_r($d);echo "</PRE>"; exit;

		if (!is_null($d) && $d !== FALSE)		// if valid year/month directory path
		{
			while (false !== ($entry = $d->read()))		// loop through all directory entries
			{
				set_time_limit(30);				// extend script timeout
				if (preg_match("/([0-9]{2})\.jpg$/i",$entry, $res))		// if found a JPG file, it's a comic 
				{
					// echo $d->path.'\\'.$entry."<br />\n"; exit;
					$file = $d->path.'\\'.$entry;
					$day = $res[1];
					
					$file = str_replace('\\','/',$file);
					echo $file.' ('.time().')<BR>'; ob_flush(); flush(); // exit;
					$im = new Imagick($file);	// open image
					$im->setImageCompression(Imagick::COMPRESSION_JPEG);
					$im->setImageCompressionQuality(90);
					$im->stripImage();
					$res = $im->writeImage('PathToDestination1985-1995Folder\\'.$year.'\\'.sprintf('%02d',$month).'\\'.$entry);
					// echo "results = $res<BR>";
					// $im->setImagePage(0, 0, 0, 0);	// reset the origs for the new image size
					// header('Content-type: image/jpeg'); echo $im->getimageblob(); exit;
					$im->destroy();
				}
			}
			$d->close();
		}
	}
}
?>