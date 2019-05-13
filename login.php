<?php
include 'utils.php';

if (isset($_POST[username]) && isset($_POST[password])) {
    $conn = connect();

    if ($stmt = $conn->prepare('SELECT `Password`, `ID` FROM `User` WHERE `Username` = ?')) {
        $stmt->bind_param('s', $_POST[username]);
        $stmt->execute();

        $result = $stmt->get_result();

        if ($result->num_rows == 0) {
            setcookie('error', 'Username not found', time() + 60);
            header('Location: ./sign-in');
            $stmt->close();
            $conn->close();
            exit();
        }

        $user = $result->fetch_assoc();
        $stmt->close();

        if (!password_verify($_POST[password], $user[Password])) {
            setcookie('error', 'Password incorrect', time() + 60);
            header('Location: ./sign-in');
            $conn->close();
            exit();
        }

        $cookie = make_cookie();

        if ($stmt = $conn->prepare('UPDATE `User` SET `Cookie` = ? WHERE `Username` = ?')) {
            $stmt->bind_param('ss', $cookie, $_POST[username]);
            $stmt->execute();
            $stmt->close();

            setcookie('user', $cookie, time() + 3600);
            setcookie('username', $_POST[username], time() + 3600);
            setcookie('id', $user[ID]);
        } else {
            header('Location: ./error/500');
            exit();
        }
    } else {
        header('Location: ./error/500');
        exit();
    }
    $conn->close();
} else {
    setcookie('error', 'Please enter username and password', time() + 60);
    header('Location: ./sign-in');
    exit();
}

header('Location: ./home');
