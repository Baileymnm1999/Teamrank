<?php

setcookie('user', null, -1);
setcookie('username', null, -1);
header('Location: ./sign-in');
