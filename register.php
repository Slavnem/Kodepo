<!-- HTML5 -->
<!doctype html>
<?php
// register sayfasına ait tüm işleri yapan parça
require_once("design/page/register/register-all-in-one.php");
?>
<!-- REGISTER -->
<html>
  <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="<?php echo $RegisterAllInOne->getSystemImage(0); ?>"/>
    <link rel="apple-touch-icon" href="<?php echo $RegisterAllInOne->getSystemImage(1); ?>"/>
    <title>
      <?php
        // misafir oturumu
        echo LanguageSupport::$LANGUAGE_WEBSITE;
        // İngilizce dünya dili olduğu için 2
        echo " | ". LanguageSupport::getlang(LanguageSupport::$lang_key__signup, 2);
      ?>
    </title>
    <link rel="stylesheet" type="text/css" href="design/style/css/root.css"/>
    <link rel="stylesheet" type="text/css" href="design/style/css/account/account-sign.css"/>
  </head>
  <body>
    <main>
    <section id="sign" class="sign" background="sign-background-add"
        style="background: linear-gradient(to right bottom, rgba(0,0,0,0.25), rgba(0,0,0,0.5)), url('<?php echo $RegisterAllInOne->getSystemImage(2); ?>');
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
                  <?php echo $RegisterAllInOne->getError(0); ?>
                  <?php echo $RegisterAllInOne->getError(1); ?>
                  <?php echo $RegisterAllInOne->getError(2); ?>
                  <?php echo $RegisterAllInOne->getError(3); ?>
                  <?php echo $RegisterAllInOne->getError(4); ?>
                  <?php echo $RegisterAllInOne->getError(5); ?>
                  <?php echo $RegisterAllInOne->getError(6); ?>
                  <?php echo $RegisterAllInOne->getError(7); ?>
                  <?php echo $RegisterAllInOne->getError(8); ?>
                </div>
                <div id="sign-form-input-area" class="sign-form-input-area">
                  <div id="input-username-area" class="input-username-area">
                    <input type="text" name="<?php echo $name_input_username; ?>" placeholder="abcde" title="username" <?php getInputValue($name_input_username); ?>
                    id="input-username" class="input-username"/>
                  </div>
                  <div id="input-password-area" class="input-password-area">
                    <input type="password" name="<?php echo $name_input_password; ?>" placeholder="******" title="password" <?php getInputValue($name_input_password); ?>
                    id="input-password" class="input-password"/>
                    <button type="button" name="btn-password-toggle" id="btn-password-toggle" class="btn-password-toggle" onclick="togglePasswordShow();">
                      <?php echo StaticSvg::$SVG_seepassword; ?>
                    </button>
                  </div>
                  <div id="input-email-area" class="input-email-area">
                    <input type="text" name="<?php echo $name_input_email; ?>" placeholder="abc@xyz.com" title="email" <?php getInputValue($name_input_email); ?>
                    id="input-email" class="input-email"/>
                  </div>
                  <div id="input-name-area" class="input-name-area">
                    <input type="text" name="<?php echo $name_input_name; ?>" placeholder="name" title="name" <?php getInputValue($name_input_name); ?>
                    id="input-name" class="input-name"/>
                  </div>
                  <div id="input-surname-area" class="input-surname-area">
                    <input type="text" name="<?php echo $name_input_surname; ?>" placeholder="surname" title="surname" <?php getInputValue($name_input_surname); ?>
                    id="input-surname" class="input-surname"/>
                  </div>
                  <div id="input-submit-area" class="input-submit-area">
                    <input type="submit" name="<?php echo $name_input_submit; ?>" value="<?php echo $value_input_submit; ?>"
                    id="input-submit" class="input-submit"/>
                  </div>
                </div>
              </form>
              <div id="sign-now-area" class="sign-now-area">
                <span id="btn-sign-now" class="btn-sign-now"
                onclick="window.location.href='<?php echo $RegisterAllInOne->changePage(); ?>'">
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
