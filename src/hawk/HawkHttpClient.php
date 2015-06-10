<?php

namespace mssdk\hawk;

require 'Curl.php';
require 'Hawk.php';

class HawkHttpClient extends Curl {

	private $key;
	private $secret;

	public function __construct($key, $secret) {
		parent::__construct();
		$this->key = $key;
		$this->secret = $secret;			
		$this->beforeSend(function() {
			$this->setHeader("Authorization", $this->generateHawkHeader());
			$this->setHeader("Accept", "application/xhtml-form+xml");
		});
	}

	private function generateHawkHeader() {
		$method = $this->getOpt(CURLOPT_CUSTOMREQUEST);
		return Hawk::generateHeader($this->key, $this->secret, $method, $this->url);
	}
	

}

?>