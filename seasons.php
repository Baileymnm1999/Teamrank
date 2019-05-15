<?php
include 'utils.php';

authenticate();

html_header('Browse');

webpage_begin('Browse');

if (isset($_GET[league])) {
    $conn = connect();

    if ($stmt = $conn->prepare('SELECT Name FROM `League` WHERE ID = ?')) {

        // get team name
        $stmt->bind_param('i', $_GET[league]);
        $stmt->execute();
        $result = $stmt->get_result();

        echo '<h3 style="margin: 10px;">' . $result->fetch_assoc()['Name'] . ' Seasons</h3>';

        $stmt->close();
    } else {
        header('Location: ./error/500');
        $conn->close();
        exit();
    }

    if ($stmt = $conn->prepare('CALL `Seasons`(?)')) {
        $stmt->bind_param('i', $_GET[league]);
        $stmt->execute();
        $result = $stmt->get_result();

        $headers = array('Season', 'Season Start Date', 'Number of Teams');
        // turn result set to table
        table_begin();
        table_header($headers);
        to_table($headers, $result, './ranks?season=%u', 'ID');
        table_end();

        $stmt->close();
    } else {
        header('Location: ./error/500');
        $conn->close();
        exit();
    }
    $conn->close();
}

html_footer();
