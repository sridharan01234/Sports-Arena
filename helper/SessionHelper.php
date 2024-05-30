<?php

session_start();

if (isset($_COOKIE['PHPSESSID'])) {
    $cookieSessionId = $_COOKIE['PHPSESSID'];
}

if (isset($_SERVER['HTTP_X_SESSION_ID'])) {
    $headerSessionId = $_SERVER['HTTP_X_SESSION_ID'];
}

if (empty($cookieSessionId)) {
    $cookieSessionId = session_id();
    setcookie('PHPSESSID', $cookieSessionId, time() + 3600, '/');
}

if (empty($headerSessionId)) {
    $headerSessionId = session_id();
    header('X-SESSION-ID: ' . $headerSessionId);
}
