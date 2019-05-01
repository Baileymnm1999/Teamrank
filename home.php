<?php
include 'utils.php';

html_header('Home');

webpage_begin('Home');



if (isset($_COOKIE[user])) {
    echo 'cookie set';
} else {
    if (isset($_POST[username]) && isset($_POST[password])) {
        $conn = connect();

        if ($stmt = $conn->prepare('SELECT `Password`, `ID` FROM `User` WHERE `Username` = ?')) {
            $stmt->bind_param('s', $_POST[username]);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();

            if ($_POST[password] != $user[Password]) {
                die();
            }

            $cookie = make_cookie();

            if ($stmt = $conn->prepare('UPDATE `User` SET `Cookie` = ? WHERE `Username` = ?')) {
                $stmt->bind_param('ss', $cookie, $_POST[username]);
                $stmt->execute();
                $stmt->close();
            }

            setcookie('user', $cookie, time() + 3600);

            $_SESSION[id] = id;
        }

        $conn->close();
    } else {
        header('Location: ./sign-in');
    }
}
