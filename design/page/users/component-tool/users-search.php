<?php
define("MAX_USER_LIST_LIMIT", 5);
define("MAX_USER_PROJECTS_LIST_LIMIT", 5);
define("MAX_USER_PROJECT_PAGE_LIST_LIMIT", 3);
define("HREF_CSS_USERS_SEARCH_AREA", "design/style/css/page/users/component-tool/users-search.css");
define("HREF_CSS_USERS_USER_DETAILS", "design/style/css/page/users/component-tool/users-user-details.css");

$name_search_input = "users-search-input";
$name_search_submit = "users-search-submit";
?>
<!-- USERS SEARCH AREA CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo HREF_CSS_USERS_SEARCH_AREA; ?>"/>
<!-- USER DETAILS CSS -->
<link rel='stylesheet' type='text/css' href="<?php echo HREF_CSS_USERS_USER_DETAILS; ?>"/>
<!-- USERS SEARCH AREA -->
<section id="users-search-area" class="users-search-area">
    <form method="POST" id="users-search-form" class="users-search-form">
        <div id="users-searchbar-area" class="users-searchbar-area">
            <input type="text" name="<?php echo $name_search_input; ?>"
            id="users-search-input" class="users-search-input"
            placeholder="<?php echo $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__text_search); ?>"/>
        
            <button name="<?php echo $name_search_input; ?>"
            id="users-search-submit" class="users-search-submit"
            title="<?php echo $AutoImportGlobal->getLangData(LanguageSupport::$lang_key__text_search); ?>">
                <?php echo StaticSvg::$SVG_user_search; ?>
                <input type="submit" value=" ">
            </button>
        </div>
    </form>
    <?php
        // eğer sayfada bilgi alış verişi yoksa
        // güzel bir arkaplan gibi duran sayfa ekleme
        switch($_SERVER["REQUEST_METHOD"] == "POST") {
            // post ile form gönderme var
            case 1:
                // GET verisi boş değilse temizleme
                if(isset($_GET[USERS_USER_DETAIL_SHORT]) ? $_GET[USERS_USER_DETAIL_SHORT] : null != null) {
                    $_GET[USERS_USER_DETAIL_SHORT] = null;
                }

                // eğer kullanıcının girmesi için bir yazı alanı yoksa
                // ya a kullanıcının girdiği metinden boşluklar
                // silindiğinde boş kalmış ise boş dönsün
                if(!isset($_POST[$name_search_input]) || trim($_POST[$name_search_input]) === "") {
                    return false;
                }

                // metin alanı tanımlı ve boş değil
                // girilen metini al
                $inputedSearchText = htmlspecialchars($_POST[$name_search_input], ENT_QUOTES, 'UTF-8');

                // saymamızı sağlayacak olan fonksiyonu bulunduran sınıf objesi
                $toolkit_user = new ToolkitUser($databaseconn);
            
                // kullanıcıları sorguyla almak
                $TOTAL_COUNT_USERS = $toolkit_user->countonly(ConfigTable::$table_a, "*");
                $PAGE_LIMIT = (int)($TOTAL_COUNT_USERS / MAX_USER_LIST_LIMIT);
                $ARRAY_USERS = $toolkit_user->limitwithorderby(
                    ConfigTable::$table_a,
                    [ConfigTable::$column_table_a_1], // username
                    [$inputedSearchText], // query username
                    [
                        ConfigTable::$column_table_a_0, // user id
                        ConfigTable::$column_table_a_1, // username
                        ConfigTable::$column_table_a_4, // first name
                        ConfigTable::$column_table_a_5, // last name
                        ConfigTable::$column_table_a_6, // rank
                        ConfigTable::$column_table_a_7 // register
                    ],
                    MAX_USER_LIST_LIMIT,
                    0,
                    [
                        ConfigTable::$column_table_a_6, // rank
                        ConfigTable::$column_table_a_7 // register
                    ],
                    [
                        "DESC", // rank
                        "ASC" // register
                    ]
                );

                // Döngü ile bunları eklemek
                // test aşaması

                // ana bir div oluşturmak
                echo "<div class='users-list-area'>";

                // kullanıcıları döngü halinde ekleme
                foreach($ARRAY_USERS as $indexUser => $User) {
                    $tempuser_userid = $User[ConfigTable::$column_table_a_0];
                    $tempuser_username = $User[ConfigTable::$column_table_a_1];
                    $tempuser_user_firstname = $User[ConfigTable::$column_table_a_4];
                    $tempuser_user_lastname = $User[ConfigTable::$column_table_a_5];
                    $tempuser_user_rank = $User[ConfigTable::$column_table_a_6];
                    $tempuser_user_register = $User[ConfigTable::$column_table_a_7];
                    $temp_redirect = "/" . PAGE_USERS ."?" . USERS_USER_DETAIL_SHORT . "=$tempuser_username";

                    $tempuser_svg_rank = StaticSvg::$SVG_ranking_star;
                    $tempuser_svg_register = StaticSvg::$SVG_calendar_true;

                    // html çıktısı vermek
                    echo "
                        <div class='users-list' onclick='window.location.href=\"$temp_redirect\"'>
                            <div class='users-user-info-textarea'>
                                <h2 class='user-info-title'>$tempuser_username</h2>
                            </div>
                            <div class='users-user-info-textarea user-info-names'>
                                <p class='user-info-firstname'>$tempuser_user_firstname</p>
                                <p class='user-info-lastname'>$tempuser_user_lastname</p>
                            </div>
                            <div class='users-user-info-textarea user-info-others'>
                                <span class='user-info-rank'>$tempuser_svg_rank $tempuser_user_rank</span>
                                <span class='user-info-register'>$tempuser_svg_register $tempuser_user_register</span>
                            </div>
                        </div>
                    ";
                }

                // ana bir div sonlandırmak
                echo "</div>";
                break;
        }

        // aramak için kullanıcı adı girilmişse ona göre işlem yapma
        require_once("users-user-details.php");
    ?>
</section>