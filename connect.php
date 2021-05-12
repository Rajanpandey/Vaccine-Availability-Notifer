<?php
$conn = mysqli_connect("localhost", "root", "", "vaccnotifier"); // For XAMPP
// $conn = mysqli_connect("database", "root", "tiger", "docker"); // For Docker

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
