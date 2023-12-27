<?php
require_once("important/config-database.php"); // veritabanı
require_once("important/config-database-table.php"); // veritabanı tabloları
require_once("important/config-session.php"); // oturum
require_once("important/static-file.php"); // sabit dosya bilgileri
require_once("important/config-sanitizer-form.php"); // form metin kontrol

// veri ekleme sayfası navigasyon menüsüne ait işlemleri bulunduran sınıf
class DataNavigationToolkit {
    // veritabanı değişken
    protected $databaseConnection;

    // sayfa araçlarına ait bazı işlem kodları
    public static $var_tool_create_project = 0;
    public static $var_tool_create_folder = 1;
    public static $var_tool_add_file = 2;
    public static $var_tool_delete = 3;
    public static $var_tool_backup = 4;
    public static $var_tool_recover_backup = 5;

    // fonksiyon yapı
    public function __construct($databaseConnection) {
        // veritabanı bağlantısı
        $this->databaseConnection = $databaseConnection;
    }

    // veri ekleme
    public function dataCreateNewProject($projectname = null, $projectdescription = "") {
        // oturum id'sini al
        $tempSessionId = SessionManager::get(SessionManager::$session_var_id);

        $projectname = FormSanitizer::sanitizeName($projectname);
        $projectdescription = FormSanitizer::sanitizeName($projectdescription);

        // oturum id'si yoksa
        if($tempSessionId < 1 || $tempSessionId == null) {
            return false;
        }

        // proje adı uygun değilse hata kodula dönsün
        if(FormSanitizer::checkLength($projectname) != true || FormSanitizer::checkLength($projectdescription) != true) {
            return -1;
        }

        // kullanıcıya ait en son eklenmiş proje id'sinin 1 fazlasını alma
        $tempsqlLastId = "SELECT MAX(". ConfigTable::$column_table_b_0 .") + 1 FROM " .ConfigTable::$table_b
        ." WHERE " .ConfigTable::$column_table_b_1 ." = ?";

        $tempstmtLastId = $this->databaseConnection->prepare($tempsqlLastId);
        $tempstmtLastId->bind_param("i", $tempSessionId);
        $tempstmtLastId->execute();
        $tempresultLastId = $tempstmtLastId->get_result();
        $tempfetchLastId = $tempresultLastId->fetch_assoc();
        $tempNewLastId = current($tempfetchLastId);

        // hiç proje yok, baştan numaralandır
        if($tempNewLastId == null || $tempNewLastId < 1) {
            $tempNewLastId = 1;
        }
        
        ////////////////////////
        // projeye ait Url verme
        $tempProjectUrl = FILE_LINK_CODE_UPLOAD .$tempSessionId ."/" .$tempNewLastId ."/";

        // proje sahip id, proje isim, proje açıklama ve proje url
        // bunları kullanıp yeni proje oluşturma
        $sqlCreateProject = ("INSERT INTO " .ConfigTable::$table_b ."("
        .ConfigTable::$column_table_b_1 // proje sahip id
        ."," .ConfigTable::$column_table_b_2 // proje ad
        ."," .ConfigTable::$column_table_b_3 // proje açıklama
        ."," .ConfigTable::$column_table_b_9 // proje url
        .") VALUES(?,?,?,?)");

        // bağlantıyı hazırlama
        $stmtCreateProject = $this->databaseConnection->prepare($sqlCreateProject);
        $stmtCreateProject->bind_param("isss", $tempSessionId, $projectname, $projectdescription, $tempProjectUrl);

        // sorguyu çalıştırıp döndürme
        return $stmtCreateProject->execute();
    }
}
?>