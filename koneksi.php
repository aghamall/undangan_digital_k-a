<?php
$conn = new mysqli("localhost","root","","wedding");

if($conn->connect_error){
  die("Koneksi gagal");
}
?>