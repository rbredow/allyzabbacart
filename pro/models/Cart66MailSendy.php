<?php

class Cart66Sendy{
  
  public function listSubscribe($url, $id, $email_address, $fullname="") {

	//POST variables
	$email = $email_address;
	$list = $id;
	$boolean = 'true';
 
	//CHECK email
	if($email=='') {
	  return false;
        }

	//CHECK URL
	if($email=='') {
	  return false;
        }
       
	//POST TO FORM
	$postdata = http_build_query(
	    array(
	    'email' => $email,
	    'list' => $list,
	    'name' => $fullname,
	    'boolean' => $boolean
	    )
	);
	$opts = array('http' =>
	    array(
	        'method'  => 'POST',
	        'header'  => 'Content-type: application/x-www-form-urlencoded',
	        'content' => $postdata
	    )
	);
	$context  = stream_context_create($opts);
	$fullurl = $url."/subscribe";
	$result = file_get_contents($fullurl, false, $context);

	return $result; 
  }

  
}

?>
