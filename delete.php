<?php
include 'utils.php';

authenticate();

$conn = connect();

if (isset($_GET[league])) {
    if ($stmt = $conn->prepare('CALL `Delete_League`(?, ?, ?)')) {

        // get team name
        $stmt->bind_param('iis', $_GET[league], $_COOKIE[id], $_COOKIE[user]);
        $stmt->execute();

        header('Location: ' . $_GET[origin]);

        $stmt->close();
    } else {
        header('Location: ./error/500');
    }
}

if (isset($_GET[season])) {
    if ($stmt = $conn->prepare('CALL `Delete_Season`(?)')) {

        // get team name
        $stmt->bind_param('i', $_GET[season]);
        $stmt->execute();

        header('Location: ' . $_GET[origin]);

        $stmt->close();
    } else {
        header('Location: ./error/500');
    }
}

if (isset($_GET[team])) {
    if ($stmt = $conn->prepare('CALL `Delete_Team`(?)')) {

        // get team name
        $stmt->bind_param('i', $_GET[team]);
        $stmt->execute();

        header('Location: ' . $_GET[origin]);

        $stmt->close();
    } else {
        header('Location: ./error/500');
    }
}

if (isset($_GET[game])) {
    if ($stmt = $conn->prepare('CALL `Delete_Game`(?)')) {

        // get team name
        $stmt->bind_param('i', $_GET[game]);
        $stmt->execute();

        header('Location: ' . $_GET[origin] . '&order=' . $_GET[order]);

        $stmt->close();
    } else {
        header('Location: ./error/500');
    }
}

$conn->close();
