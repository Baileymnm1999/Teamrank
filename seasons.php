<?php
include 'utils.php';

authenticate();

html_header('Browse');

webpage_begin('Browse');

if (isset($_GET[league])) {
    $conn = connect();

    if ($stmt = $conn->prepare('CALL `Seasons`(?)')) {
        $stmt->bind_param('i', $_GET[league]);
        $stmt->execute();
        $result = $stmt->get_result();

        $headers = array('Season', 'Season Start Date', 'Number of Teams');
        // turn result set to table
        table_begin();
        table_header($headers);
        to_table($headers, $result, './ranks?season=%u', 'Season');
        table_end();

        $stmt->close();
    }
    $conn->close();
}

html_footer();
