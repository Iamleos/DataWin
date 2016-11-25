<?php
error_reporting(E_ALL ^ E_NOTICE);
require_once './src/QcloudApi/QcloudApi.php';

$config = array(
                'SecretId'       => 'AKIDqWWvnE7LjI6RKwCItQJucvJaYpAzY6BW',
                'SecretKey'      => 'emBnTWsMp1KnV44W9dPhOVm20aysv9HH',
                'RequestMethod'  => 'POST',
                'DefaultRegion'  => 'gz');

$wenzhi = QcloudApi::load(QcloudApi::MODULE_WENZHI, $config);

$package = array("content"=>"双万兆服务器就是好，只是内存小点");

$a = $wenzhi->TextSentiment($package);

if ($a === false) {
    $error = $wenzhi->getError();
    echo "Error code:" . $error->getCode() . ".\n";
    echo "message:" . $error->getMessage() . ".\n";
    echo "ext:" . var_export($error->getExt(), true) . ".\n";
} else {
    var_dump($a);
}

//echo "\nRequest :" . $wenzhi->getLastRequest();
//echo "\nResponse :" . $wenzhi->getLastResponse();
//echo "\n";