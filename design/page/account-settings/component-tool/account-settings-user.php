<?php
define("HREF_CSS_ACCOUNT_SETTINGS_USER", "design/style/css/page/account-settings/component-tool/page-tool-account-settings-user.css");

// dizideki elemenların sıra
$array_num_type = 0;
$array_num_text = 1;
$array_num_sessiondata = 2;
$array_num_name = 3;

$array_user_num_username = 0;
$array_user_num_firstname = 1;
$array_user_num_lastname = 2;
$array_user_num_email = 3;
$array_user_num_rank = 4;
$array_user_num_language = 5;
$array_user_num_customization_mod = 6;
$array_user_num_password = 7;

$array_lang_id = ConfigTable::$column_table_e_0;
$array_lang_name = ConfigTable::$column_table_e_1;
$array_lang_short = ConfigTable::$column_table_e_2;

$array_custom_mod_id = ConfigTable::$column_table_f_4;

// kullanılabilir dilleri getirme
$ARRAY_AVAILABLE_LANGUAGES = $AutoImportGlobal->getAllData(ConfigTable::$table_e);

// Modları almak
$TEMP_MODS = $AutoImportGlobal->getSelected(
    [ConfigTable::$column_table_g_1],
    [$AutoImportGlobal->getSession(AutoImportGlobal::$var_session_id)],
    ConfigTable::$table_g
);

// modların numaralarını aktaracağımız dizi
$MOD_LIST = [];

// modların numaralarını diziye aktarmak
foreach($TEMP_MODS as $indexmod => $mod) {
    $MOD_LIST[] = $mod[ConfigTable::$column_table_g_2];
}

// kullanılabilir modları kullanıcının modlarını koy
$ARRAY_AVAILABLE_CUSTOMIZATION_MODS = $MOD_LIST;

// kullanıcı bilgilerini almak ve diziye aktarmak
$ARRAY_DATA_USER = [
    [ // USERNAME
        0, // degistirilemez
        AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_username) . ": ",
        $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_username),
        null
    ],
    [ // FIRSTNAME
        1, // degistirilebilir
        AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_firstname) . ": ",
        $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_firstname),
        "user-change-firstname",
    ],
    [ // LASTNAME
        1, // degistirilebilir
        AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_lastname) . ": ",
        $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_lastname),
        "user-change-lastname",
    ],
    [ // EMAIL
        1, // degistirilebilir
        AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_email) . ": ",
        $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_email),
        "user-change-email",
    ],
    [ // RANK
        0, // degistirilemez
        AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_rank) . ": ",
        $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_rank),
        null
    ],
    [ // LANGUAGE
        2, // özel olarak değiştirilebilir
        AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_language_name) . ": ",
        $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_language_name)
        . " (" .$AutoImportGlobal->getSession(AutoImportGlobal::$var_session_language_short) .")",
        'user-change-language'
    ],
    [
        // MOD
        4, // özel olarak değiştirilebilir
        AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_customization_mod) . ": ",
        $MOD_LIST,
        // $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_customization_mod),
        "user-change-customization-mod"
    ],
    [ // PASSWORD
        3, // özel olarak değiştirilebilir (password)
        AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_password) . ": ",
        $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_password),
        'user-change-password'
    ]
];
?>
<!-- ACCOUNT SETTINGS USER CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo HREF_CSS_ACCOUNT_SETTINGS_USER; ?>"/>
<section account-settings-type="user" id="account-settings-user" class="account-settings-user">
    <form method="POST"
    id='account-settings-user-info-area' class='account-settings-user-info-area'>
        <?php
            // değerleri çıktı vermek
            foreach($ARRAY_DATA_USER as $arraydataIndex => $DataUser) {
                $dynamicElement = null;

                // eğer değiştirilebilirse input
                // değiştirilemezse metin olarak çıktı versin
                switch($DataUser[$array_num_type]) {
                    // değiştirilemez
                    case 0:
                        $dynamicElement = "
                            <p id='account-settings-text-header-title' class='account-settings-text-header-title'><span>$DataUser[$array_num_sessiondata]<span></p>";
                        break;
                    // değiştirilebilir
                    case 1:
                        $dynamicElement = "
                            <input autocomplete='off' type='text' name='$DataUser[$array_num_name]' value='$DataUser[$array_num_sessiondata]'
                            num='$arraydataIndex' class='account-settings-text-body-text'/>
                        ";
                        break;
                    // özel listeleme sadece dil için
                    case 2:
                        // elementleri toplu halde tutacak olan geçici dizi
                        $temp_list_element_available_lang = null;

                        // dilleri elementi içine aktarma
                        foreach($ARRAY_AVAILABLE_LANGUAGES as $indexAvaiLang => $AvailableLanguage) {
                            // şuanki kullanılan dil değeri otomatik null
                            $activelang = null;

                            $temp_lang_id = $AvailableLanguage[$array_lang_id];
                            $temp_lang_name = $AvailableLanguage[$array_lang_name];
                            $temp_lang_short = $AvailableLanguage[$array_lang_short];

                            // eğer dil oturum diline eşitse otomatik onu seçilmiş yapması için
                            // oturumdaki dil kodunu ve kısaltması ile kıyaslıyoruz
                            if(
                                $temp_lang_id == $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_language)
                                && $temp_lang_short == $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_language_short)
                            )
                            {
                                // oturum dili bulundu
                                // html otomatik seçmesi için selected yapıyoruz
                                $activelang = "selected";
                            }

                            $temp_list_element_available_lang = $temp_list_element_available_lang .
                            "<option name='$DataUser[$array_num_name]' value='$temp_lang_short' $activelang>$temp_lang_name ($temp_lang_short)</option>";
                        }

                        // elemenları elementin içine aktarma
                        $dynamicElement = "
                            <select name='$DataUser[$array_num_name]'>$temp_list_element_available_lang</select>
                        ";
                        break;
                    // sadece şifre için
                    case 3:
                        $passwdsee_svg = StaticSvg::$SVG_seepassword;
                        $passwdhide_svg = StaticSvg::$SVG_hidepassword;
                        $passwdlock_svg = StaticSvg::$SVG_lock;
                        $passwdunlock_svg = StaticSvg::$SVG_unlock;
                        $passwd_name = $ARRAY_DATA_USER[$array_user_num_password][$array_num_name];
                        $passwd_sessiondata = $ARRAY_DATA_USER[$array_user_num_password][$array_num_sessiondata];
                        $passwdsee_title = $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__text_password_show);
                        $passwdhide_title = $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__text_password_hide);
                        $passwdlock_title = $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__text_password_lock);
                        $passwdunlock_title = $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__text_password_unlock);
                        
                        $dynamicElement = "
                            <input autocomplete='off' type='password' name='$passwd_name' placeholder='$passwd_sessiondata'
                            num='$arraydataIndex' class='account-settings-text-body-text'/>

                            <button type='button' id='input-show-password-toggle' class='input-show-password-toggle'
                            onclick='togglePasswordShow()'></button>

                            <button type='button' id='input-write-password-toggle' class='input-write-password-toggle'
                            onclick='togglePasswordWrite()'></button>

                            <!-- PASSWORD TOGGLE JS -->
                            <script nonce>
                                // element
                                const element_btn_password_show_toggle = document.querySelector('button#input-show-password-toggle');
                                const element_btn_password_write_toggle = document.querySelector('button#input-write-password-toggle');
                                const element_input_password = document.querySelector('input[name=user-change-password]');

                                // password show
                                function password_element_html(type) {
                                    switch(type) {
                                        // show
                                        case 0:
                                            if(element_btn_password_show_toggle !== null) {
                                                if(element_input_password.type !== 'password') {
                                                    element_btn_password_show_toggle.innerHTML = `$passwdhide_svg`;
                                                    element_btn_password_show_toggle.title = '$passwdhide_title';
                                                }
                                                else {
                                                    element_btn_password_show_toggle.innerHTML = `$passwdsee_svg`;
                                                    element_btn_password_show_toggle.title = '$passwdsee_title';
                                                }
                                            }
                                            break;
                                        // write
                                        case 1:
                                            if(element_btn_password_write_toggle !== null) {
                                                if(element_input_password.readOnly) {
                                                    element_btn_password_write_toggle.innerHTML = `$passwdlock_svg`;
                                                    element_btn_password_write_toggle.title = '$passwdunlock_title';
                                                }
                                                else {
                                                    element_btn_password_write_toggle.innerHTML = `$passwdunlock_svg`;
                                                    element_btn_password_write_toggle.title = '$passwdlock_title';
                                                }
                                            }
                                            break;
                                    }
                                }

                                // password readonly
                                function password_element_readonly(type) {
                                    switch(type) {
                                        // !readonly
                                        case 0:
                                            // !readOnly
                                            if(element_input_password !== null)
                                                element_input_password.readOnly = false;
                                                element_input_password.value = null;
                                            
                                            // unlock
                                            password_element_html(1);
                                            break;
                                        // readonly
                                        case 1:
                                            // readOnly
                                            if(element_input_password !== null)
                                                element_input_password.readOnly = true;
                                            
                                            // unlock
                                            password_element_html(1);
                                            break;
                                    }
                                }

                                // show - write
                                password_element_html(0);
                                password_element_html(1);

                                // readonly
                                // !null
                                if(element_input_password !== null) {
                                    // readOnly
                                    element_input_password.readOnly = true;
                                    
                                    // unlock
                                    password_element_html(1);
                                }

                                function togglePasswordShow()
                                {
                                    // null
                                    if(element_input_password === null)
                                        return false;
                                
                                    // !password
                                    if(element_input_password.type !== 'password') {
                                      element_input_password.type = 'password';
                                    
                                        // password show
                                        password_element_html(0);
                                    }
                                    else {
                                        // text
                                        element_input_password.type = 'text';
                                    
                                        // password hide
                                        password_element_html(0);
                                    }
                                }

                                function togglePasswordWrite()
                                {
                                    // null
                                    if(element_input_password === null)
                                        return false;
                                
                                    // readonly
                                    if(!element_input_password.readOnly)
                                        password_element_readonly(1);
                                    else
                                        password_element_readonly(0);
                                }
                            </script>
                        ";
                        break;
                    // sadece özelleştirme modu için
                    case 4:
                        // elementleri toplu halde tutacak olan geçici dizi
                        $temp_list_element_available_customization_mod = null;

                        // dilleri elementi içine aktarma
                        foreach($ARRAY_AVAILABLE_CUSTOMIZATION_MODS as $indexAvaiCustomMod => $AvailableCustomMod) {
                            // şuanki kullanılan dil değeri otomatik null
                            $active_custommod = null;
                        
                            // mod id'sini alma
                            $temp_custom_mod_id = $AvailableCustomMod;
                        
                            // eğer dil oturum diline eşitse otomatik onu seçilmiş yapması için
                            // oturumdaki dil kodunu ve kısaltması ile kıyaslıyoruz
                            if($temp_custom_mod_id == $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_customization_mod))
                            {
                                // oturum dili bulundu
                                // html otomatik seçmesi için selected yapıyoruz
                                $active_custommod = "selected";
                            }
                        
                            $temp_list_element_available_customization_mod = $temp_list_element_available_customization_mod .
                            "<option name='$DataUser[$array_num_name]' value='$temp_custom_mod_id' $active_custommod>$temp_custom_mod_id</option>";
                        }

                         // elemenları elementin içine aktarma
                         $dynamicElement = "
                         <select name='$DataUser[$array_num_name]'>$temp_list_element_available_customization_mod</select>
                        ";
                        break;
                }

                // bazı verilere göre minik dinamiklik değişiklik olabilir
                echo "
                    <div id='account-settings-text-area' class='account-settings-text-area'>
                        <div id='account-settings-text-header' class='account-settings-text-header'>
                            <p id='account-settings-text-header-title' class='account-settings-text-header-title'>
                                <span>$DataUser[$array_num_text]<span>
                            </p>
                        </div>
                        <div id='account-settings-text-body' class='account-settings-text-body'>
                            $dynamicElement
                        </div>
                    </div>
                ";
            }

            // form gönderme ve değişiklikleri silme
            $textSubmitBtn = $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__form_send);
            $nameSubmitBtn = "change-user-submit-data";

            $textResetBtn = $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__form_clear);
            $nameResetBtn = "change-user-reset-data";

            echo "
                <div id='account-settings-change-area' class='account-settings-change-area'>
                    <input type='submit' name='$nameSubmitBtn' value='$textSubmitBtn' title='$textSubmitBtn'
                    id='change-user-submit' class='change-user-submit'/>

                    <input type='reset' name='$nameResetBtn' value='$textResetBtn' title='$textResetBtn'
                    id='change-user-reset' class='change-user-submit'/>
                </div>
            ";
        ?>
    </form>
</section>
<!-- MESSAGE JS -->
<script nonce type="application/javascript" src="component/tool/message/message-system.js"></script>
<!-- UPDATE VALUE JS -->
<script nonce type="application/javascript">
    function updateValue(element, value) {
        element.setAttribute("value", value);
    }
</script>

<?php
// sayfa veri gönderimi
switch($_SERVER["REQUEST_METHOD"] == "POST") {
    // veri gönderimi başarılı
    case 1:
        $REFRESH_PAGE = false; // sayfa yenileme pasif

        // girilen verileri diziye almak
        $ARRAY_DATA_USER_NEWDATA = [
            isset($ARRAY_DATA_USER[$array_user_num_username][$array_num_sessiondata]) ? ($ARRAY_DATA_USER[$array_user_num_username][$array_num_sessiondata]) : null,
            isset($_POST[$ARRAY_DATA_USER[$array_user_num_firstname][$array_num_name]]) ? ($_POST[$ARRAY_DATA_USER[$array_user_num_firstname][$array_num_name]]) : null,
            isset($_POST[$ARRAY_DATA_USER[$array_user_num_lastname][$array_num_name]]) ? ($_POST[$ARRAY_DATA_USER[$array_user_num_lastname][$array_num_name]]) : null,
            isset($_POST[$ARRAY_DATA_USER[$array_user_num_email][$array_num_name]]) ? ($_POST[$ARRAY_DATA_USER[$array_user_num_email][$array_num_name]]) : null,
            isset($_POST[$ARRAY_DATA_USER[$array_user_num_rank][$array_num_name]]) ? ($_POST[$ARRAY_DATA_USER[$array_user_num_rank][$array_num_name]]) : null,
            isset($_POST[$ARRAY_DATA_USER[$array_user_num_language][$array_num_name]]) ? ($_POST[$ARRAY_DATA_USER[$array_user_num_language][$array_num_name]]) : null,
            isset($_POST[$ARRAY_DATA_USER[$array_user_num_customization_mod][$array_num_name]]) ? ($_POST[$ARRAY_DATA_USER[$array_user_num_customization_mod][$array_num_name]]) : null,
            isset($_POST[$ARRAY_DATA_USER[$array_user_num_password][$array_num_name]]) ? ($_POST[$ARRAY_DATA_USER[$array_user_num_password][$array_num_name]]) : null
        ];

        ///////////////////////////////
        // PASSWORD
        ///////////////////////////////
        switch(!AutoImportGlobal::VerifyPassword($ARRAY_DATA_USER_NEWDATA[$array_user_num_password], SessionManager::get(SessionManager::$session_var_password)) && !empty($ARRAY_DATA_USER_NEWDATA[$array_user_num_password])) {
            // kayıtlı şifre ile aynı şifre girilmiş ya da boş
            case 0:
                // değer girilip girilmediğini kontrol etme
                switch(!empty($ARRAY_DATA_USER_NEWDATA[$array_user_num_password])) {
                    // kayıtlı şifre ile aynı
                    case 1:
                        // uyarı mesajı çıktısı verme
                        $tempTitleMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_same_stored_data);
                        $tempDescMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_must_be_different);
                        $tempInfoMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__warning);
                        $tempTypeMsg = 3;

                        echo "
                        <!-- PASSWORD SAME AS STORED MESSAGE JS -->
                        <script nonce>
                            MessageType('$tempTitleMsg', '$tempDescMsg', '$tempInfoMsg', $tempTypeMsg);
                        </script>
                        ";
                        break;
                }
                break;
            // farklı şifre girilmiş
            case 1:
                $REFRESH_PAGE = true; // sayfa yenileme aktif

                switch($Account->validateSelected(Account::$validate_password, $ARRAY_DATA_USER_NEWDATA[$array_user_num_password])) {
                    // şifre kullanılamaz
                    case 0:
                        $tempTitleMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_entered_value_not_usuable);
                        $tempDescMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_invalid_value);
                        $tempInfoMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__warning);
                        $tempTypeMsg = 3;

                        // uyarı çıktısı ver
                        echo "
                        <script nonce>
                            MessageType('$tempTitleMsg', '$tempDescMsg', '$tempInfoMsg', $tempTypeMsg);
                        </script>
                        ";
                    break;
                    // şifre kullanılabilir
                    case 1:
                        // şifre güncelle
                        $AutoImportGlobal->UpdateUserData(
                            AutoImportGlobal::$var_update_password,
                            $ARRAY_DATA_USER_NEWDATA[$array_user_num_password],
                            SessionManager::get(SessionManager::$session_var_id)
                        );

                        // başarı mesajı çıktısı verme
                        $tempTitleMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_password_updated_successfully);
                        $tempDescMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_not_problem_found_on_value);
                        $tempInfoMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__success);
                        $tempTypeMsg = 1;

                        echo "
                        <!-- PASSWORD CHANGED SUCCESS MESSAGE JS -->
                        <script nonce>
                            MessageType('$tempTitleMsg', '$tempDescMsg', '$tempInfoMsg', $tempTypeMsg);
                        </script>
                        ";

                        // ekrandaki veriyi güncelleme
                        $html_input_name = $ARRAY_DATA_USER[$array_user_num_password][$array_num_name];
                        $html_text = $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_password);

                        echo "
                        <script nonce>
                            updateValue(document.querySelector('[name=$html_input_name]') , '$html_text');
                        </script>
                        ";
                    break;
                }
            break;
        }


        ///////////////////////////////
        // LANGUAGE
        ///////////////////////////////
        switch($ARRAY_DATA_USER_NEWDATA[$array_user_num_language] !== SessionManager::get(SessionManager::$session_var_language_short) && !empty($ARRAY_DATA_USER_NEWDATA[$array_user_num_language])) {
            // farklı dil girimiş
            case 1:
                // alınan değerlerle kaç tane sonuç bulunduğunu sayma
                $tempCountResult = $AutoImportGlobal->getCount(
                    ConfigTable::$table_e,
                    ConfigTable::$column_table_e_0,
                    [ConfigTable::$column_table_e_0, ConfigTable::$column_table_e_2],
                    [SessionManager::get(SessionManager::$session_var_language) ,$ARRAY_DATA_USER_NEWDATA[$array_user_num_language]]);

                switch($tempCountResult)
                {
                    // dil kullanılabilir
                    case 0:
                        $REFRESH_PAGE = true; // sayfa yenileme aktif

                        // seçilen dil'in id numarasını alma
                        $tempSelectedLangID = $AutoImportGlobal->getSelected(
                            [ConfigTable::$column_table_e_2],
                            [$ARRAY_DATA_USER_NEWDATA[$array_user_num_language]],
                            ConfigTable::$table_e
                        )[0][ConfigTable::$column_table_e_0];

                        // dil güncelle
                        $AutoImportGlobal->UpdateUserData(
                            AutoImportGlobal::$var_update_language,
                            $tempSelectedLangID,
                            SessionManager::get(SessionManager::$session_var_id)
                        );
                        
                        // oturumu güncelleme
                        $CheckerUser->CheckerControl($CheckerUser);

                        // başarı mesajı çıktısı verme
                        $tempTitleMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_language_updated_successfully);
                        $tempDescMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_not_problem_found_on_value);
                        $tempInfoMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__success);
                        $tempTypeMsg = 1;

                        echo "
                        <!-- LANGUAGE CHANGED SUCCESS MESSAGE JS -->
                        <script nonce>
                            MessageType('$tempTitleMsg', '$tempDescMsg', '$tempInfoMsg', $tempTypeMsg);
                        </script>
                        ";
                    break;
                }
            break;
        }

        ///////////////////////////////
        // MOD
        ///////////////////////////////
        switch($ARRAY_DATA_USER_NEWDATA[$array_user_num_customization_mod] !== SessionManager::get(SessionManager::$session_var_customization_mod) && count($ARRAY_AVAILABLE_CUSTOMIZATION_MODS) > 0) {
            // farklı mod girilmiş
            case 1:
                // kullanıcı bu moda sahip ya da değil kontrol edilmeli!
                // girilen değer dizide yoksa hata mesajı versin
                if(!in_array($ARRAY_DATA_USER_NEWDATA[$array_user_num_customization_mod] ,$ARRAY_AVAILABLE_CUSTOMIZATION_MODS)) {
                    // hata mesajı çıktısı
                    $tempTitleMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_do_not_try_modify_data);
                    $tempDescMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_system_security_alert);
                    $tempInfoMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__error);
                    $tempTypeMsg = 2;

                    echo "
                    <!-- DONT' TRY TO MODIFY THE DATA MESSAGE JS -->
                    <script nonce>
                        MessageType('$tempTitleMsg', '$tempDescMsg', '$tempInfoMsg', $tempTypeMsg);
                    </script>
                    ";

                    // hatalı dönsün
                    return false;
                }

                // sayfa yenileme aktif
                $REFRESH_PAGE = true;

                // mod güncelle
                $AutoImportGlobal->UpdateUserData(
                    AutoImportGlobal::$var_update_customization_mod,
                    $ARRAY_DATA_USER_NEWDATA[$array_user_num_customization_mod],
                    SessionManager::get(SessionManager::$session_var_id)
                );

                // başarı mesajı çıktısı verme
                $tempTitleMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_customization_mod_updated_successfully);
                $tempDescMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_not_problem_found_on_value);
                $tempInfoMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__success);
                $tempTypeMsg = 1;

                echo "
                <!-- CUSTOMIZATION MOD CHANGED SUCCESS MESSAGE JS -->
                <script nonce>
                    MessageType('$tempTitleMsg', '$tempDescMsg', '$tempInfoMsg', $tempTypeMsg);
                </script>
                ";
            break;
            // kayıtlı mod ile aynı ya da boş
            default:
                // değer girilip girilmediğini kontrol etme
                switch($ARRAY_DATA_USER_NEWDATA[$array_user_num_customization_mod] === SessionManager::get(SessionManager::$session_var_customization_mod) && empty($ARRAY_DATA_USER_NEWDATA[$array_user_num_customization_mod]) == false) {
                    // kayıtlı şifre ile aynı
                    case 1:
                        // uyarı mesajı çıktısı verme
                        $tempTitleMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_same_stored_data);
                        $tempDescMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_must_be_different);
                        $tempInfoMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__warning);
                        $tempTypeMsg = 3;

                        echo "
                        <!-- CUSTOMIZATION MOD SAME AS STORED MESSAGE JS -->
                        <script nonce>
                            MessageType('$tempTitleMsg', '$tempDescMsg', '$tempInfoMsg', $tempTypeMsg);
                        </script>
                        ";
                    break;
                }
            break;
        }

        ///////////////////////////////
        // FIRSTNAME
        ///////////////////////////////
        switch($ARRAY_DATA_USER_NEWDATA[$array_user_num_firstname] !== SessionManager::get(SessionManager::$session_var_firstname) && !empty($ARRAY_DATA_USER_NEWDATA[$array_user_num_firstname])) {
            // farklı isim girilmiş
            case 1:
                switch($Account->validateSelected(Account::$validate_firstname, $ARRAY_DATA_USER_NEWDATA[$array_user_num_firstname])) {
                    // isim sorunlu ve değiştirilemez
                    case 0:
                        $tempTitleMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_entered_value_not_usuable);
                        $tempDescMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_invalid_value);
                        $tempInfoMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__warning);
                        $tempTypeMsg = 3;

                        // uyarı çıktısı ver
                        echo "
                        <script nonce>
                            MessageType('$tempTitleMsg', '$tempDescMsg', '$tempInfoMsg', $tempTypeMsg);
                        </script>
                        ";
                    break;
                    // isim değiştirilebilir
                    case 1:
                        // isim güncelle
                        $AutoImportGlobal->UpdateUserData(
                            AutoImportGlobal::$var_update_firstname,
                            $ARRAY_DATA_USER_NEWDATA[$array_user_num_firstname],
                            SessionManager::get(SessionManager::$session_var_id)
                        );

                        // oturumu güncelleme
                        $CheckerUser->CheckerControl($CheckerUser);

                        // başarı mesajı çıktısı verme
                        $tempTitleMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_firstname_updated_successfully);
                        $tempDescMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_not_problem_found_on_value);
                        $tempInfoMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__success);
                        $tempTypeMsg = 1;

                        echo "
                        <!-- FIRSTNAME CHANGED SUCCESS MESSAGE JS -->
                        <script nonce>
                            MessageType('$tempTitleMsg', '$tempDescMsg', '$tempInfoMsg', $tempTypeMsg);
                        </script>
                        ";

                        // ekrandaki veriyi güncelleme
                        $html_input_name = $ARRAY_DATA_USER[$array_user_num_firstname][$array_num_name];
                        $html_text = $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_firstname);

                        echo "
                        <script nonce>
                            updateValue(document.querySelector('[name=$html_input_name]') , '$html_text');
                        </script>
                        ";
                    break;
                }
                break;
        }

        ///////////////////////////////
        // LASTNAME
        ///////////////////////////////
        switch($ARRAY_DATA_USER_NEWDATA[$array_user_num_lastname] !== SessionManager::get(SessionManager::$session_var_lastname) && !empty($ARRAY_DATA_USER_NEWDATA[$array_user_num_lastname])) {
            // farklı soyisim girilmiş
            case 1:
                switch($Account->validateSelected(Account::$validate_lastname, $ARRAY_DATA_USER_NEWDATA[$array_user_num_lastname])) {
                    // soyisim sorunlu ve değiştirilemez
                    case 0:
                        $tempTitleMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_entered_value_not_usuable);
                        $tempDescMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_invalid_value);
                        $tempInfoMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__warning);
                        $tempTypeMsg = 3;

                        // uyarı çıktısı ver
                        echo "
                        <script nonce>
                            MessageType('$tempTitleMsg', '$tempDescMsg', '$tempInfoMsg', $tempTypeMsg);
                        </script>
                        ";
                    break;
                    // soyisim değiştirilebilir
                    case 1:
                        // isim güncelle
                        $AutoImportGlobal->UpdateUserData(
                            AutoImportGlobal::$var_update_lastname,
                            $ARRAY_DATA_USER_NEWDATA[$array_user_num_lastname],
                            SessionManager::get(SessionManager::$session_var_id)
                        );

                        // oturumu güncelleme
                        $CheckerUser->CheckerControl($CheckerUser);

                        // başarı mesajı çıktısı verme
                        $tempTitleMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_lastname_updated_successfully);
                        $tempDescMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_not_problem_found_on_value);
                        $tempInfoMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__success);
                        $tempTypeMsg = 1;

                        echo "
                        <!-- LASTNAME CHANGED SUCCESS MESSAGE JS -->
                        <script nonce>
                            MessageType('$tempTitleMsg', '$tempDescMsg', '$tempInfoMsg', $tempTypeMsg);
                        </script>
                        ";

                        // ekrandaki veriyi güncelleme
                        $html_input_name = $ARRAY_DATA_USER[$array_user_num_lastname][$array_num_name];
                        $html_text = $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_lastname);

                        echo "
                        <script nonce>
                            updateValue(document.querySelector('[name=$html_input_name]') , '$html_text');
                        </script>
                        ";
                    break;
                }
                break;
        }

        ///////////////////////////////
        // EMAIL
        ///////////////////////////////
        switch($ARRAY_DATA_USER_NEWDATA[$array_user_num_email] !== SessionManager::get(SessionManager::$session_var_email) && !empty($ARRAY_DATA_USER_NEWDATA[$array_user_num_email])) {
            // farklı email girimiş
            case 1:
                switch($Account->validateSelected(Account::$validate_email, $ARRAY_DATA_USER_NEWDATA[$array_user_num_email])) {
                    // email zaten kullanımda!
                    case 0:
                        $tempTitleMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_email_already_use);
                        $tempDescMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_value_already_use);
                        $tempInfoMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__warning);
                        $tempTypeMsg = 3;

                        // uyarı çıktısı ver
                        echo "
                        <script nonce>
                            MessageType('$tempTitleMsg', '$tempDescMsg', '$tempInfoMsg', $tempTypeMsg);
                        </script>
                        ";
                    break;
                    // email oluşturulabilir
                    case 1:
                        // email güncelle
                        $AutoImportGlobal->UpdateUserData(
                            AutoImportGlobal::$var_update_email,
                            $ARRAY_DATA_USER_NEWDATA[$array_user_num_email],
                            SessionManager::get(SessionManager::$session_var_id)
                        );
                        
                        // oturumu güncelleme
                        $CheckerUser->CheckerControl($CheckerUser);

                        // başarı mesajı çıktısı verme
                        $tempTitleMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_email_updated_successfully);
                        $tempDescMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__text_not_problem_found_on_value);
                        $tempInfoMsg = AutoImportGlobal::getLangData(LanguageSupport::$lang_key__success);
                        $tempTypeMsg = 1;

                        echo "
                        <!-- EMAIL CHANGED SUCCESS MESSAGE JS -->
                        <script nonce>
                            MessageType('$tempTitleMsg', '$tempDescMsg', '$tempInfoMsg', $tempTypeMsg);
                        </script>
                        ";

                        // ekrandaki veriyi güncelleme
                        $html_input_name = $ARRAY_DATA_USER[$array_user_num_email][$array_num_name];
                        $html_text = $AutoImportGlobal->getSession(AutoImportGlobal::$var_session_email);

                        echo "
                        <script nonce>
                            updateValue(document.querySelector('[name=$html_input_name]') , '$html_text');
                        </script>
                        ";
                    break;
                }
            break;
        }

    // sayfa yenileme
    if($REFRESH_PAGE) {
        echo "
        <!-- REFRESH PAGE JS -->
        <script nonce>
            setTimeout(function(){
                window.location.href = '';
            }, 2400);
        </script>
        ";
    }
    break;
}
?>