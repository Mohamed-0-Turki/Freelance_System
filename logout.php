<?php
session_start();
setcookie('ID', $row['id'], time() - 1, '/');
setcookie('NAME', $row['Name'], time() -1, '/');
setcookie('ACCESS', $row['UserRole'], time() -1, '/');
setcookie('EMAIL', $email, time() -1, '/');
session_unset();
session_destroy();
header('Location: login.php');
exit();