<!-- HTML5 -->
<!doctype html>
<?php
// giriş yapılmış bazı sayfalar için otomatik tutulmuş dosya
require_once("design/page/auto/auto-import-global.php");
?>
<!-- USERS -->
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0"/>
    <link rel="icon" href="<?php echo $AutoImportGlobal->getSysData(0); /* logo icon */ ?>"/>
    <link rel="apple-touch-icon" href="<?php echo $AutoImportGlobal->getSysData(1); /* logo image */ ?>"/>
    <title>
      <?php
        echo LanguageSupport::$LANGUAGE_WEBSITE;
        echo " | ". $AutoImportGlobal->getLangData(2);
      ?>
    </title>
    <link rel="stylesheet" type="text/css" href="design/style/css/root.css"/>
  </head>
  <body>
    <?php
      // header
      require_once("design/dynamic/navigation/navigation-header.php");
      // kayıtlı kullanıcı sorgulayıcı
      include("design/page/users/users-main.php");
    ?>
  </body>
</html>
