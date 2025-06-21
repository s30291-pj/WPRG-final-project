<?php
require_once __DIR__.'/../include/config.php';

session_destroy();

header('Location: login.php');

exit;
