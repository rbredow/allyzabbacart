<?php
// Look for sendy opt-in
$mcIds = Cart66Common::postVal('sendy_subscribe_id');


if($mcIds == 1) {
  
  Cart66Common::log('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Trying to register for Sendy newsletter");
  $sendy_url = Cart66Setting::getValue('sendy_url');
  $sendy_id = Cart66Setting::getValue('sendy_id');
  
  if(isset($_POST['payment']) && isset($_POST['billing'])) {
    // Process from on-site checkout forms
    $email = $_POST['payment']['email'];
    $name = $_POST['billing']['firstName'] ." ". $_POST['billing']['lastName'];
  }
  /* TODO Fix Paypal Express (optional) */
  elseif( isset($_POST['mailchimp_email']) && isset($_POST['mailchimp_first_name']) && isset($_POST['mailchimp_last_name']) ) {
    // Process from PayPal Express Checkout
    $email = Cart66Common::postVal('mailchimp_email');
    $extraFields = array(
  		'FirstName' => $_POST['mailchimp_first_name'],
  		'LastName'  => $_POST['mailchimp_last_name']
  	);
    $name = $_POST['billing']['firstName'] ." ". $_POST['billing']['lastName'];
  }
 
  $Sendy = new Cart66Sendy();

  $status = $Sendy->listSubscribe($sendy_url, $sendy_id, $email, $name);

  if ($status != "true" && $status != "1") {
  	$logmsg = "Unable to subscribe!\n";
  	$logmsg .= "\tError=".$status."\n";
  } 
  else {
    // We've got an error of some sort
    $logmsg = "Subscribed Status:" . $status . " Fields: ".$email.",".$sendy_id.",".$name."\n";
    
  }
  
  Cart66Common::log('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] $logmsg");
  
} else {
  Cart66Common::log('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Sendy User opted out of subscribing.");
}
