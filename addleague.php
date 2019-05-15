<?php
include 'utils.php';

authenticate();

$conn = connect();

if (isset($_POST[leaguename])) {
    if (isset($_GET[editLeague])) {
        if ($stmt = $conn->prepare('CALL `Edit_League`(?, ?)')) {

            // get team name
            $stmt->bind_param('is', $_GET[editLeague], $_POST[leaguename]);
            $stmt->execute();

            header('Location: ./manage');

            $stmt->close();
        } else {
            header('Location: ./error/500');
        }
    } elseif ($stmt = $conn->prepare('CALL `Create_League`(?, ?)')) {

        // get team name
        $stmt->bind_param('si', $_POST[leaguename], $_COOKIE[id]);
        $stmt->execute();

        header('Location: ./manage');

        $stmt->close();
    } else {
        header('Location: ./error/500');
    }
} else {
    header('Location: ./error/500');
}

$conn->close();
