<?php
require_once('common.php');

$service = $JasperSoapClientFactory->Echo_();

$wrapper = new CCClientRequest();
$wrapper->value = "Hello World";

try {
  $result = $service->Echo($wrapper);
  var_dump($result);
} catch (SoapFault $e) {
  var_dump($e);
}
