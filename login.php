<!-- HTML5 -->
<!doctype html>
<?php
// login sayfasına ait tüm işleri yapan parça
require_once("design/page/login/login-all-in-one.php");
?>
<!-- LOGIN -->
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo $LoginAllInOne->getSystemImage(0); ?>"/>
    <link rel="apple-touch-icon" href="<?php echo $LoginAllInOne->getSystemImage(1); ?>"/>
    <title>
      <?php
        // misafir oturumu
        echo LanguageSupport::$LANGUAGE_WEBSITE;
        // İngilizce dünya dili olduğu için 2
        echo " | ". LanguageSupport::getlang(LanguageSupport::$lang_key__login, 2);
      ?>
    </title>
    <link rel="stylesheet" type="text/css" href="design/style/css/root.css"/>
    <link rel="stylesheet" type="text/css" href="design/style/css/account/account-sign.css"/>
  </head>
  <body>
    <main>
      <section id="sign" class="sign" background="sign-background-add"
        style="background: linear-gradient(to right bottom, rgba(0,0,0,0.25), rgba(0,0,0,0.5)), url('<?php echo $LoginAllInOne->getSystemImage(2); ?>');
      ">
        <div id="sign-container" class="sign-container">
          <div id="sign-center" class="sign-center">
            <div id="sign-column" class="sign-column">
              <div id="sign-header" class="sign-header">
                <h3 id="sign-header-title" class="sign-header-title">
                  <?php echo $text_sign_header_title; ?>
                </h3>
                <span id="sign-header-comment" class="sign-header-comment">
                  <?php echo $text_sign_header_paragraph; ?>
                </span>
              </div>
              <form method="POST" id="sign-form" class="sign-form">
                <div id="error-area" class="error-area">
                  <?php echo $LoginAllInOne->getError(0); ?>
                </div>
                <div id="sign-form-input-area" class="sign-form-input-area">
                  <div id="input-username-area" class="input-username-area">
                    <input type="text" name="<?php echo $name_input_username; ?>" placeholder="abcde" title="username" value="<?php getInputValue($name_input_username); ?>"
                    id="input-username" class="input-username"/>
                  </div>
                  <div id="input-password-area" class="input-password-area">
                    <input type="password" name="<?php echo $name_input_password; ?>" placeholder="******" title="password"
                    id="input-password" class="input-password"/>
                    <button type="button" name="<?php echo $name_btn_password_toggle; ?>" id="btn-password-toggle" class="btn-password-toggle" onclick="togglePasswordShow()">
                      <?php echo StaticSvg::$SVG_seepassword; ?>
                    </button>
                  </div>
                  <div id="input-submit-area" class="input-submit-area">
                    <input type="submit" name="<?php echo $name_input_submit; ?>" value="<?php echo $value_input_submit; ?>"
                    id="input-submit" class="input-submit"/>
                  </div>
                </div>
              </form>
              <div id="sign-now-area" class="sign-now-area">
                <span id="btn-sign-now" class="btn-sign-now"
                onclick="window.location.href='<?php echo $LoginAllInOne->changePage(); ?>'">
                  <?php echo $text_sign_header_noaccount; ?>
                </span>
              </div>
            </div>
          </div>
        </div>
      </section>
    </main>
  </body>
</html>