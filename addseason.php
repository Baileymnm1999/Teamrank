<?php
include 'utils.php';

authenticate();

$conn = connect();

if (isset($_POST[startdate]) && isset($_POST[enddate])) {
    if (isset($_GET[editSeason])) {
        if ($stmt = $conn->prepare('CALL `Edit_Season`(?, ?, ?)')) {

            // get team name
            $stmt->bind_param('iss', $_GET[editSeason], $_POST[startdate], $_POST[enddate]);
            $stmt->execute();

            header('Location: ./manage?league=' . $_GET[league]);

            $stmt->close();
        } else {
            header('Location: ./error/500');
        }
    } elseif (isset($_GET[league])) {
        if ($stmt = $conn->prepare('CALL `Create_Season`(?, ?, ?)')) {

            // get team name
            $stmt->bind_param('iss', $_GET[league], $_POST[startdate], $_POST[enddate]);
            $stmt->execute();

            header('Location: ./manage?league=' . $_GET[league]);

            $stmt->close();
        } else {
            header('Location: ./error/500');
        }
    }
} else {
    header('Location: ./error/500');
}

$conn->close();
