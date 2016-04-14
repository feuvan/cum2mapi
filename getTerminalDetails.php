<?php
//php getTerminalDetails.php 89860615010012345678 89860615010012345679

require_once('common.php');

$service = $JasperSoapClientFactory->Terminal();

$wrapper = new CCClientRequest();
$wrapper->iccids = new stdClass();

if (PHP_SAPI == 'cli') {
  $iccids = $argv;
  array_shift($iccids);
} else {
  $iccids = $_GET['iccids'];
}
$i = 0;
foreach ($iccids as $k => $v) {
  $wrapper->iccids->iccid[$i] = $v;
  $i++;
}

try{
  $result = $service->GetTerminalDetails($wrapper);
  var_dump($result);
} catch (SoapFault $e) {
  var_dump($e);
}
