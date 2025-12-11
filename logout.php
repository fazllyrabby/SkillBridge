<?php
require_once 'php/functions.php';

session_start();
session_destroy();
redirect('home.php', 'Logged out successfully!');
?>