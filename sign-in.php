<!doctype html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <title>Sign In | Teamrank</title>
    <link rel="stylesheet" href="./css/bootstrap.min.css">
    <link rel="stylesheet" href="./css/signin.css">
</head>

<body class="text-center">
    <form class="form-signin" action="login" method="POST">
        <img class="mb-4" src="./img/icon.png" alt="" width="72" height="72">
        <h1 class="h3 mb-3 font-weight-normal">Teamrank&trade;</h1>
        <label for="username" class="sr-only">Username</label>
        <input type="text" id="username" name="username" class="form-control" placeholder="Username" required autofocus>
        <label for="password" class="sr-only">Password</label>
        <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
        <?php
        if (isset($_COOKIE[error])) {
            echo '<br><div class="alert alert-danger" role="alert">' . $_COOKIE[error] . '</div>';
            setcookie('error', null, -1);
        }
        ?>
        <br>
        <button class="btn btn-lg btn-primary btn-block" type="submit">Sign in</button>
        <br>
        <a href="./sign-up">Need an account? Sign up.</a>
        <p class="mt-5 mb-3 text-muted">&copy; 2019</p>
    </form>
</body>

</html>
