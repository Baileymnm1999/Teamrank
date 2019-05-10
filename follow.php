<?php
include 'utils.php';

authenticate();

if (isset($_GET[league]) && isset($_GET[origin])) {
    $conn = connect();

    if ($stmt = $conn->prepare('CALL `Toggle_Follow`(?, ?)')) {
        $stmt->bind_param('ii', $_GET[league], $_COOKIE[id]);
        $stmt->execute();

        $stmt->close();
    }
    header('Location: ' . $_GET[origin]);
    exit();
}

header('Location: /');
