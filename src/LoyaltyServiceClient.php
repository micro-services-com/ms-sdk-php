<?php

namespace mssdk;

require 'hawk/HawkHttpClient.php';

use \mssdk\hawk\HawkHttpClient;

class LoyaltyServiceClient {

	const BASEURL = "https://service-loyalty.micro-services.com";
	const URITEMPLATE_ACTIVITIES = self::BASEURL . "/applications/{applicationId}/activities";

	const COOKIENAME_AMBASSADORID = "myLAMBId";
	const COOKIENAME_BROWSERID = "myLBWRId";

	private $applicationId;
	private $key;
	private $secret;

	public function __construct($applicationId, $key, $secret) {
		$this->applicationId = $applicationId;
		$this->key = $key;
		$this->secret = $secret;
	}

	public function validateBrowserId($browserId) {
		if(isset($browserId)) {
			$pattern='/\"?(BWRID\-[a-zA-Z0-9-]+)\"?/m';
			if (preg_match($pattern, $browserId, $match)) {
				return $match[1];
			}
		}
		return null;
	}

	public function trackBrowserActivity($browserId, $activityTypeName, $description, $autoapprove = false) {
		$url = str_replace("{applicationId}", $this->applicationId, self::URITEMPLATE_ACTIVITIES);
		$hawkHttpClient = new HawkHttpClient($this->key, $this->secret);
		$hawkHttpClient->post($url, array(
			"idType" => "BROWSERID",
            "id" => $browserId,
            "activityTypeName" => $activityTypeName,
            "description" => $description,
            "autoApprove" => $autoapprove ? "on" : null
		));
		return $this->createResultObject($hawkHttpClient);
	}

	public function validateAmbassadorId($ambassadorId) {
		if(isset($ambassadorId)) {
			$pattern='/\"?([A-Z0-9]{6,})\"?/m';
			if (preg_match($pattern, $ambassadorId, $match)) {
				return $match[1];
			}
		}
		return null;
	}

	public function trackAmbassadorActivity($ambassadorId, $description, $autoapprove = false) {
		$url = str_replace("{applicationId}", $this->applicationId, self::URITEMPLATE_ACTIVITIES);
		$hawkHttpClient = new HawkHttpClient($this->key, $this->secret);
		$hawkHttpClient->post($url, array(
			"idType" => "AMBASSADORID",
            "id" => $ambassadorId,
            "activityTypeName" => "AMBASSADOR",
            "description" => $description,
            "autoApprove" => $autoapprove ? "on" : null
		));
		return $this->createResultObject($hawkHttpClient);
	}

	private function createResultObject($hawkHttpClient) {
		if($hawkHttpClient->error) {
			return $this->createErrorResultObject($hawkHttpClient);
		}
		// ok result
		return array(
			"error" => false,
			"status" => $hawkHttpClient->http_status_code,
			"response" => $hawkHttpClient->response
		);
	}

	private function createErrorResultObject($hawkHttpClient) {
		return array(
			"error" => true,
			"status" => $hawkHttpClient->http_status_code,
			"response" => $hawkHttpClient->response
		);			
	}

}

?>