<?php
App::uses('Component', 'Controller');
class GiftologyComponent extends Component {
	function generateGiftCode($prodId) {
	    $length = 4;
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyz';
	    $string = 'GIFT-'.$prodId.'-';
	    for ($p = 0; $p < $length; $p++) {
		$string .= $characters[mt_rand(0, strlen($characters)-1)];
		}
	    return $string;
	}
	function postToFB($fb_id, $access_token, $url, $message) {
		// go with exec curl, as the return here is quicker (asynchronous) NS
		exec('curl -F \'access_token='.$access_token.'\' -F \'message='.$message.'\' -F \'link='.$url.'\' https://graph.facebook.com/'.$fb_id.'/feed  > /dev/null 2>&1 &');
		
		/*$fields = array(
		  'access_token'=> $access_token,
	          'message'=>$message,
	          'link'=>urlencode($url)
	        );
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL,'https://graph.facebook.com/'.$fb_id.'/feed');
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
		curl_setopt($ch, CURLOPT_POST, true);
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);
		
		//execute post
		$result = curl_exec($ch);
		
		//close connection
		curl_close($ch);*/
	}
}