<?php
// oturum bilgileri
require_once("include/database/config-session.php");
// dil desteği
require_once("include/support/config-language.php");
// dosya işlemleri için gerekli dosya
require_once("data/static/static-file.php");
// svg
require_once("data/static/static-svg.php");

// oturum sınıfı
$session = new SessionManager($databaseconn);

// oturum bilgisini güncelle
$session->save($session->get(SessionManager::$session_var_id));

// yazılar
$text_fileupload_import_title = "Dosya Ekle +";
$text_fileupload_upload_title = "Dosyayı Gönder";
$text_fileupload_clear_title = "Temizle";

$placeholder_input_customname = "Bla";
$placeholder_input_customcomment = "bla bla bla...";

// oturuma ait en fazla dosya yükleme boyutu
$DATA_SESSION_MAX_FILE_SIZE = $session->get(SessionManager::$session_var_maxfilesize);
$DATA_SESSION_MAX_FILE_SIZE_MB = ($DATA_SESSION_MAX_FILE_SIZE) / (1024 * 1024);
$DATA_SESSION_STORE_FILE = $session->get(SessionManager::$session_var_storefile);
$DATA_SESSION_GLOBAL_STORE_FILE = FILE_LINK_CODE_UPLOAD . $session->get(SessionManager::$session_var_id) ."/";

// veri gönderimi varsa
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST[FILE_SUBMIT])) {
  // oturuma ait rütbe yoksa boş dönsün
  if($_SESSION[SessionManager::$session_var_rank] == null || $_SESSION[SessionManager::$session_var_rank] < 0) 
    return 0;

  // Dosyaların içi boşsa 0 dönsün
  if(!isset($_FILES[FILE_IMPORT]) || empty($_FILES))
    return 0;

  // kısa adlanırma yaptık
  // biri dosyaları alıyor diğeri dosyaların adedini sayıyor
  $UPLOAD_FILES = $_FILES[FILE_IMPORT];
  $COUNT_FILES = count($UPLOAD_FILES['name']);

  $uploadsFileTmpPath = $UPLOAD_FILES['tmp_name']; // dosyalar geçici ad
  $uploadsFileName = $UPLOAD_FILES['name']; // dosyalar ad
  $uploadsFileSize = $UPLOAD_FILES['size']; // dosyalar boyut
  $uploadsFileType = $UPLOAD_FILES['type'];
  $uploadsFileExtension = array();

  foreach($uploadsFileName as $fileName) {
    $uploadsFileExtension[] = pathinfo($fileName, PATHINFO_EXTENSION);
  }

  // Projeye verilmiş ad, açıklama...
  $uploadProjectCustomName = ""; // dosyaya verilmiş özel ad
  $uploadProjectCustomComment = ""; // dosyaya verilmiş özel açıklama
  $uploadProjectCustomDate = date("Y-m-d"); // yıl-ay-gün

  // kod için özel ad tanımlı ve boş değilse
  if(isset($_POST[PROJECT_NAME]) && !empty($_POST[PROJECT_NAME])) {
    $uploadProjectCustomName = $_POST[PROJECT_NAME];
  }

  // // kod için özel açıklama tanımlı ve boş değilse
  if(isset($_POST[PROJECT_COMMENT]) && !empty($_POST[PROJECT_COMMENT])) {
    $uploadProjectCustomComment = $_POST[PROJECT_COMMENT];
  }

  // // dosya uzantısı geçersiz
  foreach($uploadsFileExtension as $index => $fileExtension) {
    if(!in_array($fileExtension, $ARRAY_FILE_TYPE_SUPPORT)) {
      echo "<script>MessageError('{$uploadsFileName[$index]}', '$fileExtension', '{$uploadsFileName[$index]} [{$uploadsFileSize[$index]} BYTE]', 0);</script>";
      $continue = false;
      break;
    }
  }

  // uznatılarda sorun yok, devam...
  if(!isset($continue) || $continue != false) {
    print_r($_SESSION);

    /* PROJE İSİM, AÇIKLAMA ALMA
    YOKSA DOSYA ADINI ALMA AÇIKLAMAYI BOŞ BIRAKMA
    PROJELER KULLANICININ İD'Sİ İLE EKLEME VE DOSYALARI DA
    KODLAR TABLOSUNA EKLEYİP PROJE ID'SİNİ YENİ OLUŞTURULAN
    PROJENİN ID'SİNDEN ALSIN VE KAYDETSİN
    */

    /*if (isset($_FILES[FILE_IMPORT]) && $_FILES[FILE_IMPORT]["error"] == UPLOAD_ERR_OK) {
    // Dosya bilgilerini alma
    /*$tempFileName = $_FILES[FILE_IMPORT]["tmp_name"];
    $uploadFileName = $_FILES[FILE_IMPORT]["name"]; // dosya adı
    $uploadFileSize = $_FILES[FILE_IMPORT]["size"]; // dosya boyutu (byte)
    $uploadFileExtension = pathinfo($uploadFileName, PATHINFO_EXTENSION); // dosya uzantısı

    $uploadFileCustomName = ""; // dosyaya verilmiş özel ad
    $uploadFileCustomComment = ""; // dosyaya verilmiş özel açıklama
    $uploadFileCustomDate = date("Y-m-d"); // yıl-ay-gün
    // ay-yıl-gün bazında depolasın
    $uploadFileCustomDateLink = $uploadFileCustomDate ."/";

    // kod için özel ad tanımlı ve boş değilse
    if(isset($_POST[PROJECT_NAME]) && !empty($_POST[PROJECT_NAME])) {
      $uploadFileCustomName = $_POST[PROJECT_NAME];
    }

    // kod için özel açıklama tanımlı ve boş değilse
    if(isset($_POST[PROJECT_COMMENT]) && !empty($_POST[PROJECT_COMMENT])) {
      $uploadFileCustomComment = $_POST[PROJECT_COMMENT];
    }

    // dosya uzantısı geçersiz
    if(!in_array($uploadFileExtension, $ARRAY_FILE_TYPE_SUPPORT)) {
      echo "<script>alert('!ERROR! $uploadFileExtension');</script>";
      return 0;
    }

    // oturuma ait rütbenin alabileceği en yüksek dosya boyutundan fazla boyuta sahip
    if($uploadFileSize > $DATA_SESSION_MAX_FILE_SIZE)
      return 0;

    // hedef klasor ve dosya
    $BasenameFile = basename($uploadFileName);

    $destionationLocalDateDir = $DATA_SESSION_STORE_FILE . $uploadFileCustomDateLink;
    $destionationLocalFile = $destionationLocalDateDir . $BasenameFile;

    $destionationGlobalDateDir = $DATA_SESSION_GLOBAL_STORE_FILE . $uploadFileCustomDateLink;
    $destionationGlobalFile = $destionationGlobalDateDir . $BasenameFile;

    /*
    // eğer klasör yoksa oluştur
    if (!file_exists(FILE_STORE_CODE_UPLOAD)) {
      // 777 dememizin nedeni:
      // 7 = okuma, yazma, erişme izni
      // 777 diyerekten, sahip, grup ve diğer kullanıcılara
      // dosya da değişiklik yapabilme izini tanıyoruz
      mkdir(FILE_STORE_CODE_UPLOAD, 0777, true);
    }

    // eğer kullanıcıya özel klasör yoksa id'si ile oluştur
    /*if (!file_exists($DATA_SESSION_STORE_FILE)) {
      // Dosyanın ana sahibi ve grup (7) izinilerin
      // hepsine sahip olsun diğer kullanıcılar sadece
      // okuma ve erişme iznine sahip olsun
      mkdir($DATA_SESSION_STORE_FILE, 0775, true);
    }
!file_exists($destionationLocalDateDir)) {
      // Dosyanın ana sahibi ve grup (7) izinilerin
      // hepsine sahip olsun diğer kullanıcılar sadece
      // okuma ve erişme iznine sahip olsun 
      mkdir($destionationLocalDateDir, 0775, true);
    }*/

    /*
    // dosyayı içe aktar
    if (move_uploaded_file($tempFileName, $destionationLocalFile)) {
      // veritabanına kullanıcı bilgisiyle birlikte
      // eski dosya bilgisini silmek ve yenisini kaydetmek

      $sqlRemoveOldFileInfo = "DELETE FROM " .ConfigTable::$table_kodlar
      ." WHERE " .ConfigTable::$column_kodlar_1 ." = ?"
      ." AND " .ConfigTable::$column_kodlar_8 ." = ?";

      // eski dosyaları silen sorguyu hazırlama
      $stmtRemoveOldFileInfo = $databaseconn->prepare($sqlRemoveOldFileInfo);

      // parametrelerini ekleme
      $stmtRemoveOldFileInfo->bind_param("is",
        $_SESSION[SessionManager::$session_var_id],
        $destionationGlobalFile
      );

      // eski dosyaları silme kodunu çalıştır ve sorgu başarılı ise
      if($stmtRemoveOldFileInfo->execute()) {
        // dosya bilgilerini kaydetmeyi sağalyacak sorgu
        $sqlSaveFileInfo = "INSERT INTO " .ConfigTable::$table_table_b.
        "("
          .ConfigTable::$column_table_b_1." , " // sahip
          .ConfigTable::$column_table_b_2." , " // ad
          .ConfigTable::$column_table_b_12." , " // açıklama
          .ConfigTable::$column_table_b_3." , " // kod uzantı
          .ConfigTable::$column_table_b_7." , " // boyut
          .ConfigTable::$column_table_b_8 // link
        .") VALUES (?,?,?,?,?,?)";

        // sorguyu hazırlama
        $stmtSaveFileInfo = $databaseconn->prepare($sqlSaveFileInfo);

        // değişkenleri belirtme
        $stmtSaveFileInfo->bind_param(
          "isssis",
          $_SESSION[SessionManager::$session_var_id], // oturum kullanıcı id
          $uploadFileCustomName, // dosyaya verilmiş özel ad
          $uploadFileCustomComment, // dosyaya verilmiş özel açıklama
          $uploadFileExtension, // kod uzantı
          $uploadFileSize, // kod boyut (byte)
          $destionationGlobalFile // küresel kod link
        );

        // sorguyu çalıştırma ve başarılıysa
        if($stmtSaveFileInfo->execute()) {
          // kullanıcının ekranına dosya adı, uzantısı ve boyutunu çıktı verme
          // işlem başarılı
          echo "
          <script>
            MessageSuccess('$uploadFileCustomName', '$uploadFileCustomComment', '$uploadFileName [$uploadFileSize BYTE]', 0);
            </script>
          ";
        }
      }
      else {
        // başarısız
        echo "
          <script>
            MessageError('[Database] $uploadFileCustomName', '$uploadFileCustomComment', '$uploadFileName [$uploadFileSize BYTE]', 0);
          </script>
        ";
      }
      
    } else {
      // dosya yükleme başarısız
      echo "
        <script>
          MessageError('$uploadFileName.$uploadFileExtension', '$uploadFileCustomComment', '$uploadFileName [$uploadFileSize BYTE]', 0);
        </script>
      ";
    }*/
    // o güne ait klasör yoksa oluştursun
    /*
    if (!file_exists($destionationLocalDateDir)) {
      // Dosyanın ana sahibi ve grup (7) izinilerin
      // hepsine sahip olsun diğer kullanıcılar sadece
      // okuma ve erişme iznine sahip olsun 
      mkdir($destionationLocalDateDir, 0775, true);
    }*/

    /*
    // dosyayı içe aktar
    if (move_uploaded_file($tempFileName, $destionationLocalFile)) {
      // veritabanına kullanıcı bilgisiyle birlikte
      // eski dosya bilgisini silmek ve yenisini kaydetmek

      $sqlRemoveOldFileInfo = "DELETE FROM " .ConfigTable::$table_kodlar
      ." WHERE " .ConfigTable::$column_kodlar_1 ." = ?"
      ." AND " .ConfigTable::$column_kodlar_8 ." = ?";

      // eski dosyaları silen sorguyu hazırlama
      $stmtRemoveOldFileInfo = $databaseconn->prepare($sqlRemoveOldFileInfo);

      // parametrelerini ekleme
      $stmtRemoveOldFileInfo->bind_param("is",
        $_SESSION[SessionManager::$session_var_id],
        $destionationGlobalFile
      );

      // eski dosyaları silme kodunu çalıştır ve sorgu başarılı ise
      if($stmtRemoveOldFileInfo->execute()) {
        // dosya bilgilerini kaydetmeyi sağalyacak sorgu
        $sqlSaveFileInfo = "INSERT INTO " .ConfigTable::$table_table_b.
        "("
          .ConfigTable::$column_table_b_1." , " // sahip
          .ConfigTable::$column_table_b_2." , " // ad
          .ConfigTable::$column_table_b_12." , " // açıklama
          .ConfigTable::$column_table_b_3." , " // kod uzantı
          .ConfigTable::$column_table_b_7." , " // boyut
          .ConfigTable::$column_table_b_8 // link
        .") VALUES (?,?,?,?,?,?)";

        // sorguyu hazırlama
        $stmtSaveFileInfo = $databaseconn->prepare($sqlSaveFileInfo);

        // değişkenleri belirtme
        $stmtSaveFileInfo->bind_param(
          "isssis",
          $_SESSION[SessionManager::$session_var_id], // oturum kullanıcı id
          $uploadFileCustomName, // dosyaya verilmiş özel ad
          $uploadFileCustomComment, // dosyaya verilmiş özel açıklama
          $uploadFileExtension, // kod uzantı
          $uploadFileSize, // kod boyut (byte)
          $destionationGlobalFile // küresel kod link
        );

        // sorguyu çalıştırma ve başarılıysa
        if($stmtSaveFileInfo->execute()) {
          // kullanıcının ekranına dosya adı, uzantısı ve boyutunu çıktı verme
          // işlem başarılı
          echo "
          <script>
            MessageSuccess('$uploadFileCustomName', '$uploadFileCustomComment', '$uploadFileName [$uploadFileSize BYTE]', 0);
            </script>
          ";
        }
      }
      else {
        // başarısız
        echo "
          <script>
            MessageError('[Database] $uploadFileCustomName', '$uploadFileCustomComment', '$uploadFileName [$uploadFileSize BYTE]', 0);
          </script>
        ";
      }
      
    } else {
      // dosya yükleme başarısız
      echo "
        <script>
          MessageError('$uploadFileName.$uploadFileExtension', '$uploadFileCustomComment', '$uploadFileName [$uploadFileSize BYTE]', 0);
        </script>
      ";
    }*/
  }
}
?>
<section id="data-add-file" class="data-add-file" data="job-add-file">
  <form id="fileupload-form-area" class="fileupload-form-area"
  method="post" enctype="multipart/form-data">
    <div id="fileupload-fileimport-area" class="fileupload-fileimport-area">
      <input id="fileupload-fileimport-inputimport" class="fileupload-fileimport-inputimport"
      type="file" name="<?php echo FILE_IMPORT; ?>[]" multiple
      value="<?php echo $text_fileupload_import_title; ?>" hidden
      accept=".asm, .wasm, .c, .cpp, .h, .hpp, .java, .py, .js, .html, .htm
      , .css, .rb, .swift, .m, .mm, .cs, .php, .go, .rs, .ts, .kt, .kotlin
      , .pl, .sh, .sql, .json, .xml, .md, .yaml, .yml, .dart, .r, .jsx"/>
      <button id="fileupload-fileimport-btn" class="fileupload-fileimport-btn"
      type="button">
        <?php echo StaticSvg::$SVG_file_add; ?>
      </button>
    </div>
    <div id="fileupload-filename-area" class="fileupload-filename-area">
      <input id="fileupload-filename-inputname" class="fileupload-filename-inputname"
      type="text" name="<?php echo PROJECT_NAME; ?>" maxlength="50"
      placeholder="<?php echo $placeholder_input_customname; ?>"/> 
    </div>
    <div id="fileupload-filecomment-area" class="fileupload-filecomment-area">
      <textarea id="fileupload-filecomment-textareacomment" class="fileupload-filecomment-textareacomment"
      type="text" name="<?php echo PROJECT_COMMENT; ?>"
      placeholder="<?php echo $placeholder_input_customcomment; ?>"></textarea>
    </div>
    <div id="fileupload-filesubmit-area" class="fileupload-filesubmit-area">
      <button id="fileupload-filesubmit" class="fileupload-filesubmit"
      type="submit" name="<?php echo FILE_SUBMIT; ?>">
        <?php
          echo StaticSvg::$SVG_add_to_box;
          echo LanguageSupport::getlang($lang_key__create_new_project, $session->get(SessionManager::$session_var_language));
        ?>
      </button>
      <button id="fileupload-filereset" class="fileupload-filereset"
      type="reset" name="<?php echo FILE_RESET; ?>">
        <?php
          echo StaticSvg::$SVG_menu_close;
          echo LanguageSupport::getlang($lang_key__clear, $session->get(SessionManager::$session_var_language));
        ?>
      </button>
    </div>
    <div id="fileupload-fileinfo-area" class="fileupload-fileinfo-area">
      <p data-info="0" id="fileupload-fileinfo-text" class="fileupload-fileinfo-text">
        <?php
          echo LanguageSupport::getlang($lang_key__max_file_size, $session->get(SessionManager::$session_var_language));
          echo " $DATA_SESSION_MAX_FILE_SIZE_MB ";
        ?> MB
      </p>
    </div>
    <div id="fileupload-fileresult-area" class="fileupload-fileresult-area"></div>
  </form>
  <!-- FORM FILE UPLOAD CSS -->
  <link rel="stylesheet" type="text/css" href="design/style/css/page/data-add/page-data-add-files.css"/>
  <!-- FORM FILE UPLOAD JS -->
  <script nonce>
    // dosya boyutuna göre adlandırma
    function formatFileSize(bytes) {
      if (bytes < 1024) {
          return bytes + " BYTE";
      } else if (bytes < 1024 * 1024) {
          return (bytes / 1024).toFixed(2) + " KB";
      } else if (bytes < 1024 * 1024 * 1024) {
          return (bytes / (1024 * 1024)).toFixed(2) + " MB";
      } else {
          return (bytes / (1024 * 1024 * 1024)).toFixed(2) + " GB";
      }
    }

    // dosya ekleme ve bağlantı sağlayan buton
    const element_add_data_area = document.querySelector('#fileupload-fileresult-area');
    const element_fileimport = document.querySelector("button#fileupload-fileimport-btn");
    const element_fileimport_file = document.querySelector("input[type='file']");
    const element_form_clear = document.querySelector("[type='reset']");

    function clearUploaded(element) {
      while (element.firstChild) {
        element.removeChild(element.firstChild);
      }
    }

    // dosya aktarmayı sağlayan butona tıklama
    element_fileimport.addEventListener("click", function() {
      element_fileimport_file.click();
    });

    // form temizleme
    element_form_clear.addEventListener("click", function() {
      // elemanın içindeki tüm veriyi temizleme
      clearUploaded(element_add_data_area);
    });

    // dosya da değişiklik olduğunda
    element_fileimport_file.onchange = ({target}) => {
      // elemanın içindeki tüm veriyi temizleme
      clearUploaded(element_add_data_area);

      // ilk dosyayı tutmak
      const data_files = target.files;
      const length_datafiles = data_files.length;

      // dizi bitene kadar dosyaları al
      for(var i = 0; i < length_datafiles; i++) {
        // dosyaya ait verileri al
        const data_file = data_files[i];
        const data_file_name = data_file.name;
        const data_file_size = data_file.size;
        const data_file_type = data_file.type;
        const data_file_lastmodified = data_file.lastModified;

        // eğer dosya var ve seçilmişse
        if(data_file) {
          // boş değilse
          if(element_add_data_area) {
            // elemanları oluşturmak
            const element_area_files = document.createElement("div");
            const element_area_files_file_svg = `<?php echo StaticSvg::$SVG_file_code; ?>`;
          
            // metini ayarlamak
            element_area_files.innerHTML = `
            <div>
              <span>
                ${element_area_files_file_svg}
                ${data_file_name}
              </span>
            </div>
            <div>
              <span>${formatFileSize(data_file_size)}</span>
            </div>
            `;

            // elementin özniteliğini ayarlamak
            element_area_files.setAttribute("id", "fileupload-fileresult-info-data");
            element_area_files.setAttribute("class", "fileupload-fileresult-info-data");

            // eğer elemente tıklanmışsa
            element_area_files.addEventListener("dblclick", function() {
                element_area_files.remove();
            });
            
            // ana elemanın içine aktarma
            element_add_data_area.appendChild(element_area_files);
          }
        }
      }
    }
  </script>
</section>