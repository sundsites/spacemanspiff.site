<?
require('misc.php');
$thisScript = 'index.php';
define('PAGE_SIZE',30);
?>
<html><head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/candh.php">
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
<noscript><p><img src="//stats.sund.org/matomo.php?idsite=7&amp;rec=1" style="border:0;" alt="" /></p></noscript>
<!-- End Matomo Code -->
</head>

<body>

<h1>
Calvin &amp; Hobbes
</h1>

<?
databaseOpen();
$mysqli = new mysqli($GLOBALS['DBCONFIG']["dbhost"], $GLOBALS['DBCONFIG']["dbuser"], $GLOBALS['DBCONFIG']["dbpassword"], $GLOBALS['DBCONFIG']["dbname"]);


$slide = (!empty(trim($_REQUEST['slide']) ? $_REQUEST['slide'] : null));
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

if (($row = mysqli_fetch_object($res)))     // if record read okay
    {
        $date = sql2date($row->ch_date);        // get strip date
        $imageFile = date('Y/m/Ymd',$date).'.jpg';      // get strip image file name and path
        if (($rowNext = mysqli_fetch_object($res)))     // if next record read okay
            $nextDate = date('Y-m-d',sql2date($rowNext->ch_date));  // get date of next strip, in "slide=" format
        else
            $nextDate = '1985-11-18';                   // wrap around if no more strips
?>

<div class="function">
    <? echo date('l, F jS, Y',$date); ?></a>
    &nbsp;&bull;&nbsp;
    <a target="_blank" href="./?book=<? echo $row->ch_books; ?>"><I>book</I></a>
</div>
<a href="<? echo $thisScript; ?>?slide=<? echo $nextDate; ?>">
<img src="<? echo $imageFile; ?>" width="100%" style="Xborder: 2px solid #666; Xpadding: 4px; padding-left: -10px; margin-top: 4px;" />

<?
    }
    mysqli_free_result($res);
    ?>
    <br />
    <i title="Show next comic (you can also click the image for this)">Next</i></a>
    &nbsp;&bull;&nbsp;
    <a href="./"><i>Home</i></a>
    </body>
    </html>
<?
    exit;
}

$book = (!empty(trim($_REQUEST['book']) ? $_REQUEST['book'] : null));
if ($book)
{
    ?>
    <h2>
    Available in the following books:
    </h2>
    <?
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
        <a target="_blank" href="<? echo $link; ?>">
        <? echo htmlentities($title); ?> (<? echo date('F Y',$date); ?>)</a>
        <br><br>
        <?
        $cnt++;
    }
    mysqli_free_result($res);
    ?>
    <br />
    <Xa href="./"><i>Close this window when done</i></Xa>
    </body></html>
    <?
    exit;
}

$q = trim($_REQUEST['q']);      // get user query

?>

<form action="<? echo $thisScript; ?>" method=get>
<input type=hidden name="issubmit" value="1">
<input type=text name="q" value="<? echo htmlentities($q); ?>">
<input type=submit name="submit" value="Search">
&nbsp;&nbsp;&nbsp;
<a class="function" href="indexBrowse.html">Chronological Menu</a>
&nbsp;&bull;&nbsp;
<a class="function" href="./?slide=1985-11-18">Chronological Slideshow</a>
&nbsp;&bull;&nbsp;
<a class="function" href="./?issubmit=1">Display text of all strips</a>
</form>
<?
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
        $imageFile = date('Y/m/Ymd',$date).'.jpg';      // get strip image file name and path
        ?>
        <br />
        <div class="function">A random selection: <a target="_blank" href="<? echo $imageFile; ?>">
        <? echo date('l, F jS, Y',$date); ?></a>
        &nbsp;&bull;&nbsp;
        <a target="_blank" href="./?book=<? echo $row->ch_books; ?>"><I>book</I></a></div>
        <a target="_blank" href="<? echo $imageFile; ?>">
        <img src="<? echo $imageFile; ?>" width="100%" style="Xborder: 2px solid #666; Xpadding: 4px; padding-left: -10px; margin-top: 4px;" />
        </a>
        <?
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
    <a href="<? echo $thisScript; ?>?q=<? echo htmlentities($q); ?>&issubmit=1&page=1">&lt;&lt;</a>&nbsp;<a href="<? echo $thisScript; ?>?q=<? echo htmlentities($q); ?>&issubmit=1&page=<? echo max(1, $page-1); ?>">&lt;</a>&nbsp;<?
    ?>Page <? echo $page; ?><?
    ?>&nbsp;<a href="<? echo $thisScript; ?>?q=<? echo htmlentities($q); ?>&issubmit=1&page=<? echo min(intval($rowmax/PAGE_SIZE), $page+1); ?>">&gt;</a>&nbsp;<a href="<? echo $thisScript; ?>?q=<? echo htmlentities($q); ?>&issubmit=1&page=<? echo intval($rowmax/PAGE_SIZE)+1; ?>">&gt;&gt;</a>

    &nbsp; Displaying <? echo ($page-1)*PAGE_SIZE+1; ?>-<? echo ($page-1)*PAGE_SIZE+min(PAGE_SIZE, mysqli_num_rows($res)); ?> of <? echo $rowmax; ?> records.
    </div><br /><?
    $cnt = 0;
    while (($row = mysqli_fetch_object($res)) && (!$isLimit || $cnt < PAGE_SIZE))       // for every record
    {
        $date = sql2date($row->ch_date);        // get strip date
        $imageFile = date('Y/m/Ymd',$date).'.jpg';      // get strip image file name and path
        ?>
        <a target="_blank" href="<? echo $imageFile; ?>">
        <? echo date('l, F jS, Y',$date); ?></a>
        &nbsp;&bull;&nbsp;
        <a target="_blank" href="./?book=<? echo $row->ch_books; ?>"><I>book</I></a>
        <div class="quote"><? echo htmlentities($row->ch_text); ?></div>
        <?
        $cnt++;
    }
    if (!$cnt)
    {
        ?>
        <b style="color: #900;">No records found</b>
        <?
    }
    mysqli_free_result($res);
}
?>
</body></html>
<?
