<?php
require('database_functions.php');
require('utility_functions.php');
// Define version number
$version = trim(file_get_contents('version.txt'));
$thisScript = 'index.php';
define('PAGE_SIZE', 30);

// Initialize query variables early
if (isset($_REQUEST['q'])) {
    $q = trim($_REQUEST['q']);      // get user query
} else {
    $q = null;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="content-type" content="text/html; charset=UTF-8">
    <link rel="icon" href="favicon.ico" type="image/x-icon">
    <link rel="stylesheet" href="css/candh.css?v=<?php echo $version; ?>">
    <title>Calvin &amp; Hobbes</title>
    <!-- Matomo Tracking Code -->
    <script>
        var _paq = window._paq = window._paq || [];
        _paq.push(['trackPageView']);
        _paq.push(['enableLinkTracking']);
        (function() {
            var u = "//brickdata.xyz/";
            _paq.push(['setTrackerUrl', u + 'matomo.php']);
            _paq.push(['setSiteId', '7']);
            var d = document, g = d.createElement('script'), s = d.getElementsByTagName('script')[0];
            g.async = true; g.src = u + 'matomo.js'; s.parentNode.insertBefore(g, s);
        })();
    </script>
    <noscript>
        <p><img src="//brickdata.xyz/matomo.php?idsite=7&amp;rec=1" style="border:0;" alt="" /></p>
    </noscript>
    <script src="js/calendar.js?v=<?php echo $version; ?>"></script>
</head>
<body>
    <div style="position: relative;">
        <h1 class="site-title"><a href="./" id="home-link">Calvin &amp; Hobbes</a></h1>
        <div id="size-toggle" style="position: absolute; top: 10px; right: 10px; font-size: 14px;">
            <a href="#" onclick="toggleImageSize(); return false;" style="text-decoration: underline; color: #0066cc;">Full Size</a>
        </div>
    </div>

    <form action="<?php echo $thisScript; ?>" method="get" id="main-menu">
    <input type="hidden" name="issubmit" value="1">
    <input type="text" name="q" id="q" value="<?php echo htmlentities($q ?? ''); ?>">
    <input type="submit" name="submit" value="Search">
    &nbsp;&nbsp;&nbsp;
    <a class="function" href="./">Home</a>
    &nbsp;&bull;&nbsp;
    <a class="function" href="./?issubmit=1">Display text of all strips</a>
    </form>

    <script>
        // Image size toggle
        window.imageScale = 50; // 50% or 100%
        
        function toggleImageSize() {
            const img = document.getElementById('comic-image');
            const toggle = document.getElementById('size-toggle').querySelector('a');
            
            if (window.imageScale === 50) {
                window.imageScale = 100;
                img.style.width = '100%';
                toggle.textContent = 'Half Size';
            } else {
                window.imageScale = 50;
                img.style.width = '50%';
                toggle.textContent = 'Full Size';
            }
        }

        function goHome() {
            // Clear search input and reset to home view
            document.getElementById('q').value = '';
            document.getElementById('comic-container').style.display = 'block';
            document.getElementById('calendar-container').style.display = 'block';
            document.getElementById('search-results').style.display = 'none';
            loadRandomComic();
        }

        // Load random comic on page load
        window.addEventListener('DOMContentLoaded', function() {
            loadRandomComic();
        });

        function loadRandomComic() {
            fetch('/api/random-comic')
                .then(response => {
                    if (!response.ok) {
                        // If API fails, generate a random date on client side
                        return generateRandomComicClientSide();
                    }
                    return response.json();
                })
                .then(data => {
                    updateComicDisplay(data);
                })
                .catch(error => {
                    console.error('Error loading comic:', error);
                    // Fallback to client-side random comic
                    const data = generateRandomComicClientSide();
                    updateComicDisplay(data);
                });
        }

        function generateRandomComicClientSide() {
            // Generate a random date between Nov 18, 1985 and Dec 31, 1995
            const startDate = new Date(1985, 10, 18); // Nov 18, 1985
            const endDate = new Date(1995, 11, 31);   // Dec 31, 1995
            const randomTime = startDate.getTime() + Math.random() * (endDate.getTime() - startDate.getTime());
            const randomDate = new Date(randomTime);
            
            const year = randomDate.getFullYear();
            const month = randomDate.getMonth() + 1;
            const day = randomDate.getDate();
            
            return {
                date: `${year}-${String(month).padStart(2, '0')}-${String(day).padStart(2, '0')}`,
                image: `${year}/${String(month).padStart(2, '0')}/${year}${String(month).padStart(2, '0')}${String(day).padStart(2, '0')}.jpg`,
                books: '',
                text: ''
            };
        }

        function updateComicDisplay(data) {
            const comicImg = document.getElementById('comic-image');
            const comicLink = document.getElementById('comic-link');
            const comicDate = document.getElementById('comic-date');
            
            if (data.image) {
                comicImg.src = data.image;
                comicLink.href = data.image;
                
                // Store current comic date for arrow key navigation
                window.currentComicDate = data.date;
                
                const [y, m, d] = data.date.split('-').map(Number);
                const date = new Date(y, m - 1, d); // construct locally to avoid timezone shift
                const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                comicDate.innerHTML = `<a target="_blank" href="${data.image}">${date.toLocaleDateString('en-US', options)}</a>`;

                // Sync calendar highlight to the loaded comic
                if (typeof generateCalendar === 'function') {
                    currentYear = y;
                    currentMonth = m;
                    generateCalendar(currentYear, currentMonth);
                }
            }
        }

        function startSlideshow() {
            window.location.href = '/?slide=1985-11-18';
        }

        // Handle form submission
        document.querySelector('#main-menu').addEventListener('submit', function(e) {
            const searchInput = document.getElementById('q');
            if (searchInput.value) {
                // PHP will handle the search
                return true;
            }
        });

        function performSearch(query) {
            fetch(`/api/search?q=${encodeURIComponent(query)}`)
                .then(response => response.json())
                .then(data => {
                    displaySearchResults(data);
                })
                .catch(error => {
                    console.error('Error searching:', error);
                });
        }

        function displaySearchResults(data) {
            const comicContainer = document.getElementById('comic-container');
            const searchResults = document.getElementById('search-results');
            const calendarContainer = document.getElementById('calendar-container');
            
            comicContainer.style.display = 'none';
            calendarContainer.style.display = 'none';
            searchResults.style.display = 'block';
            
            let html = '<h2>Search Results</h2>';
            
            if (data.comics && data.comics.length > 0) {
                data.comics.forEach(comic => {
                    const date = new Date(comic.date);
                    const options = { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' };
                    html += `
                        <div style="margin-bottom: 20px;">
                            <a target="_blank" href="${comic.image}">${date.toLocaleDateString('en-US', options)}</a>
                            <div class="quote">${comic.text}</div>
                        </div>
                    `;
                });
            } else {
                html += '<b style="color: #900;">No records found</b>';
            }
            
            searchResults.innerHTML = html;
        }
    </script>

    <div id="comic-container">
        <br />
        <div class="function">
            <span id="comic-date">Loading...</span>
        </div>
        <a id="comic-link" target="_blank" href="">
            <img id="comic-image" src="" />
        </a>
    </div>

    <!-- Calendar Component -->
    <div id="calendar-container"></div>

    <div id="search-results" style="display: none;">
        <!-- Search results will be inserted here -->
    </div>

    <?php

    if (!isset($_REQUEST['issubmit']))     // if nothing yet submitted, let JavaScript load a random comic
    {
        // JavaScript will handle loading a random comic; no DB needed
    } else {
        $mysqli = databaseOpen();
        // Display search results
        ?>
        <script>
            // Hide comic container and calendar when showing search results
            document.getElementById('comic-container').style.display = 'none';
            document.getElementById('calendar-container').style.display = 'none';
            document.getElementById('search-results').style.display = 'block';
        </script>
        <div id="search-results-content">
        <?php
        if (!$q)        // if nothing entered for search, return all records
        {
            $isLimit = True;
            $sql = "SELECT SQL_CALC_FOUND_ROWS * FROM ch ORDER BY ch_date";
        } else {            // otherwise create search string
            $sql = "SELECT SQL_CALC_FOUND_ROWS *, MATCH (ch_text) AGAINST ('".strSQL($q)."' IN NATURAL LANGUAGE MODE) AS score FROM ch WHERE MATCH (ch_text) AGAINST ('".strSQL($q)."' IN NATURAL LANGUAGE MODE)";

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
        <h2>Search Results</h2>
        <div style="padding: 2px; background-color: #CCF;">
        <a href="<?php echo $thisScript; ?>?q=<?php echo htmlentities($q); ?>&issubmit=1&page=1">&lt;&lt;</a>&nbsp;<a href="<?php echo $thisScript; ?>?q=<?php echo htmlentities($q); ?>&issubmit=1&page=<?php echo max(1, $page-1); ?>">&lt;</a>&nbsp;<?php
        ?>Page <?php echo $page; ?><?php
        ?>&nbsp;<a href="<?php echo $thisScript; ?>?q=<?php echo htmlentities($q); ?>&issubmit=1&page=<?php echo min(intval($rowmax/PAGE_SIZE), $page+1); ?>">&gt;</a>&nbsp;<a href="<?php echo $thisScript; ?>?q=<?php echo htmlentities($q); ?>&issubmit=1&page=<?php echo intval($rowmax/PAGE_SIZE)+1; ?>">&gt;&gt;</a>

        &nbsp; Displaying <?php echo ($page-1)*PAGE_SIZE+1; ?>-<?php echo ($page-1)*PAGE_SIZE+min(PAGE_SIZE, mysqli_num_rows($res)); ?> of <?php echo $rowmax; ?> records.
        </div><br /><?php
        $cnt = 0;
        // text display strips
        while (($row = mysqli_fetch_object($res)) && (!$isLimit || $cnt < PAGE_SIZE))       // for every record
        {
            $date = sql2date($row->ch_date);        // get strip date
            $imageFile = date('Y/m/Ymd',$date).'.jpg';      // get strip image file name and path
            ?>
            <a target="_blank" href="<?php echo $imageFile; ?>">
            <?php echo date('l, F jS, Y',$date); ?></a>
            &nbsp;&bull;&nbsp;
            <a href="#" onclick="window.open('./book.php?book=<?php echo urlencode($row->ch_books); ?>', 'newwindow', 'width=600,height=600'); return false;"><I>book</I></a>
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
        ?>
        </div>
        <?php
        mysqli_close($mysqli);
    }
    // Add footer page
    include 'footer.php';
    ?>
  </body>

</html>
