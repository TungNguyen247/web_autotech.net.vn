<?php
/**
 * admin/logout.php — Destroy admin session and redirect to login.
 */

require_once __DIR__ . '/../includes/helpers.php';
bootstrap();
logout_admin();
redirect('login.php');
