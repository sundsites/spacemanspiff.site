<?php

require('database_functions.php');
require('utility_functions.php');

$thisScript = 'index.php';
define('PAGE_SIZE',30);

?>
<html>
    <head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/candh.css">
    <title>Calvin &amp; Hobbes</title>
<!-- Matomo -->
<script>
    var _paq = window._paq = window._paq || [];
    /* tracker methods like "setCustomDimension" should be called before "trackPageView" */
    _paq.push(['trackPageView']);
    _paq.push(['enableLinkTracking']);
    (function() {
    var u="//stats.sund.org/";
    _paq.push(['setTrackerUrl', u+'matomo.php']);
    _paq.push(['setSiteId', '7']);
    var d=document, g=d.createElement('script'), s=d.getElementsByTagName('script')[0];
    g.async=true; g.src=u+'matomo.js'; s.parentNode.insertBefore(g,s);
    })();
</script>
<noscript>
    <p>
        <img src="//brickdata.xyz/matomo.php?idsite=7&amp;rec=1" style="border:0;" alt="" />
    </p>
</noscript>
<!-- End Matomo Code -->
</head>

<body>

<h1>
Calvin &amp; Hobbes
</h1>

<?php

$mysqli = databaseOpen();

if ($slide) {
    $sql = "SELECT * FROM ch WHERE ch_date>='".strSQL($mysqli, $slide)."' ORDER BY ch_date LIMIT 2;";
    $res = mysqli_query($mysqli, $sql);
    if (!$res) {
        echo "Calvin & Hobbes slide read Query Error = ".mysqli_error($mysqli)."<BR>";
        exit;
    }
}

$book = isset($_REQUEST['book']) ? trim($_REQUEST['book']) : null;

$slide = isset($_REQUEST['slide']) ? $_REQUEST['slide'] : null;

if (isset($my_array['slide'])) {
    if ($slide)     // if slideshow mode
    {
        $sql = "SELECT * FROM ch WHERE ch_date>='".strSQL($slide)."' ORDER BY ch_date LIMIT 2;";    // get this date, plus next one
        // echo htmlentities($sql).'<BR>'; // exit;
        $res = mysqli_query($mysqli, $sql);     // query table
    if (!$res)
    {
        echo "Calvin & Hobbes slide read Query Error = ".mysqli_error()."<BR>";
        exit;
    }
} else {
    // Handle the case where the "slide" key doesn't exist
    $slide_value = null;
}

if (($row = mysqli_fetch_object($res)))     // if record read okay
    {
        $date = sql2date($row->ch_date);        // get strip date
        $imageFile = date('Y/m/Ymd',$date).'.jpg';      // get strip image file name and path
        if (($rowNext = mysqli_fetch_object($res)))     // if next record read okay
            $nextDate = date('Y-m-d',sql2date($rowNext->ch_date));  // get date of next strip, in "slide=" format
        else
            $nextDate = '1985-11-18';                   // wrap around if no more strips
?>

<img src="<?php echo $imageFile; ?>" width="100%" style="Xborder: 2px solid #666; Xpadding: 4px; padding-left: -10px; margin-top: 4px;" />

<?php
    }
    mysqli_free_result($res);
    ?>
    <br />
    <i title="Show next comic (you can also click the image for this)">Next</i></a>
    &nbsp;&bull;&nbsp;
    <a href="./"><i>Home</i></a>
    </body>
    </html>
<?php
    exit;
}

if (isset($my_array['book'])) {
    // $book = (!empty(trim($_REQUEST['book']) ? $_REQUEST['book'] : null));
if ($book)
{
    ?>
    <h2>
    Available in the following books:
    </h2>
    <?php
    $sql = "SELECT * FROM chbooks WHERE INSTR('".strSQL($book)."', chb_id) ORDER BY chb_date";

    // echo htmlentities($sql).'<BR>'; exit;
    $res = mysqli_query($mysqli, $sql);     // query table
    if (!$res)
    {
        echo "Calvin & Hobbes book read Query Error = ".mysqli_error()."<BR>";
        exit;
    }

    $cnt = 0;
    while ($row = mysqli_fetch_object($res))        // for every book record
    {
        $title = $row->chb_title;               // get book title
        $date = sql2date($row->chb_date);       // get book publish date
        $link = $row->chb_link;             // get book title
        ?>
        <a target="_blank" href="<?php echo $link; ?>">
        <?php echo htmlentities($title); ?> (<?php echo date('F Y',$date); ?>)</a>
        <br><br>
        <?php
        $cnt++;
    }
    mysqli_free_result($res);
    ?>
    <br />
    <a href="./"><i>Close this window when done</i></a>
    </body></html>
    <?php
    exit;
}
} else {
    // Handle the case where the "book" key doesn't exist
    $book_value = null;
}

if (isset($_REQUEST['q'])) {
    $q = trim($_REQUEST['q']);      // get user query
} else {
    $q = null;
}

?>

<form action="<?php echo $thisScript; ?>" method=get>
<input type=hidden name="issubmit" value="1">
<input type=text name="q" value="<?php echo htmlentities($q); ?>">
<input type=submit name="submit" value="Search">
&nbsp;&nbsp;&nbsp;
<a class="function" href="indexBrowse.html">Chronological Menu</a>
&nbsp;&bull;&nbsp;
<a class="function" href="./?slide=1985-11-18">Chronological Slideshow</a>
&nbsp;&bull;&nbsp;
<a class="function" href="./?issubmit=1">Display text of all strips</a>
</form>
<?php


if (!$_REQUEST['issubmit'])     // if nothing yet submitted, pick a random comic
{
    $sql = "SELECT * FROM ch ORDER BY RAND() LIMIT 1;";
    // echo htmlentities($sql).'<BR>'; // exit;
    $res = mysqli_query($mysqli, $sql);     // query table
    if (!$res)
    {
        echo "Calvin & Hobbes random read Query Error = ".mysqli_error()."<BR>";
        exit;
    }

if (($row = mysqli_fetch_object($res)))     // if record read okay
{
    $date = sql2date($row->ch_date);        // get strip date
    $imageFile = date('Y/m/Ymd',$date).'.jpg';     // get strip image file name and path

    if (file_exists($imageFile)) {
        ?>
        <br />
        <div class="function">A random selection: <a target="_blank" href="<?php echo $imageFile; ?>">
        <?php echo date('l, F jS, Y',$date); ?></a>
        &nbsp;&bull;&nbsp;
        <a href="#" onclick="window.open('./book.php?book=<?php echo urlencode($row->ch_books); ?>', 'newwindow', 'width=600,height=600'); return false;"><I>book</I></a>
        <a target="_blank" href="<?php echo $imageFile; ?>">
        <img src="<?php echo $imageFile; ?>" width="100%" style="Xborder: 2px solid #666; Xpadding: 4px; padding-left: -10px; margin-top: 4px;" />
        </a>
        <?php
    } else {
        echo "Image not found: " . $imageFile;
    }
}
    mysqli_free_result($res);
} else {
    // echo 'about to open DB<BR>'; exit;
    if (!$q)        // if nothing entered for search, return all records
    {
        $isLimit = True;
        $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM ch ORDER BY ch_date";
    } else {            // otherwise create search string
        $sql = "SELECT SQL_CALC_FOUND_ROWS *, MATCH (ch_text) AGAINST ('".strSQL($q)."' IN NATURAL LANGUAGE MODE) AS score FROM ch WHERE MATCH (ch_text) AGAINST ('".strSQL($q)."' IN NATURAL LANGUAGE MODE)";
        // $sql = "SELECT *, MATCH (ch_text) AGAINST ('".strSQL($q)."' IN BOOLEAN MODE) AS score FROM ch WHERE MATCH (ch_text) AGAINST ('".strSQL($q)."' IN BOOLEAN MODE);";

        $isLimit = True;
    }
    $page = intval(@$_REQUEST['page']);
    if (!$page) $page = 1;

    $sql .= ($isLimit?' LIMIT '.(PAGE_SIZE+1).' OFFSET '.(($page-1)*PAGE_SIZE):''); // append a limiting clause, one higher than hard limit
    // $sql = "SELECT * FROM ch $sql $sqlOrder";
    // echo htmlentities($sql).'<BR>'; // exit;
    $res = mysqli_query($mysqli, $sql);     // query table
    if (!$res)
    {
        echo "Calvin & Hobbes read Query Error = ".mysqli_error()."<BR>";
        exit;
    }
    $sql2 = 'SELECT FOUND_ROWS();';
    // echo htmlentities($sql).'<BR>'; // exit;
    $res2 = mysqli_query($mysqli, $sql2);       // query table
    if (!$res2)
    {
        echo "Calvin & Hobbes FOUND_ROWS() read Query Error = ".mysqli_error()."<BR>";
        exit;
    }
    if ($row2 = mysqli_fetch_row($res2))
    {
        // echo 'Found a row, $rowmax = '.$row2[0].'<BR>'; exit;
        $rowmax = $row2[0];
    } else
        $rowmax = 0;
    // echo '$rowmax = '.$rowmax.'<BR>'; exit;
    $pagemax = intval($rowmax/PAGE_SIZE);
    ?>
    <div style="padding: 2px; background-color: #CCF;">
    <a href="<?php echo $thisScript; ?>?q=<?php echo htmlentities($q); ?>&issubmit=1&page=1">&lt;&lt;</a>&nbsp;<a href="<?php echo $thisScript; ?>?q=<?php echo htmlentities($q); ?>&issubmit=1&page=<?php echo max(1, $page-1); ?>">&lt;</a>&nbsp;<?php
    ?>Page <?php echo $page; ?><?php
    ?>&nbsp;<a href="<?php echo $thisScript; ?>?q=<?php echo htmlentities($q); ?>&issubmit=1&page=<?php echo min(intval($rowmax/PAGE_SIZE), $page+1); ?>">&gt;</a>&nbsp;<a href="<?php echo $thisScript; ?>?q=<?php echo htmlentities($q); ?>&issubmit=1&page=<?php echo intval($rowmax/PAGE_SIZE)+1; ?>">&gt;&gt;</a>

    &nbsp; Displaying <?php echo ($page-1)*PAGE_SIZE+1; ?>-<?php echo ($page-1)*PAGE_SIZE+min(PAGE_SIZE, mysqli_num_rows($res)); ?> of <?php echo $rowmax; ?> records.
    </div><br /><?php
    $cnt = 0;
    while (($row = mysqli_fetch_object($res)) && (!$isLimit || $cnt < PAGE_SIZE))       // for every record
    {
        $date = sql2date($row->ch_date);        // get strip date
        $imageFile = date('Y/m/Ymd',$date).'.jpg';      // get strip image file name and path
        ?>
        <a target="_blank" href="<?php echo $imageFile; ?>">
        <?php echo date('l, F jS, Y',$date); ?></a>
        &nbsp;&bull;&nbsp;
        <a target="_blank" href="./?book=<?php echo $row->ch_books; ?>"><I>book</I></a>
        <div class="quote"><?php echo htmlentities($row->ch_text); ?></div>
        <?php
        $cnt++;
    }
    if (!$cnt)
    {
        ?>
        <b style="color: #900;">No records found</b>
        <?php
    }
    mysqli_free_result($res);
}
?>
</body></html>
<?php
