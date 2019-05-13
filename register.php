<?php
include 'utils.php';

if (!isset($_POST[username]) || !isset($_POST[password])) {
    $_COOKIE[error] = "Must submit username and password";
} else {
    $conn = connect();

    if ($stmt = $conn->prepare('CALL `Create_User`(?, ?, ?)')) {
        $cookie = make_cookie();

        $stmt->bind_param('sss', $_POST[username], password_hash($_POST[password], PASSWORD_DEFAULT), $cookie);
        $stmt->execute();

        if (mysqli_affected_rows($conn) < 1) {
            setcookie('error', 'Account already exist or the username or password is too long', time() + 60);
            header('Location: ./sign-up');
            exit();
        }

        setcookie('user', $cookie, time() + 3600);
        setcookie('username', $_POST[username], time() + 3600);

        header('Location: ./home');
        exit();
    } else {
        setcookie('error', 'Failed to create account', time() + 60);
    }
}

header('Location: ./sign-up');
