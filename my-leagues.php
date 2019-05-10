<?php
include 'utils.php';

authenticate();

html_header('Browse');

webpage_begin('My Leagues');


echo '<h3 style="margin: 10px;">My Leagues</h3>';

$conn = connect();

if ($stmt = $conn->prepare('CALL `My_Leagues`(?)')) {
    $stmt->bind_param('i', $_COOKIE[id]);
    $stmt->execute();
    $result = $stmt->get_result();

    $headers = array('League', 'Number of Teams', 'Number of Seasons', 'Following');
    $keys = array('League', 'Number of Teams', 'Number of Seasons');

    // turn result set to table
    table_begin();
    table_header($headers);

    echo '<tbody>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr class="clickable-row" data-href="' . sprintf('./seasons?league=%u', $row['LeagueID']) . '">';

        foreach ($keys as $key) {
            echo '<td>' . $row[$key] . '</td>';
        }

        echo '<td><a href="./follow?league=' . $row['LeagueID'] . '&origin=' . $_SERVER[REQUEST_URI] . '" class="btn btn-sm btn-danger"><span class="fas fa-minus-circle"></span> </a></td>';

        echo '</tr>';
    }

    echo '</tbody>';

    table_end();

    $stmt->close();
}
$conn->close();

html_footer();
