<?php
include 'koneksi.php';

$nama = $_POST['nama'];
$hadir = $_POST['hadir'];

$conn->query("INSERT INTO rsvp (nama, kehadiran) VALUES ('$nama','$hadir')");
echo "ok";
?>