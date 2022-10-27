<?php
session_start();
$ls_servername = DB_HOST;
$ls_username = DB_USER;
$ls_password = DB_PASSWORD;
$ls_orig_dbname = DB_NAME;

$ls_conn = mysqli_connect($ls_servername, $ls_username, $ls_password, $ls_orig_dbname) or die($ls_conn);
$_SESSION['database'] = $ls_conn;

// Check connection
if ($ls_conn->connect_error) {
    die("Connection failed: " . $ls_conn->connect_error);
}

