<?php
include 'utils.php';

authenticate();

html_header('Schedule');

webpage_begin('Home');

if (isset($_GET[team]) && isset($_GET[season])) {
    $conn = connect();

    if ($stmt = $conn->prepare('SELECT Name FROM `Team` WHERE TeamID = ? LIMIT 1')) {

        // get team name
        $stmt->bind_param('i', $_GET[team]);
        $stmt->execute();
        $result = $stmt->get_result();

        echo '<h3 style="margin: 10px;">' . $result->fetch_assoc()['Name'] . ' Schedule</h3>';

        $stmt->close();
    }
    if ($stmt = $conn->prepare('CALL `Team_Schedule`(?, ?)')) {

        // execute stored procedure for schedule
        $stmt->bind_param('ii', $_GET[team], $_GET[season]);
        $stmt->execute();
        $result = $stmt->get_result();

        // turn result set to table
        table_begin();
        table_header(array('Opponent', 'Date', 'Result', 'Game Type'));
        to_table(array('Opponent', 'Date', 'Result', 'GameType'), $result, './schedule?team=%u&season=' . $_GET[season], 'TeamID');
        table_end();

        $stmt->close();
    }
    $conn->close();
}

html_footer();
