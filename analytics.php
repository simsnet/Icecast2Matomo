<?php
// Get listener information from Icecast XML
// Use your Icecast admin username and password here
// Change "/stream" to the mountpoint you want to track
$iceurl = 'https://user:pass@icecast.server:port/admin/listclients?mount=/stream';
$iceresponse = file_get_contents($iceurl);

// Get each listener's info
$xml = simplexml_load_string($iceresponse);
if (isset($xml->source->Listeners)) {
	$listeners = (string)$xml->source->Listeners;
	echo "[" . date("m-j-Y, H:i:s") . "]\r\n";
	if ($listeners > 0) {
		foreach($xml->source->listener as $listener) {
			$ip = $listener->IP;
			$ua = $listener->UserAgent;
			// Build the Matomo URL and send
			$maturl = 'https://my.matomo.server:port/matomo.php?'
			// Update with your own site ID
			. 'idsite=1&'
			. 'rec=1&'
			. 'action_name=Stream%20Listener&'
			. 'url=' . urlencode('http://icecast.server:port/mount') . '&'
			. 'apiv=1&'
			. 'pv_id=1&'
			. 'urlref=' . urlencode('http://icecast.server:port/mount') . '&'
			// Get your Matomo auth token from Personal > Security > Auth tokens
			. 'token_auth=yourmatomoauthtoken&'
			. 'ua=' . urlencode($ua) . '&'
			. 'cip=' . $ip;
			$curl = curl_init($maturl);
			curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
			$matresponse = curl_exec($curl);
			$matresponsecode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
			if ($matresponse === false) {
    				echo "Could not contact Matomo for " . $ip . ": " . curl_error($curl) . "\r\n";
			} else {
				echo 'Sent request to Matomo for ' . $ip . " (" . $matresponsecode . ")\r\n";
			}
			curl_close($curl);
		}
		echo "Complete!\r\n\n";
	} else {
		echo "No listeners connected.\r\nComplete!\r\n\n";
		
	}
}

?>
