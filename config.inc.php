<?php

if (file_exists("secret/db.inc.php")) {
    require_once("secret/db.inc.php");
} else {
    define("DB_USER", getenv("DB_USER"));
    define("DB_PASS", getenv("DB_PASS"));
    define("DB_NAME", getenv("DB_NAME"));
    define("DB_HOST", getenv("DB_HOST"));
}
define("DB_TYPE",                            "mysqli");
define("APP_NAME",                           "Example Application");
define("VERSION",                            "1.0");
define("__DEBUG__",                          FALSE);
define("__PCONNECT__",                       TRUE); // Persistent connection to the database
define("DSN",                                DB_TYPE."://".DB_USER.":".DB_PASS."@".DB_HOST."/".DB_NAME);

define("ROOT",                               dirname(__FILE__));
define("CLASSES_PATH",                       ROOT . "/classes/");
define("LIBS_PATH",                          ROOT . "/lib/");
define("TPL_PATH",                           ROOT . "/tpl/");
define("TPL_CACHE_PATH",                     ROOT . "/tpl_cache/");
define("MODS_PATH",                          ROOT . "/modules/");
define("IMAGES_PATH",                        ROOT . "/images/");

define("DATE_FORMAT",                        "d.m.Y");
define("ITEMS_PER_PAGE",                     13);

require_once LIBS_PATH . "functions.lib.php";

require_class("skel");

include_once(LIBS_PATH . "runtime.php");


// ---------------------
// Disabling Magic Quotes
// ---------------------

if (get_magic_quotes_gpc()) {
    function stripslashes_deep($value)
    {
        $value = is_array($value) ?
                    array_map('stripslashes_deep', $value) :
                    stripslashes($value);
 
        return $value;
    }
 
    $_POST = array_map('stripslashes_deep', $_POST);
    $_GET = array_map('stripslashes_deep', $_GET);
    $_COOKIE = array_map('stripslashes_deep', $_COOKIE);
 }


 ?>