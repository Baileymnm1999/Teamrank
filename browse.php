<?php
include 'utils.php';

authenticate();

html_header('Browse');

webpage_begin('Browse');

$conn = connect();

if ($result = $conn->query('CALL `Leagues`')) {
    $headers = array('League', 'Number of Teams', 'Number of Seasons');

    // turn result set to table
    table_begin();
    table_header($headers);
    to_table($headers, $result, './seasons?league=%u', 'LeagueID');
    table_end();

    $stmt->close();
}
$conn->close();

html_footer();
