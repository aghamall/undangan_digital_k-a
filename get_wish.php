<?php
include 'koneksi.php';

$data = $conn->query("SELECT * FROM wishes ORDER BY id DESC");

while($d = $data->fetch_assoc()){

  $tanggal = date('d F Y - H:i', strtotime($d['created_at']));

  echo "
  <div class='wish-item'>
    <b>{$d['nama']}</b><br>
    {$d['pesan']}<br>
    <small style='color:#ccc'>{$tanggal} WIB</small>
  </div>
  ";
}
?>