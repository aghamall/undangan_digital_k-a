<?php
include 'koneksi.php';
session_start();

$id = $_POST['id'];
$token = $_SESSION['token'];

$conn->query("DELETE FROM wishes WHERE id='$id' AND token='$token'");
?>