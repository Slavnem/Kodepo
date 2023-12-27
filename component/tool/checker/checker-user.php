<?php
// veritabanı
require_once("include/database/config-database.php");
// oturum bilgileri
require_once("include/database/config-session.php");
// hesap kontrolcü dosyayı içe aktarma
require_once("include/class/account/class-account.php");
require_once("include/class/toolkit/class-toolkit-account.php");
// sayfalar
require_once("data/static/static-page.php");

// Kontrol Sınıfı
class CheckerUser {
  // veritabanı değişken
  protected $databaseConnection;
  // oturum değişkeni
  protected $session;
  // hesap işlem sorgu objesi
  protected $account;
  // hesap işlem araçları objesi
  protected $toolkit_account;

  // kontrol ana sayfalar
  public static $controlIndex = PAGE_INDEX;
  public static $controlLogin = PAGE_LOGIN;
  public static $controlLogout = PAGE_LOGOUT;
  public static $controlRegister = PAGE_REGISTER;
  public static $controlHomepage = PAGE_HOMEPAGE;
  public static $controlCodes = PAGE_CODES;
  public static $controlUsers = PAGE_USERS;
  public static $controlAccountSettings = PAGE_ACCOUNT_SETTINGS;
  public static $controlDataAdd = PAGE_DATA_ADD;

  // fonksiyon yapı
  public function __construct($databaseConnection) {
    // veritabanı bağlantısı
    $this->databaseConnection = $databaseConnection;
    $this->session = new SessionManager($databaseConnection);
    $this->account = new Account($this->databaseConnection);
    $this->toolkit_account = new ToolkitAccount($this->databaseConnection);
  }

  // dosyayı tam dosya yolu ile getir
  public static function getFileLocation() {
    return $_SERVER['SCRIPT_FILENAME'];
  }

  // duruma göre oturum kontrol
  public function SessionCheck() {
    // oturum id kontrol
    switch(isset($_SESSION[SessionManager::$session_var_id]) && $this->toolkit_account->checkID($this->session->get(SessionManager::$session_var_id))) {
      // oturum id'si tanımlı ve doğru
      case 1:
        // eğer kullanıcı adı ve hashli şifresi doğru ise giriş tam anlamıyla başarılı
        if($this->account->hash_login($this->session->get(SessionManager::$session_var_username), $this->session->get(SessionManager::$session_var_password))) {
          // oturum bilgisini güncelle
          $this->session->save($this->session->get(SessionManager::$session_var_id));
          return true; // oturum giriş başarılı
        }
        // değilse tam anlamıyla başarılı değil demek
        // oturuma ait veri yok
        // misafir girişi
        else {
          $this->session->save(0);
        }

        // oturum giriş başarısız
        return false;
      // oturuma ait veri yok
      // misafir girişi
      default:
        $this->session->save(0);
        return false; // oturum giriş başarısız
    }
  }

  // sayfa yönlendirme
  public static function RedirectPage(int $type = 0) {
    // 0 = giriş zorunlu
    // 1 = misafir
    // 2 = yönlendirme
    switch($type) {
      case 0: // kullanıcı girişi zorunlu
      default:
        // sayfaya yönlendir
        echo "<script nonce>window.location.href='/" .CheckerUser::$controlLogin. "';</script>";
        return 0;
      case 1: // kullanıcı misafir de olabilir bir şey yapma
        break;
      case 2: // otomatik yönlendirme
        echo "<script nonce>window.location.href='/" .CheckerUser::$controlHomepage. "';</script>";
        return 2;
    }
  }

  // oturum kontrol
  public function CheckerControl(CheckerUser $CheckerObject) {
    // switch case kullanarak sayfalara göre işlem yapma
    switch(CheckerUser::getFileLocation()) {
      // homepage, codes sayfalarından çağırılmış ise...
      case (strpos(CheckerUser::getFileLocation(), CheckerUser::$controlHomepage) !== false):
      case (strpos(CheckerUser::getFileLocation(), CheckerUser::$controlCodes) !== false):
        switch($CheckerObject->SessionCheck()) {
          // başarısız ise
          case 0:
            return $CheckerObject->RedirectPage(1);
        }
      break;
      // users, account settings, data add sayfalarından çağırılmış ise...
      case (strpos(CheckerUser::getFileLocation(), CheckerUser::$controlUsers) !== false):
      case (strpos(CheckerUser::getFileLocation(), CheckerUser::$controlAccountSettings) !== false):
      case (strpos(CheckerUser::getFileLocation(), CheckerUser::$controlDataAdd) !== false):
        switch($CheckerObject->SessionCheck()) {
          // başarısız ise
          case 0:
            return $CheckerObject->RedirectPage(0);
        }
      break;
    }
  }
}

// Tüm İşlemleri Yapacak Olan Sınıf ve Fonksiyonları
$CheckerUser = new CheckerUser($databaseconn);

// sayfa yüklendiği gibi kontrol etmek
$CheckerUser->CheckerControl($CheckerUser);
?>