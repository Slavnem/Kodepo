<?php
// BASE DIRECTORY
define("BASE_DIRECTORY", "/var/www/kodepo");

// PAGES
define("PAGE_INDEX", "index");
define("PAGE_HOMEPAGE", "homepage");
define("PAGE_CODES", "codes");
define("PAGE_USERS", "users");
define("PAGE_ACCOUNT_SETTINGS", "account");
define("PAGE_DATA_ADD", "projects");
define("PAGE_LOGIN", "login");
define("PAGE_REGISTER", "register");
define("PAGE_LOGOUT", "logout");

// SUB PAGES (USERS)

// SUB PAGES (ACCOUNT SETTINGS)
define("SUB_PAGE_ACCSETT_MAIN", "design/page/account-settings/account-settings-main.php");
define("SUB_PAGE_ACCSETT_COMPONENT__TOOL_ACCSETT_USER", "design/page/account-settings/component-tool/account-settings-user.php");

// SUB PAGES (DATA ADD)
define("SUB_PAGE_DATADD_NAVIGATION", "design/page/data-add/data-add-navigation.php");
define("SUB_PAGE_DATADD_CREATE_PROJECT", "design/page/data-add/data-add-create-project.php");
define("SUB_PAGE_DATADD_COMPONENT__TOOL_CREATE_PROJECT", "design/page/data-add/component-tool/data-add-create-project.php");

// PAGES GET DATA VARIABLE NAME
// CODES


// USERS
define("USERS_USER_DETAIL_SHORT", "user");
define("USERS_USER_PROJECTID_SHORT", "project");
define("USERS_USER_PROJECT_PAGE_NUM", "projectpage");

// PAGES GET DATA VARIABLE NAME AFTER DEFINE
// AFTER DEFINE CODES
define("CODES_CODE_DETAIL_SHORT", USERS_USER_PROJECTID_SHORT);

// STATIC PAGE
class StaticPage {
    // INDEX
    public static $ARRAY_INDEX = [
        // INDEX PAGES
        PAGE_INDEX
    ];

    // ONLY USER
    public static $ARRAY_USER_MANDATORY = [
        // MAIN PAGES
        PAGE_USERS,
        PAGE_ACCOUNT_SETTINGS,
        PAGE_DATA_ADD,
        PAGE_LOGOUT,
        // SUB PAGES (USERS)
        // SUB PAGES (ACCOUNT SETTINGS)
        SUB_PAGE_ACCSETT_MAIN,
        SUB_PAGE_ACCSETT_COMPONENT__TOOL_ACCSETT_USER,
        // SUB PAGES (DATA ADD)
        SUB_PAGE_DATADD_NAVIGATION,
        SUB_PAGE_DATADD_CREATE_PROJECT,
        SUB_PAGE_DATADD_COMPONENT__TOOL_CREATE_PROJECT
    ];

    // USER & GUEST
    public static $ARRAY_GUEST_AND_USER = [
        PAGE_HOMEPAGE,
        PAGE_CODES,
        PAGE_LOGIN,
        PAGE_REGISTER
    ];
}
?>