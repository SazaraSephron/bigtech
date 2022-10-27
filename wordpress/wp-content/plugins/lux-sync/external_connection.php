<?php
session_start();
$ls_ex_servername = "luxtronic.com.au";
$ls_ex_username = "luxtroni";
$ls_ex_password = "BH2022@luxt";
$ls_ex_orig_dbname = "luxtroni_mobile";
// $ls_ex_servername = DB_HOST;
// $ls_ex_username = DB_USER;
// $ls_ex_password = DB_PASSWORD;
// $ls_ex_orig_dbname = "luxtroni_mobile";

$ls_ex_conn = mysqli_connect($ls_ex_servername, $ls_ex_username, $ls_ex_password, $ls_ex_orig_dbname) or die($ls_ex_conn);
$_SESSION['database_external'] = $ls_ex_conn;

// Check connection
if ($ls_ex_conn->connect_error) {
    die("Connection failed: " . $ls_ex_conn->connect_error);
}
