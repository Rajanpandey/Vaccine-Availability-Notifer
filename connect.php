<?php
$conn = mysqli_connect("localhost", "root", "", "vaccnotifier");

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
