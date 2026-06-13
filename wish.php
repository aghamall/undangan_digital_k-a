<?php
include 'koneksi.php';

$nama = $_POST['nama'];
$pesan = $_POST['pesan'];

$conn->query("INSERT INTO wishes (nama,pesan) VALUES ('$nama','$pesan')");
echo "ok";
?>