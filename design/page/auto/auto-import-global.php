<?php
// kullanıcı giriş kontrol dosyası
require_once("component/tool/checker/checker-user.php");
// sayfalar
require_once("data/static/static-page.php");
// dil desteği
require_once("include/support/config-language.php");
// sisteme otomatik tanımlanmış özellikler
require_once("data/static/static-system.php");
// hesap kontrolleri
require_once("include/class/account/class-account.php");
// kullanıcı araçları
require_once("include/class/toolkit/class-toolkit-user.php");
// svg resimleri tutan dosya
require_once("data/static/static-svg.php");
// dosya boyut standartı
require_once("data/static/static-standart.php");

// Otomatik Ekleme
class AutoImportGlobal {
    // veritabanı değişken
    protected $databaseConnection;
    // kontrolcü
    protected $checkeruser;
    // otomatik sistem sorgu objesi
    protected $autosystem;
    // kullanıcı araçları sorgu objesi
    protected $toolkit_user;
    // sistem giriş yapılıp yapılmadığı durumu
    private $logged;

    // kısaltmalar
    public static $var_session_logged = 0;
    public static $var_session_id = 1;
    public static $var_session_username = 2;
    public static $var_session_password = 3;
    public static $var_session_email = 4;
    public static $var_session_firstname = 5;
    public static $var_session_lastname = 6;
    public static $var_session_rank = 7;
    public static $var_session_language = 8;
    public static $var_session_customization_mod = 9;
    public static $var_session_customization = 10;
    public static $var_session_registertime = 11;
    public static $var_session_storefile = 12;
    public static $var_session_maxfilesize = 13;
    public static $var_session_maxbackup = 14;
    public static $var_session_language_name = 15;
    public static $var_session_language_short = 16;

    public static $var_sysdata_logoicon = 0;
    public static $var_sysdata_logoimage = 1;
    public static $var_sysdata_signbackground = 2;
    public static $var_sysdata_background = 3;
    public static $var_sysdata_custom_art_design = 4;

    public static $var_update_username = 0;
    public static $var_update_firstname = 1;
    public static $var_update_lastname = 2;
    public static $var_update_email = 3;
    public static $var_update_language = 5;
    public static $var_update_customization_mod = 6;
    public static $var_update_password = 7;

    public static $var_redirect_homepage = 0;
    public static $var_redirect_codes = 1;
    public static $var_redirect_users = 2;
    public static $var_redirect_account_settings = 3;
    public static $var_redirect_data_add = 4;
    public static $var_redirect_login = 5;
    public static $var_redirect_logout = 6;
    public static $var_redirect_register = 7;
    public static $var_redirect_logout_control = 8;
    public static $var_redirect_refresh = 99;

    public static $imod_write_filetype = 1;

    // fonksiyon yapı
    public function __construct($databaseConnection) {
        // veritabanı bağlantısı
        $this->databaseConnection = $databaseConnection;
        $this->autosystem = new AutoSystem($this->databaseConnection);
        $this->toolkit_user = new ToolkitUser($this->databaseConnection);
        $this->logged = SessionManager::get(SessionManager::$session_var_logged);
        $this->checkeruser = new CheckerUser($this->databaseConnection);
    }

    // dosya boyutu belirleyici
    public static function writeFileSize($filesize, int $inputmode = 0) {
        // her 1024'e bölünebildiğinde dosya boyut türü büyüyor
        for($count = 0; ($filesize) >= BYTESIZE && ($filesize / BYTESIZE) > 0; $count++, $filesize /= BYTESIZE);

        // dosya formatını ondalıklı yapma
        $filesize = sprintf("%.2f", $filesize);

        // çıktı metni
        $outputfilesize = $filesize;

        // eğer dosya türü ismiyle çıktı vermek istemiyorsa değeri döndürsün
        if($inputmode != AutoImportGlobal::$imod_write_filetype) {
            return $outputfilesize;
        }

        // döngü sonucu ne çıktıysa ona göre tür çıktısı verecek
        switch($count) {
            // byte
            case 0:
                $outputfilesize = (int)$outputfilesize . " " . LanguageSupport::getlang(LanguageSupport::$lang_key__text_byte);
                break;
            // kilobyte
            case 1:
                $outputfilesize = $outputfilesize . " " . LanguageSupport::getlang(LanguageSupport::$lang_key__text_kb);
                break;
            // megabyte
            case 2:
                $outputfilesize = $outputfilesize . " " . LanguageSupport::getlang(LanguageSupport::$lang_key__text_mb);
                break;
            // gigabyte
            case 3:
                $outputfilesize = $outputfilesize . " " . LanguageSupport::getlang(LanguageSupport::$lang_key__text_gb);
                break;
            // terabyte
            case 4:
                $outputfilesize = $outputfilesize . " " . LanguageSupport::getlang(LanguageSupport::$lang_key__text_tb);
                break;
        }

        // metini döndür
        return $outputfilesize;
    }

    // oturumu kaydetme
    public function saveSession() {
        // oturum ile işlem yapan obje oluşturma
        $session = new SessionManager($this->databaseConnection);
        // oturumu bilgilerini kaydetme/güncelleme            
        $session->save($session->get(SessionManager::$session_var_id));
    }

    // oturuma ait verileri getirme
    public function getSession(int $num = 0) {
        switch($num) {
            // oturuma giriş yapılıp yapılmadığını getirme
            case 0: return $this->logged;
            // oturuma ait id numarasını getirme
            case 1: return SessionManager::get(SessionManager::$session_var_id);
            // oturuma ait kullanıcı adi
            case 2: return SessionManager::get(SessionManager::$session_var_username);
            // oturuma ait şifre
            case 3: return SessionManager::get(SessionManager::$session_var_password);
            // oturuma ait kullanıcı adi
            case 4: return SessionManager::get(SessionManager::$session_var_email);
            // oturuma ait ad
            case 5: return SessionManager::get(SessionManager::$session_var_firstname);
            // oturuma ait soyad
            case 6: return SessionManager::get(SessionManager::$session_var_lastname);
            // oturuma ait rütbe
            case 7: return SessionManager::get(SessionManager::$session_var_rank);
            // oturuma ait dil
            case 8: return SessionManager::get(SessionManager::$session_var_language);
            // oturuma ait özelleştirme mod id
            case 9: return SessionManager::get(SessionManager::$session_var_customization_mod);
            // oturuma ait özelleştirme
            case 10: return SessionManager::get(SessionManager::$session_var_customization);
            // kullanıcıya ait kayıt tarihi
            case 11: return SessionManager::get(SessionManager::$session_var_registertime);
            // oturuma ait depolama alanı
            case 12: return SessionManager::get(SessionManager::$session_var_storefile);
            // oturuma ait en yüksek dosya yükleme boyutu
            case 13: return SessionManager::get(SessionManager::$session_var_maxfilesize);
            // oturuma ait en yüksek yedekleme miktarı
            case 14: return SessionManager::get(SessionManager::$session_var_maxbackup);
            // oturuma ait dil ismi
            case 15: return SessionManager::get(SessionManager::$session_var_language_name);
            // oturuma ait dil ismi kısaltması
            case 16: return SessionManager::get(SessionManager::$session_var_language_short);
            // bilinmeyen
            default: return false;
        }
    }

    // oturum sonlandırıcı
    public function falseMeanCleanSession(int $force = 0) {
        // kontrol objesi oluşturma
        $checker = new CheckerUser($this->databaseConnection);

        // zorlama kapalı ise normal kontrol
        switch($force) {
            // zorlama var, direk sonlandır
            case 1:
                session_unset(); // oturumu sonlandır
                return $checker->RedirectPage(); // direk giriş sayfasına yönlendir
            default:
                // oturum başarısızsa
                if(!$checker->SessionCheck()) {
                    session_unset(); // oturumu temizle
                    return $checker->RedirectPage(); // direk giriş sayfasına yönlendir
                }
                break;
        }

        // oturum var sıkıntı yok
        return true;
    }

    // istenilen tabloya ait tüm verileri getirme
    public function getAllData($table) {
        // sorguyu oluşturma ve hazırlama
        $sqlGetAllData = "SELECT * FROM ".$table;
        $stmtGetAllData = $this->databaseConnection->prepare($sqlGetAllData);

        // sonuçların bulunduğu dizi
        $array_data_language = [];

        switch($table) {
            // geçerli tablolar girilmşse sorugu döndür
            case ConfigTable::$table_a:
            case ConfigTable::$table_b:
            case ConfigTable::$table_c:
            case ConfigTable::$table_d:
            case ConfigTable::$table_e:
            case ConfigTable::$table_f:
                // sorguyu çalıştır
                $stmtGetAllData->execute();

                // sorgu sonucunu al
                $resultGetAllData = $stmtGetAllData->get_result();

                // sonucu çek ve diziye aktar
                while($rowResultGetAllData = $resultGetAllData->fetch_assoc()) {
                    $array_data_language[] = $rowResultGetAllData;
                }
                break;
        }

        // boş veya dolu farketmeksizin dil bilgilerinin olduğu diziyi döndür
        return $array_data_language;
    }

    // istenilen tabloya ait tüm verileri tekilleştirip getirme
    public function getDistinctData($table, $column) {
        // sorguyu oluşturma ve hazırlama
        $sqlGetDistinctData = "SELECT DISTINCT(". $column .") FROM ".$table;
        $stmtGetDistinctData = $this->databaseConnection->prepare($sqlGetDistinctData);

        // sonuçların bulunduğu dizi
        $array_data_distinct = [];

        switch($table) {
            // geçerli tablolar girilmşse sorugu döndür
            case ConfigTable::$table_a:
            case ConfigTable::$table_b:
            case ConfigTable::$table_c:
            case ConfigTable::$table_d:
            case ConfigTable::$table_e:
            case ConfigTable::$table_f:
                // argüman kontrol değişkeni
                $argumentcheck = false;

                // tablo dizinleri erişimi
                // sütun belirtilen tabloda yoksa hata dönsün
                foreach(ConfigStaticTable::$ARRAY_TABLE as $indexTable => $StrTable) {
                    if($StrTable == $table) {
                        // sütunu kontrol
                        foreach(ConfigStaticTable::$ARRAY_COLUMN_ALL[$indexTable] as $indexColumn => $strColumn) {
                            if($strColumn == $column) {
                                // değerler uyuştu döngüyü kır
                                $argumentcheck = true;
                                break;
                            }
                        }
                        // döngüyü bitir
                        break;
                    }
                }

                // tablolar değerleri uyuşmadı
                if($argumentcheck !== true) {
                    // boş veya dolu farketmeksizin bilgilerinin olduğu diziyi döndür
                    return $array_data_distinct;
                }

                // sorguyu çalıştır
                $stmtGetDistinctData->execute();

                // sorgu sonucunu al
                $resultGetDistinctData = $stmtGetDistinctData->get_result();

                // sonucu çek ve diziye aktar
                while($rowResultGetDistinctData = $resultGetDistinctData->fetch_assoc()) {
                    $array_data_distinct[] = $rowResultGetDistinctData;
                }
                break;
        }

        // boş veya dolu farketmeksizin bilgilerinin olduğu diziyi döndür
        return $array_data_distinct;
    }

    // sisteme ait verileri getirme
    public function getSysData(int $id = 0, int $type = 0) {
        switch($id) {
            // system logo icon
            case AutoImportGlobal::$var_sysdata_logoicon:
                $systemLogoIcon = $this->autosystem->getSelected(
                    [ConfigTable::$column_table_d_1],
                    [ConfigTable::$enum_table_d_1_0],
                    1
                  )[0];

                // türüne göre döndürsün
                switch($type) {
                    // tek dosya
                    case 0:
                        return $systemLogoIcon[ConfigTable::$column_table_d_4];
                    // dizi halinde
                    case 1:
                        return $systemLogoIcon;
                }

                // case 0: bilinmeyen tür ise, otomatik dizi dönsün
                return $systemLogoIcon;
            // system logo
            case AutoImportGlobal::$var_sysdata_logoimage:
                $systemLogoImage = $this->autosystem->getSelected(
                    [ConfigTable::$column_table_d_1],
                    [ConfigTable::$enum_table_d_1_1],
                    1
                  )[0];
                
                // türüne göre döndürsün
                switch($type) {
                    // tek dosya
                    case 0:
                        return $systemLogoImage[ConfigTable::$column_table_d_4];
                    // dizi halinde
                    case 1:
                        return $systemLogoImage;
                }

                // case 1: bilinmeyen tür ise, otomatik dizi dönsün
                return $systemLogoImage;
            // sign background
            case AutoImportGlobal::$var_sysdata_signbackground:
                // arkaplan için sorgula
                $systemSignBackground = $this->autosystem->getSelected(
                    [ConfigTable::$column_table_d_1],
                    [ConfigTable::$enum_table_d_1_2],
                    1
                  );

                // arkaplan için dizinin uzunluğnu hesaplama
                $lengthSignBackground = count($systemSignBackground);
                // arkaplan için rastgele bir sayı seçme
                $randomSignBackground = rand(0, $lengthSignBackground -1);
                // rastgele sayı ile arkaplanın indexini seçme
                $indexRandomSignBackground = $systemSignBackground[$randomSignBackground];
                // rastgele seçilmiş index'e ait bilgileri diziye aktarma
                $arraySelectedSignBackground = [
                  $indexRandomSignBackground[ConfigTable::$column_table_d_0], // id
                  $indexRandomSignBackground[ConfigTable::$column_table_d_1], // pid
                  $indexRandomSignBackground[ConfigTable::$column_table_d_2], // short name
                  $indexRandomSignBackground[ConfigTable::$column_table_d_3], // long name
                  $indexRandomSignBackground[ConfigTable::$column_table_d_4] // url
                ];

                // türüne göre döndürsün
                switch($type) {
                    // tek dosya
                    case 0:
                        return $arraySelectedSignBackground[4];
                    // dizi halinde
                    case 1:
                        return $arraySelectedSignBackground;
                }

                // sonucu döndür
                return $arraySelectedSignBackground;
            // arkaplan
            case AutoImportGlobal::$var_sysdata_background:
                // arkaplan için sorgula
                $systemBackground = $this->autosystem->getSelected(
                    [ConfigTable::$column_table_d_1],
                    [ConfigTable::$enum_table_d_1_3],
                    1
                  );

                // arkaplan için dizinin uzunluğnu hesaplama
                $lengthBackground = count($systemBackground);
                // arkaplan için rastgele bir sayı seçme
                $randomBackground = rand(0, $lengthBackground -1);
                // rastgele sayı ile arkaplanın indexini seçme
                $indexRandomBackground = $systemBackground[$randomBackground];
                // rastgele seçilmiş index'e ait bilgileri diziye aktarma
                $arraySelectedBackground = [
                  $indexRandomBackground[ConfigTable::$column_table_d_0], // id
                  $indexRandomBackground[ConfigTable::$column_table_d_1], // pid
                  $indexRandomBackground[ConfigTable::$column_table_d_2], // short name
                  $indexRandomBackground[ConfigTable::$column_table_d_3], // long name
                  $indexRandomBackground[ConfigTable::$column_table_d_4] // url
                ];

                // türüne göre döndürsün
                switch($type) {
                    // tek dosya
                    case 0:
                        return $arraySelectedBackground[4];
                    // dizi halinde
                    case 1:
                        return $arraySelectedBackground;
                }

                // sonucu döndür
                return $arraySelectedBackground;
            // custom art design
            case AutoImportGlobal::$var_sysdata_custom_art_design:
                $systemCustomArtDesign = $this->autosystem->getSelected(
                    [ConfigTable::$column_table_d_1],
                    [ConfigTable::$enum_table_d_1_4],
                    1
                );

                // arkaplan için dizinin uzunluğnu hesaplama
                $lengthCustomArtDesign = count($systemCustomArtDesign);
                // arkaplan için rastgele bir sayı seçme
                $randomCustomArtDesign = rand(0, $lengthCustomArtDesign -1);
                // rastgele sayı ile arkaplanın indexini seçme
                $indexRandomCustomArtDesign = $systemCustomArtDesign[$randomCustomArtDesign];
                // rastgele seçilmiş index'e ait bilgileri diziye aktarma
                $arraySelectedCustomArtDesign = [
                  $indexRandomCustomArtDesign[ConfigTable::$column_table_d_0], // id
                  $indexRandomCustomArtDesign[ConfigTable::$column_table_d_1], // pid
                  $indexRandomCustomArtDesign[ConfigTable::$column_table_d_2], // short name
                  $indexRandomCustomArtDesign[ConfigTable::$column_table_d_3], // long name
                  $indexRandomCustomArtDesign[ConfigTable::$column_table_d_4] // url
                ];

                // türüne göre döndürsün
                switch($type) {
                    // tek dosya
                    case 0:
                        return $arraySelectedCustomArtDesign[4];
                    // dizi halinde
                    case 1:
                        return $arraySelectedCustomArtDesign;
                }

                // sonucu döndür
                return $arraySelectedCustomArtDesign;
                break;
            // bilinmeyen
            default:
                // boş dizi dönsün
                return [];
        }
    }

    // kullanıcının seçtiği dile göre veri getirme
    public static function getLangData(int $datanum) {
        // istenen değişkeni dil numarasına göre getir
        switch($datanum) {
            case LanguageSupport::$lang_key__homepage: return LanguageSupport::getlang(LanguageSupport::$lang_key__homepage, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__codes: return LanguageSupport::getlang(LanguageSupport::$lang_key__codes, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__users: return LanguageSupport::getlang(LanguageSupport::$lang_key__users, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__account_settings: return LanguageSupport::getlang(LanguageSupport::$lang_key__account_settings, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__data_add: return LanguageSupport::getlang(LanguageSupport::$lang_key__data_add, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__logout: return LanguageSupport::getlang(LanguageSupport::$lang_key__logout, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__login: return LanguageSupport::getlang(LanguageSupport::$lang_key__login, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__signup: return LanguageSupport::getlang(LanguageSupport::$lang_key__signup, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__to_continue_website: return LanguageSupport::getlang(LanguageSupport::$lang_key__to_continue_website, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__dont_have_acc_website: return LanguageSupport::getlang(LanguageSupport::$lang_key__dont_have_acc_website, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__have_acc_website: return LanguageSupport::getlang(LanguageSupport::$lang_key__have_acc_website, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__create_new_project: return LanguageSupport::getlang(LanguageSupport::$lang_key__create_new_project, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__create_folder: return LanguageSupport::getlang(LanguageSupport::$lang_key__create_folder, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__add_file: return LanguageSupport::getlang(LanguageSupport::$lang_key__add_file, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__delete: return LanguageSupport::getlang(LanguageSupport::$lang_key__delete, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__recover_backup: return LanguageSupport::getlang(LanguageSupport::$lang_key__recover_backup, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__new_backup: return LanguageSupport::getlang(LanguageSupport::$lang_key__new_backup, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__max_filesize: return LanguageSupport::getlang(LanguageSupport::$lang_key__max_filesize, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__error: return LanguageSupport::getlang(LanguageSupport::$lang_key__error, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__success: return LanguageSupport::getlang(LanguageSupport::$lang_key__success, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__warning: return LanguageSupport::getlang(LanguageSupport::$lang_key__warning, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__information: return LanguageSupport::getlang(LanguageSupport::$lang_key__information, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__title_error_no_logged_in: return LanguageSupport::getlang(LanguageSupport::$lang_key__title_error_no_logged_in, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__msg_error_no_logged_in: return LanguageSupport::getlang(LanguageSupport::$lang_key__msg_error_no_logged_in, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__form_send: return LanguageSupport::getlang(LanguageSupport::$lang_key__form_send, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__form_clear: return LanguageSupport::getlang(LanguageSupport::$lang_key__form_clear, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_project_create_name: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_project_create_name, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_project_create_description: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_project_create_description, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__title_success_created_project: return LanguageSupport::getlang(LanguageSupport::$lang_key__title_success_created_project, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__msg_success_created_project: return LanguageSupport::getlang(LanguageSupport::$lang_key__msg_success_created_project, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__title_error__not_created_project: return LanguageSupport::getlang(LanguageSupport::$lang_key__title_error__not_created_project, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__msg_error__not_created_project: return LanguageSupport::getlang(LanguageSupport::$lang_key__msg_error__not_created_project, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__title_warning_create_project: return LanguageSupport::getlang(LanguageSupport::$lang_key__title_warning_create_project, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__msg_warning_create_project: return LanguageSupport::getlang(LanguageSupport::$lang_key__msg_warning_create_project, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_username: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_username, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_firstname: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_firstname, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_lastname: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_lastname, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_email: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_email, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_rank: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_rank, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_language_name: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_language_name, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_language_short: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_language_short, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_id: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_id, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_password: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_password, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_password_show: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_password_show, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_password_hide: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_password_hide, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_password_lock: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_password_lock, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_password_unlock: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_password_unlock, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_customization_mod: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_customization_mod, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_email_already_use: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_email_already_use, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_value_already_use: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_value_already_use, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_invalid_value: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_invalid_value, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_email_updated_successfully: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_email_updated_successfully, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_not_problem_found_on_value: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_not_problem_found_on_value, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_firstname_updated_successfully: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_firstname_updated_successfully, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_lastname_updated_successfully: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_lastname_updated_successfully, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_language_updated_successfully: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_language_updated_successfully, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_customization_mod_updated_successfully: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_customization_mod_updated_successfully, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_password_updated_successfully: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_password_updated_successfully, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_entered_value_not_usuable: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_entered_value_not_usuable, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_same_stored_data: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_same_stored_data, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_must_be_different: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_must_be_different, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_language_already_use: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_language_already_use, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_do_not_try_modify_data: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_do_not_try_modify_data, SessionManager::get(SessionManager::$session_var_language));
            case LanguageSupport::$lang_key__text_system_security_alert: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_system_security_alert, SessionManager::get(SessionManager::$session_var_language));            
            case LanguageSupport::$lang_key__text_search: return LanguageSupport::getlang(LanguageSupport::$lang_key__text_search, SessionManager::get(SessionManager::$session_var_language));            
            default: return "n/a";
        }
    }

    // tekli veya çoklu sayaç sorgusu
    public function getCount(
        string $table = null, string $column = null,
        array $querycolumn_array = [], array $querydata_array = [])
        {
        // oturuma ait id yok
        if(AutoImportGlobal::getSession(AutoImportGlobal::$var_session_id) < 1) {
            return -1;
        }

        // veya tablo ya da sütun girilmediğinden
        // -1 döndürsün ki hatalı olduğu anlaşılsın 
        if($table == null || $column == null) {
            return -1;
        }

        // sorgu sütunu veya değeri girilmediğinden
        // 0 döndür
        if($querycolumn_array == null || $querydata_array == null) {
            return false;
        }

        // eğer bir dizi diğerinden fazlaysa false dönsün
        if(count($querycolumn_array) != count($querydata_array)) {
            return false;
        }

        // Tablo, sütun kontrolü için
        ConfigStaticTable::initialize();

        // türüne göre tekli ya da çoklu sorgu
        // 1 = tekli (single)
        // 2 = çoklu (multi)
        $checkType = (count($querycolumn_array) > 1 && count($querydata_array) > 1);

        // tekli (single) = 0
        // çoklu (multi) = 1
        switch($checkType) {
            case 0: // tekli sorgu
                return $this->toolkit_user->countsinglequery($table, $column, $querycolumn_array[0], $querydata_array[0]);
            case 1: // çoklu sorgu
                return $this->toolkit_user->countmultiquery($table, $column, $querycolumn_array, $querydata_array);
            default: // bilinmeyen
                return -99;
        }
    }

    // kullanıcı verisini güncelleme
    public function UpdateUserData($updateType, string $value, $userid) {
        // kullanıcı id'si yoksa boş dönsün
        if($userid == null || $userid < 1)
            return -99;

        // güncellenecek veri türü
        switch($updateType) {
            // password
            case AutoImportGlobal::$var_update_password:
                // girilen değeri şifreleme
                $value = password_hash($value, PASSWORD_DEFAULT);

                $updateType = ConfigTable::$column_table_a_2;
                break;
            // email
            case AutoImportGlobal::$var_update_email:
                $updateType = ConfigTable::$column_table_a_3;
                break;
            // firstname
            case AutoImportGlobal::$var_update_firstname:
                $updateType = ConfigTable::$column_table_a_4;
                break;
            // lastname
            case AutoImportGlobal::$var_update_lastname:
                $updateType = ConfigTable::$column_table_a_5;
                break;
            // language
            case AutoImportGlobal::$var_update_language:
                $updateType = ConfigTable::$column_table_a_8;
                break;
            // customization mod
            case AutoImportGlobal::$var_update_customization_mod:
                $updateType = ConfigTable::$column_table_a_9;
                break;
            // bilinmeyen
            default:
                return 0;
        }

        // sorgu işlemlerini yapma
        $sqlUpdateUserData = "UPDATE " .ConfigTable::$table_a
        ." SET " .$updateType ." = ?"
        ." WHERE " .ConfigTable::$column_table_a_0 ." = ?";

        // soruguyu hazırlama ve parametreleri girme
        $stmtUpdateUserData = $this->databaseConnection->prepare($sqlUpdateUserData);
        $stmtUpdateUserData->bind_param("si", $value, $userid);

        // sorgu doğru çalışmışsa oturuma ait veriyi güncelle
        if($stmtUpdateUserData->execute()) {
            // güncellenecek veri türü
            switch($updateType) {
                // session password update
                case AutoImportGlobal::$var_update_password:
                return SessionManager::set(SessionManager::$var_session_password, $value);
                // session email update
                case AutoImportGlobal::$var_update_email:
                return SessionManager::set(SessionManager::$var_session_email, $value);
                // session firstname update
                case AutoImportGlobal::$var_update_firstname:
                return SessionManager::set(SessionManager::$var_session_firstname, $value);
                // session lastname update
                case AutoImportGlobal::$var_update_lastname:
                return SessionManager::set(SessionManager::$var_session_lastname, $value);
                // session language update
                case AutoImportGlobal::$var_update_language:
                return SessionManager::set(SessionManager::$var_session_password, $value);
                // session customization mod update
                case AutoImportGlobal::$var_update_customization_mod:
                return SessionManager::set(SessionManager::$var_session_customization_mod, $value);
                // ?
                default:
                return -80;
            }
        }

        // oturum verisi güncellenemedi
        return -90;
    }

    // sayfa yönlendirme
    public function RedirectSelected(int $num) {
        // geçici yeni obje oluşturma
        $this->checkeruser->SessionCheck();

        switch($num) {
            // homepage
            case AutoImportGlobal::$var_redirect_homepage: return header("Location: ".CheckerUser::$controlHomepage);
            // codes
            case AutoImportGlobal::$var_redirect_codes: return header("Location: ".CheckerUser::$controlCodes);
            // users
            case AutoImportGlobal::$var_redirect_users: return header("Location: ".CheckerUser::$controlUsers);
            // account settings
            case AutoImportGlobal::$var_redirect_account_settings: return header("Location: ".CheckerUser::$controlAccountSettings);
            // data add
            case AutoImportGlobal::$var_redirect_data_add: return header("Location: ".CheckerUser::$controlDataAdd);
            // login
            case AutoImportGlobal::$var_redirect_login: return header("Location: ".CheckerUser::$controlLogin);
            // logout
            case AutoImportGlobal::$var_redirect_logout: return AutoImportGlobal::falseMeanCleanSession(1);
            // register
            case AutoImportGlobal::$var_redirect_register: return header("Location: ".CheckerUser::$controlRegister);
            // logout control
            case AutoImportGlobal::$var_redirect_logout_control: return AutoImportGlobal::falseMeanCleanSession(0);
            // refresh page
            case AutoImportGlobal::$var_redirect_refresh: echo "<script nonce>window.location.href='';</script>";
            return true;
        }
    }

    // veriyi şifreleme
    public static function HashData($data, int $type = 0) {
        switch($type) {
            // otomatik tanımlı şifreleme türü
            case 0:
            default:
                return password_hash($data, PASSWORD_DEFAULT);
        }
    }

    // veriyi kontrol etme
    public static function VerifyPassword($value, $hashed) {
        if($value == null || $hashed == null)
            return -1;

        return password_verify($value, $hashed);
    }

    // istenileni getirme
    public function getSelected($array_querycolumn = [], $array_value = [], string $table) {
        // tablo kontrolü
        $TEMP_CONTROL = false;
        $TEMP_QUERY_COL_COUNT = 0;

        foreach(ConfigStaticTable::$ARRAY_TABLE as $indexTable => $TableCheck) {
            if($table === $TableCheck) {
                // tablo bulundu
                $TEMP_CONTROL = true;

                foreach($array_querycolumn as $indexqarray => $QArray) {
                    // sütun sütun kontrol etme
                    foreach(ConfigStaticTable::$ARRAY_COLUMN_ALL[$indexTable] as $indexColumn => $ColumnCheck) {
                        if($QArray == $ColumnCheck) {
                            $TEMP_QUERY_COL_COUNT += 1;
                            break;
                        }
                    }
                }
            }
        }

        // tablo bulunmamışsa
        if($TEMP_CONTROL != true) {
            return -99;
        }

        // girilen sütunların hepsi doğru değilse
        if($TEMP_QUERY_COL_COUNT != count($array_querycolumn)) {
            return -98;
        }

        // herhangi bir sıkıntı yoksa devam etsin
        // sorgunun sonuçlarınının depolanacağı dizi
        $STORE_RESULT_ARRAY = [];

        // Sorgu oluşturma
        $sqlGetSelected = "SELECT * FROM " . $table
        ." WHERE 1 > 0 ";

        // sorgu parametrelerini tutacak olan değişken
        $queryParam = "";
        
        // döngü ile sorgu yapacağımız sütunları eklemek
        foreach($array_querycolumn as $indexqarray => $QArray) {
            $sqlGetSelected = $sqlGetSelected . " AND " .$QArray ." = ?"; // sorgu kontrolcüsü ekleme
            $queryParam = $queryParam . "s"; // parametre ekleme
        }

        // sorgu bağlantısı sağlama
        $stmtGetSelected = $this->databaseConnection->prepare($sqlGetSelected);

        // sorgu parametrelerini ve değerlerini girme
        $stmtGetSelected->bind_param("$queryParam", ...$array_value);

        // sorguyu çalıştırma ve sonrasında sonucunu alma
        $stmtGetSelected->execute();
        $resultGetSelected = $stmtGetSelected->get_result();

        // verileri fetch edip diziye aktarma
        while($fetchGetSelected = $resultGetSelected->fetch_assoc()) {
            $STORE_RESULT_ARRAY[] = $fetchGetSelected;
        }

        // verilerin aktarıldığı diziyi döndürme
        return $STORE_RESULT_ARRAY;
    }
}

// Tüm İşlemleri Yapacak Olan Sınıf ve Fonksiyonları
$AutoImportGlobal = new AutoImportGlobal($databaseconn);
$Account = new Account($databaseconn);
?>