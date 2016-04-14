<?php
$JASPER_URI = 'http://api.jasperwireless.com/ws/schema';

require_once('config.php');

class JasperSoapClientFactory {
	
	private $wsdl = array(
		'Echo' => 'ws/Echo.wsdl',//'http://api.10646.cn/ws/schema/Echo.wsdl';
		'Terminal' => 'ws/Terminal.wsdl',
		);
	private function client($wsdlUrl) {
		return new SoapClient($wsdlUrl, array('trace' => TRUE, 'features' => SOAP_SINGLE_ELEMENT_ARRAYS));
	}
	public function Echo_() {
		return $this->client($this->wsdl['Echo']);
	}
	public function Terminal() {
		global $CONFIG;
		$c = $this->client($this->wsdl['Terminal']);
		$Security = new WsseAuthHeader($CONFIG['username'], $CONFIG['password']);
		$c->__setSoapHeaders($Security);
		return $c;
	}
}

$JasperSoapClientFactory = new JasperSoapClientFactory();

class WsseAuthHeader extends SoapHeader {
    private $wss_ns = 'http://docs.oasis-open.org/wss/2004/01/oasis-200401-wss-wssecurity-secext-1.0.xsd';
    
    function __construct($user, $pass, $ns = null) {
        if ($ns) {
            $this->wss_ns = $ns;
        }
    
        $auth = new stdClass();
        $auth->Username = new SoapVar($user, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns); 
        $auth->Password = new SoapVar($pass, XSD_STRING, NULL, $this->wss_ns, NULL, $this->wss_ns);
    
        $username_token = new stdClass();
        $username_token->UsernameToken = new SoapVar($auth, SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'UsernameToken', $this->wss_ns); 
    
        $security_sv = new SoapVar(
            new SoapVar($username_token, SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'UsernameToken', $this->wss_ns),
            SOAP_ENC_OBJECT, NULL, $this->wss_ns, 'Security', $this->wss_ns);
        parent::__construct($this->wss_ns, 'Security', $security_sv, true);
    }
}

/*
 * Control Center API client request
 */
class CCClientRequest {
	public $messageId;
	public $version;
	function __construct($messageId = null, $version = "5.90", $licenseKey = null) {
		global $CONFIG;
		$this->messageId = isset($messageId) ? $messageId : microtime();
		$this->version = $version;
		$this->licenseKey = isset($licenseKey) ? $licenseKey : $CONFIG['licenseKey'];
	}
}
