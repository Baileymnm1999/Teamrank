<?php
include 'utils.php';

authenticate();

html_header('Browse');

webpage_begin('Browse');


echo '<h3 style="margin: 10px;">Leagues</h3>';

$conn = connect();

if ($stmt = $conn->prepare('CALL `Leagues`(?, ?)')) {
    $query = isset($_POST[query]) ? '%' . $_POST[query] . '%' : '%';
    $stmt->bind_param('is', $_COOKIE[id], $query);
    $stmt->execute();
    $result = $stmt->get_result();

    $headers = array('League', 'Number of Teams', 'Number of Seasons', 'Following');
    $keys = array('League', 'Number of Teams', 'Number of Seasons');

    // turn result set to table
    table_begin();
    table_header($headers);
    // to_table($headers, $result, './seasons?league=%u', 'LeagueID');

    echo '<tbody>';

    while ($row = $result->fetch_assoc()) {
        echo '<tr class="clickable-row" data-href="' . sprintf('./seasons?league=%u', $row['LeagueID']) . '">';

        foreach ($keys as $key) {
            echo '<td>' . $row[$key] . '</td>';
        }

        echo '<td><a href="./follow?league=' . $row['LeagueID'] . '&origin=' . $_SERVER[REQUEST_URI] . '" class="btn btn-sm btn-' . ($row['Follows'] == 1 ? 'success' : 'info') . '"><span class="fas fa-' . ($row['Follows'] == 1 ? 'check' : 'plus') . '"></span> </a></td>';

        echo '</tr>';
    }

    echo '</tbody>';

    table_end();

    $stmt->close();
} else {
    header('Location: ./error/500');
    exit();
}

$conn->close();

html_footer();
