<?php
include 'utils.php';

authenticate();

html_header('Manage');

webpage_begin('Manage');

$conn = connect();

if (isset($_GET[addleague])) {
    add_league();
    html_footer();
    $conn->close();
    exit();
}

if (isset($_GET[addseason])) {
    if (isset($_GET[season])) {
        add_season('./addseason?editSeason=' . $_GET[season] . '&league=' . $_GET[league]);
    } else {
        add_season('./addseason?league=' . $_GET[league]);
    }

    html_footer();
    $conn->close();
    exit();
}

if (isset($_GET[addteam])) {
    if (isset($_GET[team])) {
        add_team('./addteam?editTeam=' . $_GET[team] . '&league=' . $_GET[league]);
    } else {
        add_team('./addteam?league=' . $_GET[league]);
    }
    html_footer();
    $conn->close();
    exit();
}

if (isset($_GET[addgame]) && isset($_GET[season]) && isset($_GET[order])) {
    add_game('./addgame?season=' . $_GET[season] . '&order=' . $_GET[order]);
    html_footer();
    $conn->close();
    exit();
}

if (isset($_GET[league])) {
    if ($stmt = $conn->prepare('SELECT Name FROM `League` WHERE ID = ?')) {

        // get team name
        $stmt->bind_param('i', $_GET[league]);
        $stmt->execute();
        $result = $stmt->get_result();

        echo '<h3 style="margin: 10px;">Manage ' . $result->fetch_assoc()['Name'] . '</h3>';

        $stmt->close();
    } else {
        header('Location: ./error/500');
        $conn->close();
        exit();
    }

    echo '<div class="row" style="margin: 0;">';

    if ($stmt = $conn->prepare('CALL `Seasons`(?)')) {
        $stmt->bind_param('i', $_GET[league]);
        $stmt->execute();
        $result = $stmt->get_result();

        echo '<div class="col-md-7" style="padding-right: 2em;">';

        $headers = array('Season', 'Season Start Date', 'Number of Teams', 'Delete', 'Edit');
        // turn result set to table
        table_begin();
        table_header($headers);

        echo '<tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr class="clickable-row" data-href="./manage?season=' . $row['ID'] . '&order=' . $row['Season'] . '">';

            foreach (array('Season', 'Season Start Date', 'Number of Teams') as $key) {
                echo '<td>' . $row[$key] . '</td>';
            }

            echo '<td><a href="./delete?season=' . $row['ID'] . '&origin=' . $_SERVER[REQUEST_URI] . '" class="btn btn-sm btn-danger"><span class="fas fa-trash-alt"></span> </a></td>';
            echo '<td><a href="./manage?addseason=1&season=' . $row['ID'] . '&start=' . $row['Season Start Date'] . '&end=' . $row['Season End Date'] . '&league=' . $_GET['league'] . '" class="btn btn-sm btn-success"><span class="fas fa-pencil-alt"></span> </a></td>';

            echo '</tr>';
        }

        echo '</tbody>';


        echo '<tr><td colspan="4" class="text-center"><a href="./manage?addseason=1&league=' . $_GET[league] . '" class="btn btn-sm btn-info"><span class="fas fa-plus"></span>&nbsp;&nbsp;&nbsp;Add a season</a></td>';

        table_end();

        $stmt->close();
    } else {
        header('Location: ./error/500');
        $conn->close();
        exit();
    }

    echo '</div>';

    echo '<div class="col-md-5" style="padding-left: 2em;">';

    if ($stmt = $conn->prepare('CALL `Teams`(?)')) {
        $stmt->bind_param('i', $_GET[league]);
        $stmt->execute();
        $result = $stmt->get_result();


        $headers = array('Team', 'Games Played', 'Delete', 'Edit');
        // turn result set to table
        table_begin();
        table_header($headers);

        echo '<tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';

            foreach (array('Team', 'Games Played') as $key) {
                echo '<td>' . $row[$key] . '</td>';
            }

            echo '<td><a href="' . (($row['Games Played'] != 0) ? '' :  './delete?team=' . $row['ID'] . '&origin=' . $_SERVER[REQUEST_URI]) . '" class="btn btn-sm btn-danger ' . (($row['Games Played'] != 0) ? 'disabled' : '') . '"><span class="fas fa-trash-alt"></span> </a></td>';
            echo '<td><a href="./manage?addteam=1&team=' . $row['ID'] . '&league=' . $_GET[league] . '&name=' . $row['Team'] . '" class="btn btn-sm btn-success"><span class="fas fa-pencil-alt"></span> </a></td>';

            echo '</tr>';
        }

        echo '</tbody>';

        echo '<tr><td colspan="3" class="text-center"><a href="./manage?addteam=1&league=' . $_GET[league] . '" class="btn btn-sm btn-info"><span class="fas fa-plus"></span>&nbsp;&nbsp;&nbsp;Add a team</a></td>';

        table_end();

        $stmt->close();
    } else {
        header('Location: ./error/500');
        $conn->close();
        exit();
    }

    echo '</div></div>';
} elseif (isset($_GET[season]) && isset($_GET[order])) {
    if ($stmt = $conn->prepare('SELECT l.Name FROM `League` l JOIN `Season` s ON l.ID = s.LeagueID AND s.ID = ? GROUP BY l.name')) {

        // get team name
        $stmt->bind_param('i', $_GET[season]);
        $stmt->execute();
        $result = $stmt->get_result();

        echo '<h3 style="margin: 10px;">' . $result->fetch_assoc()['Name'] . ' Season ' . $_GET[order] . ' Games</h3>';

        $stmt->close();
    } else {
        header('Location: ./error/500');
        $conn->close();
        exit();
    }

    if ($stmt = $conn->prepare('CALL `Games`(?)')) {
        $stmt->bind_param('i', $_GET[season]);
        $stmt->execute();
        $result = $stmt->get_result();

        $headers = array('Date', 'Teams', 'Winner', 'Score', 'Delete');
        // turn result set to table
        table_begin();
        table_header($headers);

        echo '<tbody>';

        while ($row = $result->fetch_assoc()) {
            echo '<tr>';

            foreach (array('Date', 'Teams', 'Winner', 'Score') as $key) {
                echo '<td>' . $row[$key] . '</td>';
            }

            echo '<td><a href="./delete?game=' . $row['ID'] . '&origin=' . $_SERVER[REQUEST_URI] . '" class="btn btn-sm btn-danger"><span class="fas fa-trash-alt"></span> </a></td>';

            echo '</tr>';
        }

        echo '</tbody>';


        echo '<tr><td colspan="5" class="text-center"><a href="./manage?addgame=1&season=' . $_GET[season] . '&order=' . $_GET[order] . '" class="btn btn-sm btn-info"><span class="fas fa-plus"></span>&nbsp;&nbsp;&nbsp;Add a game</a></td>';

        table_end();

        $stmt->close();
    } else {
        header('Location: ./error/500');
        $conn->close();
        exit();
    }
} else {
    echo '<h3 style="margin: 10px;">Moderated Leagues</h3>';

    if ($stmt = $conn->prepare('CALL `Moderated_Leagues`(?)')) {
        $stmt->bind_param('i', $_COOKIE[id]);
        $stmt->execute();
        $result = $stmt->get_result();

        $headers = array('League', 'Number of Teams', 'Number of Seasons', 'Delete', 'Edit');

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
            echo '<td><a href="./manage?addleague=' . $row['LeagueID'] . '&league=' . $row['LeagueID'] . '&name=' . $row['League'] . '" class="btn btn-sm btn-success"><span class="fas fa-pencil-alt"></span> </a></td>';

            echo '</tr>';
        }

        echo '</tbody>';

        echo '<tr><td colspan="4" class="text-center"><a href="./manage?addleague=1" class="btn btn-sm btn-info"><span class="fas fa-plus"></span>&nbsp;&nbsp;&nbsp;Add a league</a></td>';

        table_end();

        $stmt->close();
    } else {
        header('Location: ./error/500');
        $conn->close();
        exit();
    }
}
$conn->close();

html_footer();
