<?php

function is_database_exist($conn, $dbname) {
      $sql = "SHOW DATABASES LIKE '" . $dbname . "'";
      if ($conn->query($sql)->num_rows > 0) {
          return true;
      } else {
          return false;
      }
}

function is_table_exist($conn, $tbname) {
      $sql = "SHOW TABLES LIKE '" . $tbname . "'";
      if ($conn->query($sql)->num_rows > 0) {
          return true;
      } else {
          return false;
      }
}


include_once('config.php');
$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if (!is_database_exist($conn, DB_NAME)) {
    $sql = "CREATE DATABASE ".DB_NAME;
    if ($conn->query($sql) === TRUE) {
        echo "Database created successfully\n";
    } else {
        die("Error creating database: " . $conn->error);
    }
}

$conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME);

if (!is_table_exist($conn, "accounts")) {
    $sql = "CREATE TABLE accounts (" .
           "id CHAR(16) PRIMARY KEY," .
           "name VARCHAR(100) NOT NULL," .
           "balance int(32)" .
           ")";
    if ($conn->query($sql) === TRUE) {
        echo "Table accounts created successfully\n";
    } else {
        die("Error creating table accounts: " . $conn->error);
    }
}

$conn->close();
?>
