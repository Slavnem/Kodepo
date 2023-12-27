<?php
// giriş yapılmış bazı sayfalar için otomatik tutulmuş dosya
require_once("design/page/auto/auto-import-global.php");

// menü adlar
$name_menu = "menu-go";
$name_menu_main = "main";
$name_menu_users = "go-users";
$name_menu_codes = "go-codes";
$name_menu_account_settings = "account-settings";
$name_menu_data_add = "data-add";
$name_menu_account_logout = "account-logout";
$name_menu_account_login = "account-login";
$name_menu_account_register = "account-signup";
$name_array_public_data = "PUBLIC_DATA_SESSION";
$name_array_public_logged_menu = "PUBLIC_DATA_LOGGED_MENU";
$name_array_public_guest_menu = "PUBLIC_DATA_GUEST_MENU";

// kullanıcıya ait dizi içindeki ayraçlar
$public_name_user_name = "user_name";
$public_name_user_surname = "user_surname";
$public_name_user_rank = "user_rank";

// dizilerin içindeki ayraçlar
$public_name_url = "url";
$public_name_icon = "icon";
$public_name_text = "text";

// kullanıcı giriş yaptığındaki kullanabileceği menüler
$array_name_menu_logged = [
  $name_array_public_logged_menu => [
    [
      $public_name_url => CheckerUser::$controlHomepage,
      $public_name_icon => StaticSvg::$SVG_home,
      $public_name_text => $AutoImportGlobal->getLangData(0)
    ],
    [
      $public_name_url => CheckerUser::$controlCodes,
      $public_name_icon => StaticSvg::$SVG_code,
      $public_name_text => $AutoImportGlobal->getLangData(1)
    ],
    [
      $public_name_url => CheckerUser::$controlUsers,
      $public_name_icon => StaticSvg::$SVG_users,
      $public_name_text => $AutoImportGlobal->getLangData(2)
    ],
    [
      $public_name_url => CheckerUser::$controlAccountSettings,
      $public_name_icon => StaticSvg::$SVG_setting,
      $public_name_text => $AutoImportGlobal->getLangData(3)
    ],
    [
      $public_name_url => CheckerUser::$controlDataAdd,
      $public_name_icon => StaticSvg::$SVG_add,
      $public_name_text => $AutoImportGlobal->getLangData(4)
    ],
    [
      $public_name_url => CheckerUser::$controlLogout,
      $public_name_icon => StaticSvg::$SVG_exit,
      $public_name_text => $AutoImportGlobal->getLangData(5)
    ]
  ]
];

// giriş yapmadığındaki görebileceği menüler
$array_name_menu_guest = [
  $name_array_public_guest_menu => [
    [
      $public_name_url => CheckerUser::$controlHomepage,
      $public_name_icon => StaticSvg::$SVG_home,
      $public_name_text => $AutoImportGlobal->getLangData(0)
    ],
    [
      $public_name_url => CheckerUser::$controlCodes,
      $public_name_icon => StaticSvg::$SVG_code,
      $public_name_text => $AutoImportGlobal->getLangData(1)
    ],
    [
      $public_name_url => CheckerUser::$controlLogin,
      $public_name_icon => StaticSvg::$SVG_enter,
      $public_name_text => $AutoImportGlobal->getLangData(6)
    ],
    [
      $public_name_url => CheckerUser::$controlRegister,
      $public_name_icon => StaticSvg::$SVG_user_add,
      $public_name_text => $AutoImportGlobal->getLangData(7)
    ]
  ]
];

// oturuma ait verilerin kullanıcı tarafından görülmesi sorun oluşturmayanlar
// veri yok giriş başarısız
if(isset($_SESSION[SessionManager::$session_var_id]) && $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_id)) {
  // giriş başarılı
  // oturuma ait verilerin kullanıcı tarafından görülmesi sorun oluşturmayanlar
  $array_public_data_user_session = [
    $public_name_user_name => $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_firstname),
    $public_name_user_surname => $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_lastname),
    $public_name_user_rank => $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_rank)
  ];

  // kullanıcı verilerini JSON objesine dönüştür
  $JSON_name_array_public_user_data = json_encode($array_public_data_user_session);
}

// dizileri JSON objesine dönüştürüyoruz
$JSON_name_array_public_logged_menu = json_encode($array_name_menu_logged[$name_array_public_logged_menu]);
$JSON_name_array_public_guest_menu = json_encode($array_name_menu_guest[$name_array_public_guest_menu]);
?>
<!-- NAVIGATION HEADER -->
<header id="header-menu" class="header-menu">
  <nav id="menu-navigation" class="menu-navigation">
      <!-- HEADER LOGO -->
      <div id="logo-area" class="logo-area">
        <?php
            // logo için sorgula
            $resultSystemQueryLogo = $AutoImportGlobal->getSysData(1,1);

            // logo bulunmuşsa
            if($resultSystemQueryLogo != null) {
              $RANDOM_LOGO_pid = $resultSystemQueryLogo[ConfigTable::$column_table_d_1];
              $RANDOM_LOGO_name = $resultSystemQueryLogo[ConfigTable::$column_table_d_2];
              $RANDOM_LOGO_longname = $resultSystemQueryLogo[ConfigTable::$column_table_d_3];
              $RANDOM_LOGO_link = $resultSystemQueryLogo[ConfigTable::$column_table_d_4];
            }
            else {
              $RANDOM_LOGO_pid = null;
              $RANDOM_LOGO_name = null;
              $RANDOM_LOGO_longname = null;
              $RANDOM_LOGO_link = null;
            }
        ?>
        <img id="logo" class="logo" src="<?php echo $RANDOM_LOGO_link; ?>" alt="<?php echo $RANDOM_LOGO_longname; ?>" width="32px" height="32px"/>
      </div>
      <div id="header-menu-btn-area" class="header-menu-btn-area">
        <button id="header-menu-btn" class="header-menu-btn" title="Menu">
          <script nonce>
            // butonu bul
            const element_menubtn = document.querySelector("#header-menu-btn");

            // butona tıklandığında menüyü aç/kapat, buton icon değiştir
            element_menubtn.addEventListener("click", function() {
              if(document.querySelector("#menu-area").style.display !== "none") {
                element_menubtn.innerHTML = `<?php echo StaticSvg::$SVG_menu_show; ?>`;
                document.querySelector("#menu-area").style.display = "none";
              }
              else {
                element_menubtn.innerHTML = `<?php echo StaticSvg::$SVG_menu_close; ?>`;
                document.querySelector("#menu-area").style.display = null;
              }
            });
          </script>
        </button>
      </div>
      <!-- HEADER MENU -->
      <div id="menu-area" class="menu-area">
          <?php
            // eğer giriş yapılmışsa
            switch($AutoImportGlobal->getSession(AutoImportGlobal::$var_session_logged) && $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_id) > 0) {
              case 0: // misafir
                // misafir kullanıcı ise menü de kullanıcı içeriği olmayacak
                // menü sayısı oluşturmak için döngü kullanıyoruz
                foreach($array_name_menu_guest as $menuindex => $subArray) {
                  foreach($subArray as $subindex => $subitem) {
                    // ana dizinin içindeki elemenlara ait dizilerdeki değerlere erişme
                    $temp_public_name_url = $subitem[$public_name_url];
                    $temp_public_name_icon = $subitem[$public_name_icon];
                    $temp_public_name_text = $subitem[$public_name_text];

                    echo "
                    <a class='menu-redirect-btn' title='$temp_public_name_text' href='$temp_public_name_url'>$temp_public_name_icon $temp_public_name_text</a>"
                    ;
                  }
                }
              break;
              case 1: // kullanıcı
                // kullanıcıya ait isim, soyisim ve rütbeyi çıktı vermek
                $temp_name = $array_public_data_user_session[$public_name_user_name];
                $temp_surname = $array_public_data_user_session[$public_name_user_surname];
                $temp_icon = $SVG_ARRAY_RANK[$array_public_data_user_session[$public_name_user_rank]];

                echo "
                <div id='menu-user-area' class='menu-user-area'>
                    <div id='menu-user-area-text' class='menu-user-area-text'>
                      <p>$temp_name</p>
                    </div>
                    <div id='menu-user-area-icon' class='menu-user-area-icon'>
                      $temp_icon
                    </div>
                </div>
                <div id='menu-redirect-area' class='menu-redirect-area'>";

                // menü sayısı oluşturmak için döngü kullanıyoruz
                foreach($array_name_menu_logged as $menuindex => $subArray) {
                  foreach($subArray as $subindex => $subitem) {
                    // ana dizinin içindeki elemenlara ait dizilerdeki değerlere erişme
                    $temp_public_name_url = $subitem[$public_name_url];
                    $temp_public_name_icon = $subitem[$public_name_icon];
                    $temp_public_name_text = $subitem[$public_name_text];

                    echo "
                    <a class='menu-redirect-btn' title='$temp_public_name_text' href='$temp_public_name_url'>$temp_public_name_icon $temp_public_name_text</a>"
                    ;
                  }
                }

                // element sonlandırma
                echo "</div>";
                break;
            }
          ?>
        </div>
        <!-- FORM BASIC JS -->
        <script nonce>
          const headerArea = document.querySelector("header#header-menu.header-menu");

          // scroll => fixed
          window.addEventListener("scroll", function() {
            if(headerArea != null) {
              if(window.scrollY > 100)
                headerArea.style.position = "fixed";
              else
                headerArea.style.position = "relative";
            }
          });

          // (button == true && menu == hide) => (auto icon = true)
          if(document.querySelector("#menu-area").style.display !== "none") {
            element_menubtn.innerHTML = `<?php echo StaticSvg::$SVG_menu_show; ?>`;
            document.querySelector("#menu-area").style.display = "none";
          }
        </script>
      </div>
  </nav>
</header>
<!-- HEADER CSS -->
<link rel="stylesheet" type="text/css" href="design/style/css/navigation/navigation-header.css"/>