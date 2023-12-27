<?php
// tablolara erişmemizi sağlayan dosya
require_once("include/database/config-database-table.php");
require_once("include/class/toolkit/class-toolkit-global.php");

// hesaplara erişmemizi sağlayacak olan araç sınıfı
class ToolkitAccount {
  // gizli değişkenler
  private $databaseConnection;
  private $errorArray = array();

  // veritabanı yapı
  public function __construct($databaseConnection) {
    // veritabanı bağlantısı
    $this->databaseConnection = $databaseConnection;
  }

  // oturuma ait id yi veren fonksiyon
  public function getSessionID(string $username) {
    // girilmiş istenmeyen karakterleri temizleme
    // $username = ToolkitGlobal::clearLetter($username);

    // istenmeyen karakterler temizlendiğinde boş kalmışsa, yanlış döndür
    if(empty($username))
      return false;

    // kullanıcı adıyla eşleşecek olan satırı getiren fonksiyon
    $sqlSessionID =
    "SELECT ". ConfigTable::$column_table_a_0
    ." FROM ". ConfigTable::$table_a
    ." WHERE ". ConfigTable::$column_table_a_1 ." = ?";

    // sorgu bağlantısı
    $stmt = $this->databaseConnection->prepare($sqlSessionID);

    // sorgu için gerekli parametreler
    $stmt->bind_param("s", $username);

    // sorguyu çalıştır
    $stmt->execute();

    // sonucu al
    $resultSessionID = $stmt->get_result();

    // satır bulunmuşsa eğer id çek ve döndür
    // hiç satır bulunmamışsa -1 döndür
    return ($resultSessionID->num_rows > 0) ?
      ($resultSessionID->fetch_assoc()[ConfigTable::$column_table_a_0]) :
      (-1);
  }

  // oturuma ait diğer id ile bulunabilen verileri veren fonksiyon
  public function getSessionDataWithID(int $id, int $datanum) {
    // boş ise, yanlış döndür
    if(empty($id))
      return false;

    // girilen istek numarasına göre sorgu hazırlamak
    // eğer şifreyi isterse, hashlenmiş halini direk döndürecek
    switch($datanum) {
      case 1: // KULLANICIAD
        $temp_column = ConfigTable::$column_table_a_1;
        break;
      case 2: // SIFRE
        $temp_column = ConfigTable::$column_table_a_2;
        break;
      case 3: // EMAIL
        $temp_column = ConfigTable::$column_table_a_3;
        break;
      case 4: // AD
        $temp_column = ConfigTable::$column_table_a_4;
        break;
      case 5: // SOYAD
        $temp_column = ConfigTable::$column_table_a_5;
        break;
      case 6: // SEVIYE
        $temp_column = ConfigTable::$column_table_a_6;
        break;
      case 7: // KAYIT
        $temp_column = ConfigTable::$column_table_a_7;
        break;
      // BILINMIYOR
      default:
        return null;
    }

    // istenen numaraya göre id kullanılarak satırı getirecek sorgu
    $sqlSessionDataWithID =
    "SELECT ". $temp_column
    ." FROM ". ConfigTable::$table_a
    ." WHERE ". ConfigTable::$column_table_a_0 ." = ?";

    // sorgu bağlantısı
    $stmt = $this->databaseConnection->prepare($sqlSessionDataWithID);

    // sorgu için gerekli parametreler
    $stmt->bind_param("i", $id);

    // sorguyu çalıştır
    $stmt->execute();

    // sonucu al
    $resultSessionDataWithID = $stmt->get_result();

    // satır bulunmuşsa eğer kullanıcı adını çek ve döndür
    // hiç satır bulunmamışsa -1 döndür
    return ($resultSessionDataWithID->num_rows > 0) ?
      ($resultSessionDataWithID->fetch_assoc()[$temp_column]) :
      (-1);
    // istenen bir değer girmemişse boş döndür
  }

  // oturum id kontrol
  public function checkID(int $value) {
    if(!isset($value))
      return -1;

    // oturum id kontrol
    $sqlCheck = "SELECT * FROM " .ConfigTable::$table_a
    ." WHERE " .ConfigTable::$column_table_a_0 ." = ?";

    // sorguyu hazırlama
    $stmt = $this->databaseConnection->prepare($sqlCheck);

    // sorgu parametrelerini belirtme
    $stmt->bind_param("i", $value);

    // sorguyu çalıştırma
    $stmt->execute();

    // sonucu al
    $result = $stmt->get_result();

    // eğer satır numarası 0'dan büyükse var demek
    // yoksa zaten yoktur 0 döner
    return ($result->num_rows > 0);
  }
}
?>
