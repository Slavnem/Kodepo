<?php
// Oturum başlamamışsa başlat
if(session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

// Oturum yöneticisi
class SessionManager {
    // gizli değişkenler
    private $databaseConnection;
    
    // veritabanı yapı
    public function __construct($databaseConnection) {
      // veritabanı bağlantısı
      $this->databaseConnection = $databaseConnection;
    }

    // Oturum tanımlayıcılar
    public static $session_var_id = "oturum-id";
    public static $session_var_username = "oturum-kullaniciad";
    public static $session_var_password = "oturum-sifre";
    public static $session_var_email = "oturum-email";
    public static $session_var_name = "oturum-ad";
    public static $session_var_surname = "oturum-soyad";
    public static $session_var_rank = "oturum-rutbe";
    public static $session_var_language = "oturum-dil";
    public static $session_var_customization_mod = "oturum-ozellestirme-mod";
    public static $session_var_customization = "oturum-ozellestirme";
    public static $session_var_registertime = "oturum-kayit-tarih";
    public static $session_var_logged = "oturum-giris-yapildi";
    public static $session_var_storefile = "oturum-dosya-depo";
    public static $session_var_maxfilesize = "oturum-max-dosya-boyut";
    public static $session_var_maxbackup = "oturum-max-yedekleme";

    public static $customization_mod_var_message_ask = 0;
    public static $customization_mod_var_message_info = 1;

    // Oturum değişkenini ayarlama
    public static function set($key, $value = null) {
        $_SESSION[$key] = $value;
    }

    // Oturum değişkenini alma
    public static function get($key) {
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    // Oturum değişkenini silme
    public static function remove($key) {
        unset($_SESSION[$key]);
    }

    // Oturumu temizleme
    public static function clear() {
        session_unset();
        session_destroy();
    }

    // Oturum özelleştirmelerini kaydetme
    public function customization(int $id = 0, int $mod = 1) {
        // verileri tutacak olan dizi
        $customization_mod_array = [];

        // Oturuma ait özelleştirmeleri getirme
        // Kullanıcı id'si ve seçili mod'a göre getirme
        $sqlGetCustomizationData = "SELECT * FROM ". ConfigTable::$table_f
        ." WHERE " .ConfigTable::$column_table_f_1 ." = ?"
        ." AND " .ConfigTable::$column_table_f_4 ." = ?";

        // Sorguyu bağlantısı
        $stmtGetCustomizationData = $this->databaseConnection->prepare($sqlGetCustomizationData);

        // eğer özelleştirme modu yoksa sistem otomatik modu alsın
        // Sorguyu parametrelerini alma
        $temp_session_var_id = $id;
        $temp_session_var_customization_mod = $mod;

        $stmtGetCustomizationData->bind_param("ii",
            $temp_session_var_id,
            $temp_session_var_customization_mod
        );

        // Sorguyu çalıştırma
        $stmtGetCustomizationData->execute();

        // Sorgu sonucunu alma
        $resultGetCustomizationData = $stmtGetCustomizationData->get_result();

        // Kullanıcı modu varsa onu alsın
        switch($resultGetCustomizationData->num_rows) {
            // Eğer kullanının hiç modu yoksa otomatik sistem modunu alsın
            case 0:
                $temp_session_var_id = 0; // kodepo id
                $temp_session_var_customization_mod = 1; // kodepo mod

                $stmtGetCustomizationData->bind_param("ii",
                    $temp_session_var_id, // kodepo id
                    $temp_session_var_customization_mod // kodepo mod id
                );

                // Sorguyu çalıştırma
                $stmtGetCustomizationData->execute();

                // Sorgu sonucunu al
                $resultGetCustomizationData = $stmtGetCustomizationData->get_result();
            break;
        }

        // verileri çek diziye aktar
        while($fetchGetCustomizationData = $resultGetCustomizationData->fetch_assoc()) {
            $customization_mod_array[] = $fetchGetCustomizationData;
        }

        // veriler aktarılmış olan diziyi döndür
        return $customization_mod_array;
    }

    // Oturumu kaydetme
    public function save(int $id = null) {
        // geçerli id zorunlu
        if($id == null || $id < 1)
        {
            // Oturum bilgilerini sıfırla
            SessionManager::set(SessionManager::$session_var_id, null);
            SessionManager::set(SessionManager::$session_var_username, null);
            SessionManager::set(SessionManager::$session_var_password, null);
            SessionManager::set(SessionManager::$session_var_email, null);
            SessionManager::set(SessionManager::$session_var_name, null);
            SessionManager::set(SessionManager::$session_var_surname, null);
            SessionManager::set(SessionManager::$session_var_rank, null);
            SessionManager::set(SessionManager::$session_var_language, 2);
            SessionManager::set(SessionManager::$session_var_customization_mod, 1);
            SessionManager::set(SessionManager::$session_var_registertime, null);
            SessionManager::set(SessionManager::$session_var_storefile, null);
            SessionManager::set(SessionManager::$session_var_maxfilesize, 0);
            SessionManager::set(SessionManager::$session_var_maxbackup, 0);
            SessionManager::set(SessionManager::$session_var_logged, false);

            // Oturum özelleştirmeleri
            SessionManager::set(
                SessionManager::$session_var_customization,
                SessionManager::customization(0,1)
            );

            // hata döndür
            return false;
        }

        // Oturum bilgilerini getiren sorgu
        $sqlGetSessionData = "SELECT * FROM " .ConfigTable::$table_a
        ." WHERE " .ConfigTable::$column_table_a_0 ." = ?";

        // Sorgu bağlantısı
        $stmtGetSessionData = $this->databaseConnection->prepare($sqlGetSessionData);

        // Sorgu parametrelerini atama
        $stmtGetSessionData->bind_param("i", $id);

        // Sorguyu çalıştırma
        $stmtGetSessionData->execute();

        // Sonucu al
        $resultGetSessionData = $stmtGetSessionData->get_result();

        // Eğer satır bulunmamışsa
        if(!$resultGetSessionData->num_rows > 0)
            return false;

        // Başlıklar henüz gönderilmediyse
        if (!headers_sent()) {
            // Oturuma ait session id yenileme
            session_regenerate_id();
        }

        // satır bulunmuşsa
        while($fetchGetSessionData = $resultGetSessionData->fetch_assoc()) {
            // Oturuma ait kodların deposunun linki
            $DATA_SESSION_STORE_FILE = FILE_STORE_CODE_UPLOAD . $id ."/";

            // Oturum bilgilerini kaydet
            SessionManager::set(SessionManager::$session_var_id, isset($id) && $id > 0 ? $id : 0);
            SessionManager::set(SessionManager::$session_var_username, $fetchGetSessionData[ConfigTable::$column_table_a_1] == null ? "username n/a" : $fetchGetSessionData[ConfigTable::$column_table_a_1]);
            SessionManager::set(SessionManager::$session_var_password, $fetchGetSessionData[ConfigTable::$column_table_a_2] == null ? "password n/a" : $fetchGetSessionData[ConfigTable::$column_table_a_2]);
            SessionManager::set(SessionManager::$session_var_email, $fetchGetSessionData[ConfigTable::$column_table_a_3] == null ? "email n/a" : $fetchGetSessionData[ConfigTable::$column_table_a_3]);
            SessionManager::set(SessionManager::$session_var_name, $fetchGetSessionData[ConfigTable::$column_table_a_4] == null ? "name n/a" : $fetchGetSessionData[ConfigTable::$column_table_a_4]);
            SessionManager::set(SessionManager::$session_var_surname, $fetchGetSessionData[ConfigTable::$column_table_a_5] == null ? "surname n/a" : $fetchGetSessionData[ConfigTable::$column_table_a_5]);
            SessionManager::set(SessionManager::$session_var_rank, $fetchGetSessionData[ConfigTable::$column_table_a_6] == null ? 0 : ($fetchGetSessionData[ConfigTable::$column_table_a_6]));
            SessionManager::set(SessionManager::$session_var_language, $fetchGetSessionData[ConfigTable::$column_table_a_8] == null ? 2 : ($fetchGetSessionData[ConfigTable::$column_table_a_8]));
            SessionManager::set(SessionManager::$session_var_customization_mod, $fetchGetSessionData[ConfigTable::$column_table_a_9] == null ? 1 : ($fetchGetSessionData[ConfigTable::$column_table_a_9]));
            SessionManager::set(SessionManager::$session_var_registertime, $fetchGetSessionData[ConfigTable::$column_table_a_7]);
            SessionManager::set(SessionManager::$session_var_storefile, $DATA_SESSION_STORE_FILE);
            SessionManager::set(SessionManager::$session_var_maxfilesize, StaticFile::$ARRAY_FILEMAX_RANK[SessionManager::get(SessionManager::$session_var_rank)] == null ? 0 : StaticFile::$ARRAY_FILEMAX_RANK[SessionManager::get(SessionManager::$session_var_rank)]);
            SessionManager::set(SessionManager::$session_var_maxbackup, StaticFile::$ARRAY_FILEMAX_RANK[SessionManager::get(SessionManager::$session_var_rank)] == null ? 0 : StaticFile::$ARRAY_FILEMAX_BACKUP[SessionManager::get(SessionManager::$session_var_rank)]);
            SessionManager::set(SessionManager::$session_var_logged, true);

            // Oturum özelleştirmeleri
            SessionManager::set(
                SessionManager::$session_var_customization,
                SessionManager::customization(
                    SessionManager::get(SessionManager::$session_var_id),
                    SessionManager::get(SessionManager::$session_var_customization_mod)
                )
            );
        }

        // İşlem başarılı
        return true;
    }
}
?>