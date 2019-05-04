<?php
include 'utils.php';

authenticate();

html_header('Browse');

webpage_begin('Browse');

if (isset($_GET[season])) {
    $conn = connect();

    if ($stmt = $conn->prepare('CALL `Season_Ranks`(?)')) {
        $stmt->bind_param('i', $_GET[season]);
        $stmt->execute();
        $result = $stmt->get_result();

        $headers = array('Rank', 'Team', 'Rating', 'Wins', 'Losses');
        // turn result set to table
        table_begin();
        table_header($headers);
        to_table($headers, $result, './schedule?team=%u&season=' . $_GET[season], 'TeamID');
        table_end();

        $stmt->close();
    }
    $conn->close();
}

html_footer();
