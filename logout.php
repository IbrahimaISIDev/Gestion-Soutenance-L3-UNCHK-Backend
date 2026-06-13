<?php
require_once __DIR__ . '/includes/bootstrap.php';
logout();
flash('success', 'Vous avez été déconnecté.');
redirect('/login.php');
