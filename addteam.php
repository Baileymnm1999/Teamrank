<?php
include 'utils.php';

authenticate();

$conn = connect();

if (isset($_GET[league]) && isset($_POST[teamname])) {
    if (isset($_GET[editTeam])) {
        if ($stmt = $conn->prepare('CALL `Edit_Team`(?, ?)')) {

            // get team name
            $stmt->bind_param('is', $_GET[editTeam], $_POST[teamname]);
            $stmt->execute();

            header('Location: ./manage?league=' . $_GET[league]);

            $stmt->close();
        } else {
            header('Location: ./error/500');
        }
    } elseif ($stmt = $conn->prepare('CALL `Create_Team`(?, ?)')) {

            // get team name
        $stmt->bind_param('is', $_GET[league], $_POST[teamname]);
        $stmt->execute();

        header('Location: ./manage?league=' . $_GET[league]);

        $stmt->close();
    } else {
        header('Location: ./error/500');
    }
} else {
    header('Location: ./error/500');
}

$conn->close();
