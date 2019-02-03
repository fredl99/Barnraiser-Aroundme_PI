<?php

// ---------------------------------------------------------------------------------------------
// This file is part of AROUNDMe
// 
// Copyright (C) 2003-2008 Barnraiser
// http://www.barnraiser.org/
// info@barnraiser.org
// 
// This program is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
// 
// This program is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
// 
// You should have received a copy of the GNU General Public License
// along with this program; see the file COPYING.txt.  If not, see
// <http://www.gnu.org/licenses/>
// ---------------------------------------------------------------------------------------------

include_once 'OpenidCommon.class.php';

class OpenidConsumer extends OpenidCommon {

	var $optional_fields = array(); // here you should put nickname, email, etc... (no prefix of openid)
	var $required_fields = array(); // here you should put nickname, email, etc... (no prefix of openid)
	
	function OpenidConsumer() {
		$this->_openid_dh_modulus = OPENID_DH_MODULUS;
		$this->_openid_dh_gen = OPENID_DH_GEN;
	}
	
	// this sends post to a server
	function associate() {
	
		$data_to_send = array();
		$data_to_send['openid.mode'] = 'associate';
		$data_to_send['openid.assoc_type'] = 'HMAC-SHA1';
		$data_to_send['openid.session_type'] = 'DH-SHA1';
		$data_to_send['openid.dh_modulus'] = base64_encode($this->btwocEncode($this->_openid_dh_modulus)); // this is a 'real' prime number
		$data_to_send['openid.dh_gen'] = base64_encode($this->btwocEncode($this->_openid_dh_gen)); // this is a 'real' prime number
		$data_to_send['openid.dh_consumer_public'] = $this->dh_public(); // generate the consumer-public-dh-key
		
		$result = $this->_send($data_to_send);
		
		if ($result) { //print_r($result);
			$data_to_return = array(); 
			foreach(explode("\n", trim($result)) as $key => $r) {
				$tmp = explode(':', $r);
				if (isset($tmp[0], $tmp[1])) {
					$data_to_return[$tmp[0]] = $tmp[1]; // we need to store this in a smart way later...
				}
				else {
					return 0;
				}
			}
			
			if (isset($data_to_return['assoc_handle'])) {
				$_SESSION['openid_assoc_handle'] = $data_to_return['assoc_handle']; // do we really need this?
			}
			else {
				return 0; // failed to associate
			}

			// here we calculate the mac-key. 
			// We need to remember to do some checking if we got enc_mac_key or just mac_key from the server.
			$enc_mac_key = base64_decode($data_to_return['enc_mac_key']);
			$composite_key = bcpowmod($this->btwocDecode(base64_decode($data_to_return['dh_server_public'])), $_SESSION['openid_secret_key'], $this->_openid_dh_modulus);
			$sha1_composite_key = sha1($this->btwocEncode($composite_key), true);

			$mac_key = '';
			
			for ($i = 0; $i < strlen($enc_mac_key); $i++) {
				$mac_key .= chr(ord($enc_mac_key[$i]) ^ ord($sha1_composite_key[$i]));
			}

			$_SESSION['openid_mac_key'] = base64_encode($mac_key); // store the decrypted mac-key here
			$_SESSION['openid_enc_mac_key'] = $enc_mac_key; // for debugging. Not really neccesary...?
			return 1;
		}
		return 0;
	}
	
	// this function is far away from done. Should be completly rewritten to meet 2.0 spec.
	function checkid_setup() {
		$data_to_send = array();
		$data_to_send['openid.mode'] = 'checkid_setup';
		$data_to_send['openid.identity'] = $this->openid_url;
		$data_to_send['openid.assoc_handle'] = $_SESSION['openid_assoc_handle'];
		
		if (isset($this->openid_return_to)) {
			$data_to_send['openid.return_to'] = $this->openid_return_to;
		}
		else {
			$data_to_send['openid.return_to'] = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
		}
		//$data_to_send['openid.trusted_root'] = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
		
		if (isset($this->openid_realm)) {
			$data_to_send['openid.trust_root'] = $this->openid_realm;
		}
		else {
			$data_to_send['openid.trust_root'] = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF'];
		}
		
		if (!empty($this->optional_fields)) {
			$data_to_send['openid.sreg.optional'] = implode(',', $this->optional_fields);
		}
		
		if (!empty($this->required_fields)) {
			$data_to_send['openid.sreg.required'] = implode(',', $this->required_fields);
		}
		
		// $this->openid_url_server points to 'the server' (which can be the same url as identity, but
		// it doesnt need to be that)
		// $this->openid_url_server probably needs to be normalized.
		header('location: ' . $this->openid_url_server .'?'.http_build_query($data_to_send));
		exit;
	}
	
	// function validates the decrypted mac-key with recevied signature.
	// this function is probably far from done yet.
	function id_res() {

		$tokens = '';
		$signed = explode (',', $_GET['openid_signed']);
		foreach($signed as $key => $v) {
			$tokens .=  $v . ':' . $_GET['openid_' . str_replace('.', '_', $v)] . "\n"; //do we need to rewrite this?
		}

		// with the hmac-function we check if there was a match using the mac-key+tokens (above) to the signature
		// we got from the server
		if (base64_encode($this->hmac(base64_decode($_SESSION['openid_mac_key']), $tokens)) == $_GET['openid_sig']) {
			// match ok. proceed from here
			//echo "bingo!";
			return true;
		}
		else {
			// signature not met.
			return false;
		}
	}
	
	// This function should do lookup+validation and set some
	// private vars to this class. Lots of stuff to do here.
	function discover($openid_url) {
		
		$openid_headers = @get_headers($openid_url);
		if ($openid_headers[0] == 'HTTP/1.1 200 OK' || $openid_headers[0] == 'HTTP/1.0 200 OK') {
			$openid_content = file_get_contents($openid_url);
			
			$this->openid_url = $openid_url;
			
			$pattern = "/<link rel=\"openid.delegate\" href=\"(.*?)\"/";
			
			if (preg_match($pattern, $openid_content, $matches)) {
				// openid delegation
				if (!empty($matches[1]) && $matches[1] != $openid_url) {
 					//echo $matches[1]; exit;
					return $this->discover($matches[1]);
				}
			}
			
			$pattern = "/<link rel=\"openid.server\" href=\"(.*?)\"/";
			
			if (preg_match($pattern, $openid_content, $matches)) {
				$this->openid_url_server = $matches[1];
			}
			else {
				$this->openid_url_server = $this->openid_url;
			}
			
			/* continue... we want to check it $openid_url indeed is an openid-url + some othe stuff */
			return 1;
		}
		return 0;
	}
	
	// curl-function that senda data to an openid-server
	function _send($data, $method = 'POST') {
	
		$url = $this->openid_url_server;
		
		if ($method == 'GET') {
			$url .= '?' . http_build_query($data);
		}
	
		$curl = curl_init($url);
		
		if (!ini_get("safe_mode")) {
			curl_setopt($curl, CURLOPT_FOLLOWLOCATION, true);
		}
		
		if ($method == 'POST') {
			curl_setopt($curl, CURLOPT_POST, true);
			curl_setopt($curl, CURLOPT_POSTFIELDS, http_build_query($data));
		}
		else {
			curl_setopt($curl, CURLOPT_HTTPGET, true);
		}
		
		curl_setopt($curl, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_0); // this solves the issues with the chunked encoding
		curl_setopt($curl, CURLOPT_HEADER, false);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

		$response = curl_exec($curl);
		
		if (curl_errno($curl) == 0){
			return $response;
		}
		else {
			return 0;
		}
	}
}

?>