<?php
//Packing List Mod 
//Max Wilson Max@ninexn.com 
//April 6 2011 
  $product = new Cart66Product();
  $order = $data['order']; 
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
	"http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">

<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>

	<title>Packing Slip</title>
	<style type="text/css" media="print">
/*<![CDATA[*/
	 #print_button {
	   display: none;
	 }
/*]]>*/
	</style>
	<style type="text/css" media="screen,print">
  /*<![CDATA[*/
     body {
       font-family: arial;     
       font-size: 12px;      
       color: black;   
     }       
     table {
       margin: 10px;   
     }
     h1 {
       font-size: 18px;
     }
     h2 {
     	font-size: 14px;
     }
     p {
       padding: 3px 0px;
     }

     #viewCartTable th,
     #viewCartTable td {
       padding: 5px;
     }  
     
     table .entry-details {
       width: 100%;
     }

     table .entry-details tbody {
       padding: 0px;
       margin: 0px;
       background-color: #fff;
     }

     #viewCartTable td .entry-view-field-name {
       font-weight: bold;
       margin: 0px;
     }

     #viewCartTable td .entry-view-field-value {
       padding-left: 25px !important;
       border: none !important;
     }
  /*]]>*/
  </style>
</head>

<body>
  <h1><?php echo get_bloginfo('name'); ?></h1>
  <h2>Packing Slip</h2>
  
  <table border="0" cellpadding="0" cellspacing="0">
    <tr>
      <td colspan="3"><p><strong>Order Number: <?php echo $order->trans_id ?></strong></p></td>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      <td align="left"><p><strong>Date: <?php echo date('m/d/Y', strtotime($order->ordered_on)); ?></strong></p></td>
    </tr>
    <tr>
      <td valign="top">
        <p>
          <strong>Billing Information</strong><br/>
        <?php echo $order->bill_first_name ?> <?php echo $order->bill_last_name ?><br/>
        <?php echo $order->bill_address ?><br/>
        <?php if(!empty($order->bill_address2)): ?>
          <?php echo $order->bill_address2 ?><br/>
        <?php endif; ?>

        <?php if(!empty($order->bill_city)): ?>
          <?php echo $order->bill_city ?> <?php echo $order->bill_state ?>, <?php echo $order->bill_zip ?><br/>
        <?php endif; ?>

        <?php if(!empty($order->bill_country)): ?>
          <?php echo $order->bill_country ?><br/>
        <?php endif; ?>
        </p>
      </td>
      <td>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</td>
      <td valign="top">
       <?php if($order->shipping_method != 'None'): ?>
        <p>
          <strong>Shipping Information</strong><br/>
        <?php echo $order->ship_first_name ?> <?php echo $order->ship_last_name ?><br/>
        <?php echo $order->ship_address ?><br/>

        <?php if(!empty($order->ship_address2)): ?>
          <?php echo $order->ship_address2 ?><br/>
        <?php endif; ?>

        <?php if($order->ship_city != ''): ?>
          <?php echo $order->ship_city ?> <?php echo $order->ship_state ?>, <?php echo $order->ship_zip ?><br/>
        <?php endif; ?>

        <?php if(!empty($order->ship_country)): ?>
          <?php echo $order->ship_country ?><br/>

        <?php endif; ?>

        </p>
      </td>
    </tr>
    <tr>
      <td>
      	<p><strong>Contact Information</strong><br/>
        <?php if(!empty($order->phone)): ?>
          Phone: <?php echo Cart66Common::formatPhone($order->phone) ?><br/>
        <?php endif; ?>
        Email: <?php echo $order->email ?><br/>
        </p>
        <?php endif; ?>
      </td>
      <td>&nbsp;</td>
      <td>&nbsp;</td>
    </tr>
  </table>

  <table id="viewCartTable" cellspacing="0" cellpadding="0">
    <tr>
      <th style="text-align: left;">Product</th>
      <th style="text-align: center;">Quantity Ordered</th>
    </tr>

    <?php foreach($order->getItems() as $item): ?>
      <?php 
        $product->load($item->product_id);
        $price = $item->product_price * $item->quantity;
      ?>
      <tr>
        <td>
          <?php echo str_replace("'", "&#039;", $item->description); ?>
          <?php
            $product->load($item->product_id);
            if($product->isDigital()) {
              $receiptPage = get_page_by_path('store/receipt');
              $receiptPageLink = get_permalink($receiptPage);
              $receiptPageLink .= (strstr($receiptPageLink, '?')) ? '&duid=' . $item->duid : '?duid=' . $item->duid;
              //echo "<br/><a href='$receiptPageLink'>Download</a>";
            }
          ?>

        </td>
        <td style="text-align: center;"><?php echo $item->quantity ?></td>
      </tr>
      <?php
        if(!empty($item->form_entry_ids)) {
          $entries = explode(',', $item->form_entry_ids);
          foreach($entries as $entryId) {
            if(class_exists('RGFormsModel')) {
              if(RGFormsModel::get_lead($entryId)) {
                echo "<tr><td colspan='4'><div class='Cart66GravityFormDisplay'>" . Cart66GravityReader::displayGravityForm($entryId) . "</div></td></tr>";
              }
            }
            else {
              echo "<tr><td colspan='5' style='color: #955;'>This order requires Gravity Forms in order to view all of the order information</td></tr>";
            }
          }
        }
      ?>
    <?php endforeach; ?>
  </table>
  
  <form>
    <input type="button" onClick="window.print();" name="print_button" id="print_button" value="Print Packing List" />
  </form>
</body>
</html>
