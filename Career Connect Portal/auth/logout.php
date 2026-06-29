<?php

session_start();

/* Unset all session variables */
session_unset();

/* Destroy session */
session_destroy();

/* Delete session cookie */
setcookie(
    session_name(),
    '',
    time() - 3600,
    '/'
);

header("Location: ../index.php");
exit();

?>