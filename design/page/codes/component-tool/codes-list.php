<?php
define("MAX_CODE_LIST_LIMIT", 5);
define("MAX_CODE_PROJECTS_LIST_LIMIT", 5);
define("MAX_CODE_PROJECT_PAGE_LIST_LIMIT", 3);
define("HREF_CSS_CODES_LIST", "design/style/css/page/codes/component-tool/codes-list.css");
define("HREF_CSS_CODES_LIST_AND_DETAILS", "design/style/css/page/codes/component-tool/codes-list-and-details.css");
?>
<!-- CODES LIST CSS -->
<link rel="stylesheet" type="text/css" href="<?php echo HREF_CSS_CODES_LIST; ?>"/>
<!-- CODES LIST AND DETAILS -->
<link rel='stylesheet' type='text/css' href="<?php echo HREF_CSS_CODES_LIST_AND_DETAILS; ?>"/>
<!-- CODES LIST AREA -->
<section id="codes-list-area" class="codes-list-area">
    <?php
        // kodları listeleyen ya da
        // belirli bir projeyi kodlarıyla birlikte
        // göstermeyi sağlayan dosya
        include("codes-list-and-details.php");
    ?>
</section>