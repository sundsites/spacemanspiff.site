<?php
require('database_functions.php');
require('utility_functions.php');

$mysqli = databaseOpen();

if (isset($_GET['book'])) {
    $book = trim($_GET['book']);
    $book = mysqli_real_escape_string($mysqli, $book);
    $sql = "SELECT * FROM chbooks WHERE INSTR('$book', chb_id) ORDER BY chb_date";
    $res = mysqli_query($mysqli, $sql);
    if (!$res) {
        echo "Book Query Error = ".mysqli_error($mysqli)."<BR>";
        exit;
    }
    while ($row = mysqli_fetch_object($res)) {
        echo "Book Title: <a href=\"" . htmlspecialchars($row->chb_link) . "\">" . htmlspecialchars($row->chb_title) . "</a><br>";
        echo "Book Publish Date: " . htmlspecialchars($row->chb_date) . "<br>";
        // Add more fields as necessary
    }
    mysqli_free_result($res);
}
?>