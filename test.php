--TEST--
Test cross validation 
--SKIPIF--
<?php
if (!extension_loaded('svm')) die('skip');
?>
--FILE--
<?php      
include "my_svm.php";
$svm = new EasyPhpSvm();
$svm->debug=true;
$svm->setOptions(array(SVM::OPT_C=>16,SVM::OPT_GAMMA=>0.0078125));
//print_r($svm->getOptions());exit;
$arrayData = $svm->fileData2Array(dirname(__FILE__) . '/data/a5a.small');
$result = $svm->crossvalidate($arrayData,4);
//$result = $svm->gridSearchCG($arrayData);
print_r($result);
if($result > 0) {
	echo "ok";
}
?>
--EXPECT--
ok
