<?
require __DIR__ . '/vendor/autoload.php';

define("ROOT", dirname(__FILE__));
define("TPL_PATH", ROOT . "/tpl/");
define("TPL_CACHE_PATH", ROOT . "/tpl_cache/");

$index_content = "";

$index = new HTML_Template_IT(TPL_PATH, TPL_CACHE_PATH);
$index->loadTemplateFile("index.tpl", TRUE, TRUE);
$index->setVariable("app_name", "Example Application");
$index->setVariable("content", $index_content);
$index->show();
?>
