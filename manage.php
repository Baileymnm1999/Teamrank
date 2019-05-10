<?php
include 'utils.php';

authenticate();

html_header('Manage');

webpage_begin('Manage');

$conn = connect();

if (isset($_GET[league])) {
    if (isset($_GET[addseason])) {
        add_season('./addseason?league=' . $_GET[league]);
        html_footer();
        exit();
    }

    if (isset($_GET[addteam])) {
        add_team('./addteam?league=' . $_GET[league]);
        html_footer();
        exit();
    }

    if ($stmt = $conn->prepare('SELECT Name FROM `League` WHERE ID = ?')) {

        // get team name
        $stmt->bind_param('i', $_GET[league]);
        $stmt->execute();
        $result = $stmt->get_result();

        echo '<h3 style="margin: 10px;">Manage ' . $result->fetch_assoc()['Name'] . '</h3>';

        $stmt->close();
    }

    echo '<div class="row" style="margin: 0;">';

    if ($stmt = $conn->prepare('CALL `Seasons`(?)')) {
        $stmt->bind_param('i', $_GET[league]);
        $stmt->execute();
        $result = $stmt->get_result();

        echo '<div class="col-md-8" style="padding-right: 2em;">';

        $headers = array('Season', 'Season Start Date', 'Number of Teams', 'Delete');
        // turn result set to table
        table_begin();
        table_header($headers);
        // to_table($headers, $result, './ranks?season=%u', 'Season');

        echo '<tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr class="clickable-row" data-href="' . sprintf('./ranks?season=%u', $row['ID']) . '">';

            foreach (array('Season', 'Season Start Date', 'Number of Teams') as $key) {
                echo '<td>' . $row[$key] . '</td>';
            }

            echo '<td><a href="./delete?season=' . $row['ID'] . '&origin=' . $_SERVER[REQUEST_URI] . '" class="btn btn-sm btn-danger"><span class="fas fa-trash-alt"></span> </a></td>';

            echo '</tr>';
        }

        echo '</tbody>';


        echo '<tr><td colspan="4" class="text-center"><a href="./manage?addseason=1&league=' . $_GET[league] . '" class="btn btn-sm btn-info"><span class="fas fa-plus"></span>&nbsp;&nbsp;&nbsp;Add a season</a></td>';

        table_end();

        $stmt->close();
    }

    echo '</div>';

    echo '<div class="col-md-4" style="padding-left: 2em;">';

    if ($stmt = $conn->prepare('SELECT Name AS `Team`, COUNT(*) AS `Games Played` FROM `Team` t JOIN Plays p ON t.TeamID = p.TeamID WHERE LeagueID = ? GROUP BY t.TeamID')) {
        $stmt->bind_param('i', $_GET[league]);
        $stmt->execute();
        $result = $stmt->get_result();


        $headers = array('Team', 'Games Played');
        // turn result set to table
        table_begin();
        table_header($headers);
        to_table($headers, $result, './manage?league=' . $_GET[league], null);

        echo '<tr><td colspan="3" class="text-center"><a href="./manage?addteam=1&league=' . $_GET[league] . '" class="btn btn-sm btn-info"><span class="fas fa-plus"></span>&nbsp;&nbsp;&nbsp;Add a team</a></td>';

        table_end();

        $stmt->close();
    }

    echo '</div></div>';
} else {
    if (isset($_GET[addleague])) {
        add_league();
        html_footer();
        exit();
    }
    
    echo '<h3 style="margin: 10px;">Moderated Leagues</h3>';

    if ($stmt = $conn->prepare('CALL `Moderated_Leagues`(?)')) {
        $stmt->bind_param('i', $_COOKIE[id]);
        $stmt->execute();
        $result = $stmt->get_result();

        $headers = array('League', 'Number of Teams', 'Number of Seasons', 'Delete');

        // turn result set to table
        table_begin();
        table_header($headers);

        echo '<tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr class="clickable-row" data-href="' . sprintf('./manage?league=%u', $row['LeagueID']) . '">';

            foreach (array('League', 'Number of Teams', 'Number of Seasons') as $key) {
                echo '<td>' . $row[$key] . '</td>';
            }

            echo '<td><a href="./delete?league=' . $row['LeagueID'] . '&origin=' . $_SERVER[REQUEST_URI] . '" class="btn btn-sm btn-danger"><span class="fas fa-trash-alt"></span> </a></td>';

            echo '</tr>';
        }

        echo '</tbody>';

        echo '<tr><td colspan="4" class="text-center"><a href="./manage?addleague=1" class="btn btn-sm btn-info"><span class="fas fa-plus"></span>&nbsp;&nbsp;&nbsp;Add a league</a></td>';

        table_end();

        $stmt->close();
    }
}
$conn->close();

html_footer();
