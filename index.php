<?php
require_once __DIR__ . '/includes/bootstrap.php';
if (isLoggedIn()) redirect(dashboardForRole($_SESSION['user']['role']));
redirect('/login.php');
