<?php
class ZendeskRemoteAuth {

  public static function login(Cart66Account $account) {
    $name = $account->firstName . ' ' . $account->lastName;
    $email = $account->email;
    $externalId = $account->id;
    $organization = Cart66Setting::getValue('zendesk_organization');
    $token = Cart66Setting::getValue('zendesk_token');
    $prefix = Cart66Setting::getValue('zendesk_prefix');

     /* Build the message */
    $ts = isset($_GET['timestamp']) ? $_GET['timestamp'] : time(); 
    $message = $name . '|' . $email . '|' . $externalId . '|' . $organization . '|||' . $token . '|' . $ts;
    $hash = MD5($message);

    //$remoteAuthUrl = "http://" . $prefix . ".zendesk.com/access/remoteauth/?name=" . urlencode($name) . "&email=". urlencode($email) . "&external_id=".$externalId . "&organization=" . $organization ."&timestamp=". $ts ."&hash=". $hash;
    
    $remoteAuthUrl = 'http://' . $prefix . '.zendesk.com/access/remoteauth/';
    $arguments = array(
      'name' => $name,
      'email' => $email,
      'external_id' => $externalId,
      'organization' => $organization,
      'timestamp' => $ts,
      'hash' => $hash
    );
    $url = add_query_arg($arguments, $remoteAuthUrl);

    //Cart66Common::log('[' . basename(__FILE__) . ' - line ' . __LINE__ . "] Message: $message\nRemote Auth URL: $remoteAuthUrl");

    header("Location: " . $url);
    exit;
  } 
}