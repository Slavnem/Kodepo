<?php
// defines
define("MIN_LENGTH", 2);
define("FIRSTNAME_LENGTH", 36);
define("LASTNAME_LENGTH", 36);
define("USERNAME_LENGTH", 36);

// dosyaları içe aktarma
require_once("include/class/constant/class-constant.php");
require_once("include/database/config-database-table.php");

class Account {
  // gizli değişkenler
  private $databaseConnection;
  private $errorArray = array();

  // değişkenler
  public static $validate_username = 0;
  public static $validate_firstname = 1;
  public static $validate_lastname = 2;
  public static $validate_email = 3;
  public static $validate_rank = 4;
  public static $validate_language = 5;
  public static $validate_customization_mod = 6;
  public static $validate_password = 7;

  // veritabanı yapı
  public function __construct($databaseConnection) {
    // veritabanı bağlantısı
    $this->databaseConnection = $databaseConnection;
  }

  // kayıt fonksiyonu
  public function register (
    string $username,
    string $password, string $passwordConfirm,
    string $email, string $emailConfirm,
    string $firstname, string $lastname,
  )
  {
    // değerleri kontrol ettirme
    $this->validateUserName($username);
    $this->validatePasswords($password, $passwordConfirm);
    $this->validateEmails($email, $emailConfirm);
    $this->validateFirstName($firstname);
    $this->validateLastName($lastname);

    // hata dizisi boş ise veriyi başarıyla döndür
    if(empty($this->errorArray)) {
        return $this->insertUserDetails(
          $username, $password, $email, $firstname, $lastname
        );
    }

    // istenen olmamış, hata döndür
    return false;
  }

  // giriş fonksiyonu
  public function login(string $username, string $password) {
    // kullanıcı adı ve şifreyi getiren sorgu
    $sqlCheckUser =
    "SELECT ". ConfigTable::$column_table_a_1
    .", ". ConfigTable::$column_table_a_2
    ." FROM ". ConfigTable::$table_a
    ." WHERE ". ConfigTable::$column_table_a_1 ." = ?";

    // sorgu bağlantısı
    $stmt = $this->databaseConnection->prepare($sqlCheckUser);

    // parametreleri girmek
    $stmt->bind_param("s", $username);

    // sorguyu çalıştır
    $stmt->execute();

    // sonucu al
    $resultCheckUser = $stmt->get_result();

    // bulunan satır sayısı
    $numResultCheck = $resultCheckUser->num_rows;

    // eğer satırlar bulunmuşsa
    if($numResultCheck) {
      // sorguda veriyi çek
      while($rowResultCheck = $resultCheckUser->fetch_assoc()) {
        // veri
        $fetchPassword = $rowResultCheck[ConfigTable::$column_table_a_2];

        // şifre doğrulama
        $verifyPassword = password_verify($password, $fetchPassword);

        // şifre kontrolü sonucu başarılı ise
        if($verifyPassword)
          return true;
      }
    }

    // kontrol başarısız, hatayı diziye atasın ve boş dönsün
    array_push($this->errorArray, Constant::$loginFailed);
    return false;
  }

  // giriş fonksiyonu ama şifre hashlenmiş
  public function hash_login(string $username, string $hash_password) {
    // kullanıcı adı ve şifreyi getiren sorgu
    $sqlCheckUser =
    "SELECT ". ConfigTable::$column_table_a_1
    .", ". ConfigTable::$column_table_a_2
    ." FROM ". ConfigTable::$table_a
    ." WHERE ". ConfigTable::$column_table_a_1 ." = ?"
    ." AND " .ConfigTable::$column_table_a_2 ." = ?";

    // sorgu bağlantısı
    $stmt = $this->databaseConnection->prepare($sqlCheckUser);

    // parametreleri girmek
    $stmt->bind_param("ss", $username, $hash_password);

    // sorguyu çalıştır
    $stmt->execute();

    // sonucu al
    $resultCheckUser = $stmt->get_result();

    // bulunan satır sayısı
    $numResultCheck = $resultCheckUser->num_rows;

    // eğer satırlar bulunmuşsa
    if($numResultCheck) {
      // sorguda veriyi çek
      while($rowResultCheck = $resultCheckUser->fetch_assoc()) {
        // veri
        $fetchPassword = $rowResultCheck[ConfigTable::$column_table_a_2];

        // şifre doğrulama
        $verifyPassword = $hash_password == $fetchPassword ? (true) : (false);

        // şifre kontrolü sonucu başarılı ise
        if($verifyPassword)
          return true;
      }
    }

    // kontrol başarısız, hatayı diziye atasın ve boş dönsün
    array_push($this->errorArray, Constant::$loginFailed);
    return false;
  }

  // değer kontrolcü fonksiyon
  public function validateSelected(int $validateType, $value) {
    switch($validateType) {
      // kullanıcı isim
      case Account::$validate_username:
        return $this->validateUserName((string)$value);
      // isim
      case Account::$validate_firstname:
        return $this->validateFirstName((string)$value);
      // soyisim
      case Account::$validate_lastname:
        return $this->validateLastName((string)$value);
      // e-posta
      case Account::$validate_email:
        // eğer bir dizi ise
        if(is_array($value)) {
          // 2 değerden fazla ya da az ise hata kodu dönsün
          if(count($value) != 2) {
            return -2;
          }

          // 2 değer girilmiş, değerleri kontrol et
          return $this->validateEmails((string)$value[0], (string)$value[1]);
        }
        // sadece tek değer
        else {
          return $this->validateEmails((string)$value, (string)$value);
        }
      // şifre
      case Account::$validate_password:
        // eğer bir dizi ise
        if(is_array($value)) {
          // 2 değerden fazla ya da az ise hata kodu dönsün
          if(count($value) != 2) {
            return -2;
          }

          // 2 değer girilmiş, değerleri kontrol et
          return $this->validatePasswords((string)$value[0], (string)$value[1]);
        }
        // sadece tek değer
        else {
          return $this->validatePasswords((string)$value, (string)$value);
        }
    }
  }

  // kullanıcı bilgilerini veritabanına kaydetmek
  private function insertUserDetails(
    string $username,
    string $password,
    string $email,
    string $firstname,
    string $lastname
    )
    {
        // şifreleme
        $password = password_hash($password, PASSWORD_DEFAULT);

        // kullanıcı adı, şifre, email, ad, soyad verilerini
        // veritabanına kaydedicek sorgu
        $sqlInsert =
        "INSERT INTO ". ConfigTable::$table_a
        ." ( "
          .ConfigTable::$column_table_a_1." , "
          .ConfigTable::$column_table_a_2." , "
          .ConfigTable::$column_table_a_3." , "
          .ConfigTable::$column_table_a_4." , "
          .ConfigTable::$column_table_a_5
        ." ) "
        ." VALUES (?,?,?,?,?)";

        // sorgu bağlantısı
        $stmt = $this->databaseConnection->prepare($sqlInsert);

        // sorgu parametreleri
        $stmt->bind_param("sssss", $username, $password, $email, $firstname, $lastname);

        // sorgu çalıştır ve döndür
        return $stmt->execute();
    }

    // ad doğrulama
    private function validateFirstName(string $firstname) {
        if(strlen($firstname) < 2 || strlen($firstname) > FIRSTNAME_LENGTH) {
            array_push($this->errorArray, Constant::$firstNameCharacters);
            return false;
        }

        return true;
    }

    // soyad doğrulama
    private function validateLastName(string $lastname) {
        if(strlen($lastname) < 2 || strlen($lastname) > LASTNAME_LENGTH) {
            array_push($this->errorArray, Constant::$lastNameCharacters);
            return false;
        }

        return true;
    }

    // kullanıcı adı doğrulama
    private function validateUserName(string $username) {
        if(strlen($username) < 2 || strlen($username) > USERNAME_LENGTH) {
            array_push($this->errorArray, Constant::$usernameCharacters);
            return false;
        }

        // kullanıcının girdiği ile veritabanındaki kullanıcı adını karşılaştırma
        $sqlUsername =
        "SELECT ". ConfigTable::$column_table_a_1
        ." FROM ". ConfigTable::$table_a
        ." WHERE ". ConfigTable::$column_table_a_1 ." = ?";

        // sorgu bağlantısı
        $stmt = $this->databaseConnection->prepare($sqlUsername);

        // sorgu parametreleri
        $stmt->bind_param("s", $username);

        // sorgu çalıştır
        $stmt->execute();

        // sonucu al
        $resultUsername = $stmt->get_result();

        // bulunan satır sayısı
        $numResultUsername = $resultUsername->num_rows;

        // veri satırı 0'a eşit değilse, bu veri daha önce oluşturulmuş demektir
        if($numResultUsername) {
            // kullanıcı adı alınmış
            array_push($this->errorArray, Constant::$usernameTaken);
            return false;
        }

        // eğer bulunmadıysa başarılı
        return true;
    }

    // email doğrulama
    private function validateEmails(string $email, string $emailConfirm) {
        if($email != $emailConfirm) {
            array_push($this->errorArray, Constant::$emailsDontMatch);
            return false;
        }

        if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            array_push($this->errorArray, Constant::$emailInvalid);
            return false;
        }

        // kullanıcının girdiği email ile veritabanındaki email'i karşılaştırmak
        $sqlEmail =
        "SELECT ". ConfigTable::$column_table_a_3
        ." FROM ". ConfigTable::$table_a
        ." WHERE ". ConfigTable::$column_table_a_3 ." = ?";

        // sorgu bağlantısı
        $stmt = $this->databaseConnection->prepare($sqlEmail);

        // sorgu parametreleri
        $stmt->bind_param("s", $email);

        // sorgu çalıştır
        $stmt->execute();

        // sonucu al
        $resultEmail = $stmt->get_result();

        // bulunan satır sayısı
        $numResultEmail = $resultEmail->num_rows;

        // veri satırı 0'a eşit değilse, bu veri daha önce oluşturulmuş demektir
        if($numResultEmail != 0) {
            // email adı alınmış
            // email taken
            array_push($this->errorArray, Constant::$emailTaken);
            return false;
        }

        // eğer bulunmadıysa başarılı
        return true;
    }

    // şifre doğrulama
    private function validatePasswords(string $password, string $passwordConfirm) {
      // şifreler uyuşmuyor ise hata bildir ve boş dön
      if($password != $passwordConfirm) {
          array_push($this->errorArray, Constant::$passwordsDontMatch);
          return false;
      }

      // boyutu küçük veya fazla ise...
      if(strlen($password) < 2 || strlen($password) > USERNAME_LENGTH) {
          array_push($this->errorArray, Constant::$passwordLength);
          return false;
      }

      return true;
    }

    // hata getirme
    public function getError($error) {
        if(in_array($error, $this->errorArray)) {
            return "<span id='error-msg' class='error-msg'>$error</span>";
        }
    }
}
?>
