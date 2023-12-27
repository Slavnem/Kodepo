<?php
// veritabanına ait değerleri tuttuğumuz değişkenler
$servername = "localhost";
$databaseuser = "root";
$databasepassword = "kadir";
$databasename = "kodepo";

// veritabanına bağlantı oluşturma
try {
  // mysqli bağlantısı oluşturma
  $databaseconn = new mysqli(
    $servername,
    $databaseuser,
    $databasepassword,
    $databasename
  );

  // veritabanına ait ayarları yapma
  mysqli_set_charset($databaseconn, "UTF8");
  mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
}
// hatayı yakalama
catch(mysqli_sql_exception $error) {
  // hatayı çıktı ver ve bitir
  die($error);
  session_destroy(); // oturumu bitir
  exit();
}
?>
