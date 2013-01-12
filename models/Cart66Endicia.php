<?php
class Cart66Endicia {
  
  public static function shipLink($order) {

      $endiciaBaseUrl = "endicia://newShipment2/?";
      $endiciaUrl = "";

      $ToAddress = $order->ship_first_name . " ". $order->ship_last_name . "%0D";
      $ToAddress .= $order->ship_address . "%0D";
      if(!empty($order->ship_address2)) {
        $ToAddress .= $order->ship_address2 . "%0D";        
      }
      $ToAddress .= $order->ship_city . " " . $order->ship_state . " " . $order->ship_zip . "%0D";        
      $ToAddress .= $order->ship_country;        

      //$ToAddress = rawurlencode($ToAddress);

      if ($order->ship_country != "United States") {
	    if (strpos($order->shipping_method, "First-Class")) {
	    	  $MailClass = "INTLFIRST";
                } else if (strpos($order->shipping_method, "Express")) {
	    	  $MailClass = "INTLEXPRESS";
                } else {
	    	  $MailClass = "INTLPRIORITY";
        }
      } else if (strpos($order->shipping_method, "Express")) {
	    $MailClass = "EXPRESS";
      } else if (strpos($order->shipping_method, "Priority")) {
	    $MailClass = "PRIORITY";
      } else {
	    $MailClass = "FIRST";
      }

      $shipweight = 0.0;

	  $endiciaCustoms = "CustomsFormType=CN22;ContentsType=MERCHANDISE";
      $itemNum = 1;
      $totalProductCount = 0;

      foreach($order->getItems() as $item) {
        
	    $productCount = rawurlencode($item->quantity);
	    $productPrice = rawurlencode($item->product_price * $item->quantity);
        $productName = rawurlencode($item->description);

        $totalProductCount += $productCount;

        $p = new Cart66Product();
        $p->loadByItemNumber($item->item_number);
        $lineWeight = $p->weight * $item->quantity;
        unset ($p);

        $shipweight += $lineWeight;
        $lineWeightOz = sprintf("%0.1f",(float) $lineWeight * 16.0);

	    $endiciaCustoms .= "CustomsQuantity$itemNum=$productCount;".
                           "CustomsDescription$itemNum=$item->description;".
                           "CustomsWeight$itemNum=$lineWeightOz;".
                           "CustomsValue$itemNum=$productPrice;".
                           "CustomsCountry$itemNum=".Cart66Setting::getValue('endicia_countryOfOrigin').";";
	    $itemNum += 1;
      }

	  $ShipWeightOz = sprintf("%0.1f",(float) $shipweight * 16.0);
      $orderValue = $order->subtotal - $order->discount; 

      // If the user wants all the customs info summarized into a single line, do that now
      if (Cart66Setting::getValue('enable_endiciaGenericProducts')) { 
	      $endiciaCustoms = "CustomsFormType=CN22;ContentsType=MERCHANDISE;";
	      $endiciaCustoms .= "CustomsQuantity1=$totalProductCount;".
                           "CustomsDescription1=".Cart66Setting::getValue('endicia_productDescription').";".
                           "CustomsWeight1=$ShipWeightOz;".
                           "CustomsValue1=$orderValue;".
                           "CustomsCountry1=".Cart66Setting::getValue('endicia_countryOfOrigin').";";
      }


      $endiciaUrl =  
		   "ToAddress=$ToAddress" .
		   ";ReferenceID=$order->trans_id" . 
           ";ToEMail=$order->email" . 
           ";MailClass=$MailClass" .
           ";WeightOz=$ShipWeightOz" .
           ";Value=$orderValue".
           ";Description=".Cart66Setting::getValue('endicia_productDescription').
           ";Stealth=TRUE".
           ";$endiciaCustoms";
      
      return $endiciaBaseUrl . $endiciaUrl;
  }
  
}
