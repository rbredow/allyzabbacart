<?php
// Look for sendy opt-in
$mcIds = Cart66Common::postVal('sendy_subscribe_id');

if(isset($mcIds)) {
  
  Cart66Common::log('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Trying to register for Sendy newsletter");
  $sendy_url = Cart66Setting::getValue('sendy_url');
  $sendy_id = Cart66Setting::getValue('sendy_id');
  
  
  if(isset($_POST['payment']) && isset($_POST['billing'])) {
    // Process from on-site checkout forms
    $email = $_POST['payment']['email'];
    $extraFields = array(
  		'FirstName' => $_POST['billing']['firstName'],
  		'LastName'  => $_POST['billing']['lastName']
  	);
  }
  /* TODO Fix this */
  elseif( isset($_POST['mailchimp_email']) && isset($_POST['mailchimp_first_name']) && isset($_POST['mailchimp_last_name']) ) {
    // Process from PayPal Express Checkout
    $email = Cart66Common::postVal('mailchimp_email');
    $extraFields = array(
  		'FirstName' => $_POST['mailchimp_first_name'],
  		'LastName'  => $_POST['mailchimp_last_name']
  	);
  }
  
  
  if ($mc->errorCode) {
  	$logmsg = "Unable to load listSubscribe()!\n";
  	$logmsg .= "\tCode=".$mc->errorCode."\n";
  	$logmsg .= "\tMsg=".$mc->errorMessage."\n";
  } 
  else {
    
    //Cart66Common::log('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] MailChimp Subscribe output: ".print_r($retval,true));
    
    $list_ids = array();
    foreach($mcIds as $key=>$mcid){
      $list_ids[] = $mcid;
    }
    $subscribed_list_ids = implode(',', $list_ids);
    $listn = $mc->lists(array("list_id"=>$subscribed_list_ids),0,100);

    $list_names = array();
    foreach($listn['data'] as $list){
      $list_names[] = $list['name'];
    }
    $subscribed_list_names = implode(', ', $list_names);
    
    $logmsg = "Subscribed: " . $extraFields['FirstName'] . " " . $extraFields['LastName'] . " $email to ".print_r($subscribed_list_names,true);
    
  }
  
  Cart66Common::log('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] $logmsg");
  
  
}
