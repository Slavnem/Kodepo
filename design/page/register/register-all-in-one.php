<?php
// oturum bilgileri
require_once("include/database/config-session.php");
// dosyaları içe aktar
require_once("include/class/account/class-account.php");
require_once("include/class/constant/class-constant.php");
require_once("include/class/sanitizer/class-sanitizer-form.php");
require_once("include/class/toolkit/class-toolkit-account.php");
// svg resimleri tutan dosya
require_once("data/static/static-svg.php");
// dil desteği
require_once("include/support/config-language.php");
// sisteme otomatik tanımlanmış özellikler
require_once("data/static/static-system.php");
// sayfalar
require_once("data/static/static-page.php");

// Login İşlemlerini Yapan Sınıf
class RegisterAllInOne {
    // veritabanı değişken
    protected $databaseConnection;
    // otomatik sistem sorgu objesi
    protected $autosystem;
    // hesap işlem sorgu objesi
    protected $account;
    // hesap işlem araçları objesi
    protected $toolkit_account;

    // sayfa yönlendirmeleri
    protected $redirectIndexPage =  PAGE_HOMEPAGE;
    protected $redirectChangePage = PAGE_LOGIN;

    // fonksiyon yapı
    public function __construct($databaseConnection) {
        // veritabanı bağlantısı
        $this->databaseConnection = $databaseConnection;
        $this->autosystem = new AutoSystem($this->databaseConnection);
        $this->account= new Account($this->databaseConnection);
        $this->toolkit_account = new ToolkitAccount($this->databaseConnection);
    }

    // hesap giriş kontrolü
    public function RegisterCheck(
        string $dataUsername,
        string $dataPassword, string $dataPasswordVerify,
        string $dataEmail, string $dataEmailVerify,
        string $dataName, string $dataSurname)
        {
        if(empty($dataUsername) || empty($dataPassword)) {
            return false;
        }

        // kayıt işlemi
        $status = $this->account->register(
            $dataUsername,
            $dataPassword, $dataPasswordVerify,
            $dataEmail, $dataEmailVerify,
            $dataName, $dataSurname
          );

        // giriş işlemi
        $status = $this->account->login($dataUsername, $dataPassword);
        
        // giriş işlemi başarılı ise
        if($status) {
          // kullanıcı id'sini al
          $session_id = $this->toolkit_account->getSessionID($dataUsername);
        
          // oturum bilgisini güncelle
          $save_session = new SessionManager($this->databaseConnection);
          $save_session->save($session_id);
        
          // sayfa adresine yönlendirme
          return header("Location: $this->redirectIndexPage");
        }
    }

    // logoları getirtme işlemi
    public function getSystemImage(int $id = 0) {
        // otomatik olarak icon getirsin
        switch($id) {
            case 0: // logo icon
                // logo icon için sorgula
                $systemLogoIcon = $this->autosystem->getSelected(
                    [ConfigTable::$column_table_d_1],
                    [ConfigTable::$enum_table_d_1_0],
                    1
                )[0];

                // sonucu döndür
                return $systemLogoIcon[ConfigTable::$column_table_d_4];
            case 1: // logo image
                $systemLogoImage = $this->autosystem->getSelected(
                    [ConfigTable::$column_table_d_1],
                    [ConfigTable::$enum_table_d_1_1],
                    1
                  )[0];

                // sonucu döndür
                return $systemLogoImage[ConfigTable::$column_table_d_4];
            case 2: // sign background
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
                $indexRandmoSignBackground = $systemSignBackground[$randomSignBackground];
                // rastgele seçilmiş index'e ait bilgileri diziye aktarma
                $arraySelectedSignBackground = [
                  $indexRandmoSignBackground[ConfigTable::$column_table_d_0], // id
                  $indexRandmoSignBackground[ConfigTable::$column_table_d_1], // pid
                  $indexRandmoSignBackground[ConfigTable::$column_table_d_2], // short name
                  $indexRandmoSignBackground[ConfigTable::$column_table_d_3], // long name
                  $indexRandmoSignBackground[ConfigTable::$column_table_d_4] // url
                ];

                // sonucu döndür
                return $arraySelectedSignBackground[4];
            default:
                return false;
        }
    }

    // sayfa değiştirme işlemi
    public function changePage(int $num = 0) {
        switch($num) {
            // signup
            case 0:
                return $this->redirectChangePage;
            // none
            default:
                return false;
        }
    }

    // hata getirme
    public function getError(int $errornum = 0) {
        switch($errornum) {
            // login fail
            case 0:
                return $this->account->getError(Constant::$loginFailed);
            // lastname characters
            case 1:
                return $this->account->getError(Constant::$firstNameCharacters);
            // firstname characters
            case 2:
                return $this->account->getError(Constant::$lastNameCharacters);
            // username characters
            case 3:
                return $this->account->getError(Constant::$usernameCharacters);
            // username taken
            case 4:
                return $this->account->getError(Constant::$usernameTaken);
            // emails dont match
            case 5:
                return $this->account->getError(Constant::$emailsDontMatch);
            // email invalid
            case 6:
                return $this->account->getError(Constant::$emailInvalid);
            // passwords dont match
            case 7:
                return $this->account->getError(Constant::$passwordsDontMatch);
            // password length
            case 8:
                return $this->account->getError(Constant::$passwordLength);
            // none
            default:
                return false;
        }
    }
}

// yönlendirme link
$redirect_change_page = "login.php";
$redirect_index_page = "index.php";

// giriş ekranı başlık ve yazı
$text_sign_header_title = "Kayıt Ol";
$text_sign_header_paragraph = "Kodepo'ya Devam Etmek İçin...";
$text_sign_header_noaccount = "Kodepo Hesabınız var mı? Hemen Şimdi Kodepo'ya Giriş Yapın...";

// form input girişlerine ait adlar
$name_input_username = "input-username";
$name_input_password = "input-password";
$name_input_email = "input-email";
$name_input_name = "input-name";
$name_input_surname = "input-surname";
$name_input_submit = "input-submit";
$name_btn_password_toggle = "btn-password-toggle";

$name_input_verify_password = "input-verify-password";
$name_input_verify_email = "input-verify-email";

// form input girişlerine ait değerler
$value_input_submit = "Kayıt Ol";

// Tüm İşlemleri Yapacak Olan Sınıf ve Fonksiyonları
$RegisterAllInOne = new RegisterAllInOne($databaseconn);

// kayıt kontrolü yapmak
if(
    isset($_POST[$name_input_submit])
    && isset($_POST[$name_input_username])
    && isset($_POST[$name_input_password])
    && isset($_POST[$name_input_email])
    && !empty($_POST[$name_input_username])
    && !empty($_POST[$name_input_password])
    && !empty($_POST[$name_input_email])
    && !empty($_POST[$name_input_name])
    && !empty($_POST[$name_input_surname])
  )
  {
    // girilen değerler
    $data_username = FormSanitizer::sanitizeFormUsername($_POST[$name_input_username]);
    $data_password = FormSanitizer::sanitizeFormPassword($_POST[$name_input_password]);
    $data_email = FormSanitizer::sanitizeFormEmail($_POST[$name_input_email]);
    $data_name = FormSanitizer::sanitizeFormString($_POST[$name_input_name]);
    $data_surname = FormSanitizer::sanitizeFormString($_POST[$name_input_surname]);
    $data_verify_password = FormSanitizer::sanitizeFormPassword($_POST[$name_input_password]);
    $data_verify_email = FormSanitizer::sanitizeFormEmail($_POST[$name_input_email]);
  
    // giriş işlemi
    $status = $RegisterAllInOne->RegisterCheck(
      $data_username,
      $data_password, $data_verify_password,
      $data_email, $data_verify_email,
      $data_name, $data_surname
    );
  
    // giriş işlemi başarılı ise
    if($status) {
      // kullanıcı id'sini al
      $session_id = $toolkit_account->getSessionID($data_username);
  
      // oturum bilgisini güncelle
      $save_session = new SessionManager($databaseconn);
      $save_session->save($session_id);
  
      // sayfa adresine yönlendirme
      return header("Location: $redirect_index_page");
    }
  }
  
  // girilen değeri yazan fonksiyon
  function getInputValue($name) {
      if(isset($_POST[$name])) {
          echo $_POST[$name];
      }
  }
?>
<!-- TOGGLE INPUT JS -->
<script nonce>
  function togglePasswordShow()
  {
    // elementi bulsun
    const element_btn_password_toggle = document.querySelector("button[name='<?php echo $name_btn_password_toggle; ?>']");
    const element_password = document.querySelector("input[name='<?php echo $name_input_password; ?>']");
    const element_image_password = document.querySelector("#input-password-logo");

    // element tanımlı değilse boş dönsün
    if(typeof element_password === null || element_password.type ==  null)
      return false;

      // element text ise password, değilse text
      if(element_password.type == "text") {
        element_password.type = "password";

        // resimi şifreyi gör yap
      if(element_btn_password_toggle !== null)
        element_btn_password_toggle.innerHTML = `<?php echo StaticSvg::$SVG_seepassword; ?>`;

        // resimi kilid kapalı yap
        if(element_image_password !== null)
        element_image_password.innerHTML = `<?php echo StaticSvg::$SVG_passwordon; ?>`;
      }
      else {
        element_password.type = "text";

        // resimi şifreyi gizle yap
        if(element_btn_password_toggle !== null)
          element_btn_password_toggle.innerHTML = `<?php echo StaticSvg::$SVG_hidepassword; ?>`;

          // resimi kilid açık yap
        if(element_image_password !== null)
          element_image_password.innerHTML = `<?php echo StaticSvg::$SVG_passwordoff; ?>`;
    }
  }
</script>