<?php
include 'utils.php';

authenticate();

$conn = connect();

if (isset($_GET[season]) && isset($_GET[order])) {
    if (isset($_GET[editGame])) {
        if ($stmt = $conn->prepare('CALL `Edit_Game`(?, ?)')) {

            // get team name
            $stmt->bind_param('is', $_GET[editTeam], $_POST[teamname]);
            $stmt->execute();

            header('Location: ./manage?season=' . $_GET[season] . '&order=' . $_GET[order]);

            $stmt->close();
        } else {
            header('Location: ./error/500');
        }
    } else {
        if ($stmt = $conn->prepare('CALL `Create_Game`(?, ?, ?, ?, ?, ?, ?)')) {

            // get team name
            if (isset($_POST[startdate]) && isset($_POST[teamA]) && isset($_POST[teamB]) && isset($_POST[winner]) && isset($_POST[winningscore]) && isset($_POST[losingscore]) && $_POST[teamA] != $_POST[teamB]) {
                $winner = ($_POST[winner] == "a" ? $_POST[teamA] : $_POST[teamB]);
                $stmt->bind_param('isiiiii', $_GET[season], $_POST[startdate], $_POST[teamA], $_POST[teamB], $winner, $_POST[winningscore], $_POST[losingscore]);
                $stmt->execute();
            }

            header('Location: ./manage?season=' . $_GET[season] . '&order=' . $_GET[order]);

            $stmt->close();
        } else {
            header('Location: ./error/500');
        }
    }
} else {
    header('Location: ./error/500');
}

$conn->close();
