<?php
include 'utils.php';

authenticate();

html_header('Browse');

webpage_begin('Browse');

if (isset($_GET[season])) {
    $conn = connect();

    if ($stmt = $conn->prepare('SELECT Name FROM `League` l JOIN `Season` s ON l.ID = s.LeagueID AND s.ID = ?')) {

        // get team name
        $stmt->bind_param('i', $_GET[season]);
        $stmt->execute();
        $result = $stmt->get_result();

        echo '<h3 style="margin: 10px;">' . $result->fetch_assoc()['Name'] . ' Season</h3>';

        $stmt->close();
    }

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
