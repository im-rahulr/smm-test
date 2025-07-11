<?php

if( $panel["panel_status"] == "suspended" ): include 'app/views/frozen.twig';exit(); endif;
if( $panel["panel_status"] == "frozen" ): include 'app/views/frozen.twig';exit(); endif;

use Mollie\Api\MollieApiClient;
use GuzzleHttp\Client;

//print_r($_POST); die;

function create_random_string_key($length = "") {
		if ($length == "") {
			$length = 26;
		}
	    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
}


$title.= " Add Funds";

if( $_SESSION["msmbilisim_userlogin"] != 1  || $user["client_type"] == 1  ){
    Header("Location:".site_url('logout'));
}

if( $settings["email_confirmation"] == 1  && $user["email_type"] == 1  ){
  Header("Location:".site_url('confirm_email'));
}


$paymentsList = $conn->prepare("SELECT * FROM payment_methods WHERE method_type=:type && id!=:id6 && id!=:id10 && id!=:id14 ORDER BY method_line ASC ");
$paymentsList->execute(array("type" => 2, "id6" => 6, "id10" => 10, "id14" => 14  ));
$paymentsList = $paymentsList->fetchAll(PDO::FETCH_ASSOC);
foreach ($paymentsList as $index => $payment) {
    $extra = json_decode($payment["method_extras"], true);
    $methodList[$index]["method_name"] = $extra["name"];
    $methodList[$index]["id"] = $payment["id"];
}
$PaytmQR = $conn->prepare("SELECT * FROM payment_methods WHERE id=:id ");
$PaytmQR->execute(array("id" => 14));
$PaytmQR = $PaytmQR->fetch(PDO::FETCH_ASSOC);
$PaytmQRimg = json_decode($PaytmQR['method_extras'], true);
$PaytmQRimage = $PaytmQRimg["merchant_key"];
$bankPayment = $conn->prepare("SELECT * FROM payment_methods WHERE id=:id ");
$bankPayment->execute(array("id" => 6));
$bankPayment = $bankPayment->fetch(PDO::FETCH_ASSOC);
$bankList = $conn->prepare("SELECT * FROM bank_accounts");
$bankList->execute(array());
$bankList = $bankList->fetchAll(PDO::FETCH_ASSOC);
$payoneerPayment = $conn->prepare("SELECT * FROM payment_methods WHERE id=:id ");
$payoneerPayment->execute(array("id" => 10));
$payoneerPayment = $payoneerPayment->fetch(PDO::FETCH_ASSOC);
$payoneerPaymentExtra = json_decode($payoneerPayment['method_extras'], true);

$clid =$user['client_id']; 

$searchh = "WHERE payments.client_id=$clid && payments.payment_status='3' ";
$transaction_logs = $conn->prepare("SELECT * FROM payments INNER JOIN payment_methods ON payment_methods.id=payments.payment_method INNER JOIN clients ON clients.client_id=payments.client_id $searchh ORDER BY payments.payment_id DESC");
$transaction_logs->execute(array());
$transaction_logs = $transaction_logs->fetchAll(PDO::FETCH_ASSOC);

if ($_POST && $_POST["payment_bank"]):
    foreach ($_POST as $key => $value):
        $_SESSION["data"][$key] = $value;
    endforeach;
    $bank = $_POST["payment_bank"];
    $amount = $_POST["payment_bank_amount"];
    $gonderen = $_POST["payment_gonderen"];
    $method_id = 6;
    $extras = json_encode($_POST);
    if (open_bankpayment($user["client_id"]) >= 2) {
        unset($_SESSION["data"]);
        $error = 1;
        $errorText = 'You have 2 payment notifications pending approval, you cannot create new notifications.';
    } elseif (empty($bank)) {
        $error = 1;
        $errorText = 'Please select a valid bank account.';
    } elseif (!is_numeric($amount)) {
        $error = 1;
        $errorText = 'Please enter a valid amount.';
    } elseif (empty($gonderen)) {
        $error = 1;
        $errorText = 'Please enter a valid sender name.';
    } else {
        $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_method=:method, payment_create_date=:date, payment_ip=:ip, payment_extra=:extras, payment_bank=:bank ");
        $insert->execute(array("c_id" => $user["client_id"], "amount" => $amount, "method" => $method_id, "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extras" => $extras, "bank" => $bank));
        if ($insert) {
            unset($_SESSION["data"]);
            $success = 1;
            $successText = 'Your payment notification has been received.';
            if ($settings["alert_newbankpayment"] == 2):
                if ($settings["alert_type"] == 3):
                    $sendmail = 1;
                    $sendsms = 1;
                elseif ($settings["alert_type"] == 2):
                    $sendmail = 1;
                    $sendsms = 0;
                elseif ($settings["alert_type"] == 1):
                    $sendmail = 0;
                    $sendsms = 1;
                endif;
                if ($sendsms):
                    SMSUser($settings["admin_telephone"], "New payment request created on your site and ID is: #" . $conn->lastInsertId());
                endif;
                if ($sendmail):
                    sendMail(["subject" => "New payment request", "body" => "New payment request created on your site and ID is: #" . $conn->lastInsertId(), "mail" => $settings["admin_mail"]]);
                endif;
            endif;
        } else {
            $error = 1;
            $errorText = 'An error occurred while alert sending, please try again later..';
        }
    } elseif ($_POST && $_POST["payment_type"]):
        foreach ($_POST as $key => $value):
            $_SESSION["data"][$key] = $value;
        endforeach;
        $method_id = $_POST["payment_type"];
        $amount = $_POST["payment_amount"];
         if($_POST["paytmqr_orderid"] !="" ){
            $paytmqr_orderid = $_POST["paytmqr_orderid"];
        }
        $extras = json_encode($_POST);
        $method = $conn->prepare("SELECT * FROM payment_methods WHERE id=:id ");
        $method->execute(array("id" => $method_id));
        $method = $method->fetch(PDO::FETCH_ASSOC);
        $extra = json_decode($method["method_extras"], true);
        $paymentCode = createPaymentCode();
        $amount_fee = ($amount + ($amount * $extra["fee"] / 100));
        if (empty($method_id)) {
            $error = 1;
            $errorText = 'Please select a valid payment method.';
        } elseif (!is_numeric($amount)) {
            $error = 1;
            $errorText = 'Please enter a valid amount.';
        } elseif ($amount < $method["method_min"]) {
            $error = 1;
            $errorText = 'Minimum payment amount ' . $settings["csymbol"] . $method["method_min"];
        } elseif ($amount > $method["method_max"] && $method["method_max"] != 0) {
            $error = 1;
            $errorText = 'Maximum payment amount ' . $settings["csymbol"] . $method["method_max"];
        } else {
            // $currentcur = '{"rates": {"USD": "1"}}';
            // $ch = curl_init();
            // curl_setopt($ch, CURLOPT_URL, 'https://api.exchangeratesapi.io/latest?base='.$settings['site_currency']);
            // curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            // $currentcur = curl_exec($ch);
            // $currentcur = json_decode($currentcur);

            // if(isset($currentcur->error){
            //     if(defined($getcur.'_')) { constant($getcur.'_') }else{
            //         die('There\'s a problem with currency. Please contact with admin.')
            //     } 
            // }

            if ($method_id == 1):
                unset($_SESSION["data"]);
                $pp_amount_fee = str_replace(',', '.', $amount_fee);
                $icid = md5(rand(1,999999));
                $getcur = $extra['currency'];
                $lastcur = isset($currentcur->error) ? defined($getcur.'_') ? constant($getcur.'_') : die('There\'s a problem with currency. Please contact with admin.') : $currentcur->rates->$getcur;
                $getamo = number_format($pp_amount_fee * $lastcur, 2, '.', '');
                $jsondata = json_encode(array('c' => $getcur, 'a' => $getamo));
                
                $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra, data=:data");
                $insert->execute(array("c_id" => $user['client_id'], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $icid, "data" => $jsondata));
                if ($insert):
                    $success = 1;
                    $successText = "Your payment was initiated successfully, you are being redirected..";
                    $payment_url = site_url('lib/pay/paypal-payment.php?hash='.$icid);
                else:
                    $error = 1;
                    $errorText = "There was an error starting your payment, please try again later..";
                endif;
                
                elseif ($method_id == 17):
                unset($_SESSION["data"]);
                $pix_amount_fee = str_replace(',', '.', $amount_fee);
                $icid = "PIXFB".create_random_string_key(21);
                $getcur = "BRL";//$extra['currency'];
                //$lastcur = isset($currentcur->error) ? defined($getcur.'_') ? constant($getcur.'_') : die('There\'s a problem with currency. Please contact with admin.') : $currentcur->rates->$getcur;
                $getamo = number_format($pix_amount_fee, 2, '.', '');
                $jsondata = json_encode(array('c' => $getcur, 'a' => $getamo));
                
                $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra, data=:data");
                $insert->execute(array("c_id" => $user['client_id'], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $icid, "data" => $jsondata));
                if ($insert):     		
                    //$payment_url = site_url('lib/pay/pix-payment.php?hash='.$icid);
					 echo '<form method="post" action="lib/pay/pix-payment.php" name="pix">';
                         echo '<input type="hidden" name="hash" value="'.$icid.'">
                    		<script type="text/javascript">
                    			document.pix.submit();
                    		</script>
                    	   </form>';
                endif;
				
			 ## MERCADOPAGO ## ---------------------------------------------------------------------------------------------------------------------------------------------------------------
        elseif ($method_id == 16) :
            include( $_SERVER['DOCUMENT_ROOT']."/vendor/pix_auto/autoload.php"); 
            unset($_SESSION["data"]);
            $mp_amount_fee = str_replace(',', '.', $amount_fee);
            $icid = create_random_string_key(15);
            $getcur = $extra['currency'];
            $getamo = number_format($mp_amount_fee, 2, '.', '');
            $jsondata = json_encode(array('c' => $getcur, 'a' => $getamo));

            $paramLista["TOKEN"]        = $extra['live_access_token'];
            $paramLista["ORDER_ID"]     = $icid;
            $paramLista["TXN_AMOUNT"]   = $getamo;
            $paramLista["NOME"]         = $user['name'];
            $paramLista["CALLBACK_URL"] = site_url('lib/pay/mercadopago/notificacao.php');
            $paramLista["BASE"]         = site_url();

            $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra");
            $insert->execute(array("c_id" => $user['client_id'], "amount" => $getamo, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $icid));
            if ($insert) :
                echo '<form method="post" action="lib/pay/mercadopago/index.php" name="f1">
                        ';
                foreach ($paramLista as $name => $value) {
                    echo '<input type="hidden" name="' . $name . '" value="' . $value . '">';
                }
                echo '<script type="text/javascript">
                            document.f1.submit();
                        </script>
                    </form>';
            endif;

elseif ($method_id == 26):

        // purchase.php

//

        $MERCHANT_KEY = $extra['merchant_key'];
        $SALT         = $extra['salt'];

$PAYU_BASE_URL = "https://secure.payu.in"; // LIVE mode
        // $PAYU_BASE_URL = "https://test.payu.in"; // TEST mode
$famount = $amount*$settings["dolar_charge"];
        $posted = [
            'key'              => $MERCHANT_KEY,
            'txnid'            => substr(hash('sha256', mt_rand() . microtime()), 0, 20),
            'amount'           => $famount,
            'productinfo'      => 'Recharge',
            'firstname'        => $user['first_name'],
            'email'            => $user['email'],
            'phone'            => $user['phone'],
            'surl'             => site_url('payment/payumoney'),
            'furl'             => site_url('addfunds'),
            'service_provider' => 'payu_paisa', // <================= DONT send this in TEST mode
        ];
 
// Hash Sequence



        $hashSequence = "key|txnid|amount|productinfo|firstname|email|udf1|udf2|udf3|udf4|udf5|udf6|udf7|udf8|udf9|udf10";

        $hashVarsSeq = explode('|', $hashSequence);
        $hash_string = '';

        foreach ($hashVarsSeq as $hash_var) {
            $hash_string .= isset($posted[$hash_var]) ? $posted[$hash_var] : '';
            $hash_string .= '|';
        }

        $hash_string .= $SALT;
        $hash = strtolower(hash('sha512', $hash_string));


        $posted['hash'] = $hash ;
        $action         = $PAYU_BASE_URL . '/_payment';

        $order_id                     = $posted["txnid"];
       
        $insert                       = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra");
        $insert->execute(array("c_id" => $user['client_id'], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $order_id));
        
        

// Redirects to PayUMoney
        // $payumoney->initializePurchase($params)->send();
        echo '<div class="dimmer active" style="min-height: 400px;">
          <div class="loader"></div>
          <div class="dimmer-content">
            <center><h2>Please do not refresh this page</h2></center>
            <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
              <circle cx="50" cy="50" r="32" stroke-width="8" stroke="#e15b64" stroke-dasharray="50.26548245743669 50.26548245743669" fill="none" stroke-linecap="round">
                <animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 50;360 50 50"></animateTransform>
              </circle>
              <circle cx="50" cy="50" r="23" stroke-width="8" stroke="#f8b26a" stroke-dasharray="36.12831551628262 36.12831551628262" stroke-dashoffset="36.12831551628262" fill="none" stroke-linecap="round">
                <animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 50;-360 50 50"></animateTransform>
              </circle>
            </svg>
             <form action="'.$action.'" method="post" name="payuForm" id="pay">
            <input type="hidden" name="key"         value="' . $posted["key"] . '" />
            <input type="hidden" name="hash"        value="' . $posted["hash"] . '" />
            <input type="hidden" name="txnid"       value="' . $posted["txnid"] . '" />
            <input type="hidden" name="amount"      value="' . $posted["amount"] . '" />
            <input type="hidden" name="productinfo" value="' . $posted["productinfo"] . '" />
            <input type="hidden" name="firstname"   value="' . $posted["firstname"] . '" />
            <input type="hidden" name="email"       value="' . $posted["email"] . '" />
            <input type="hidden" name="phone"       value="' . $posted["phone"] . '" />
            <input type="hidden" name="surl"        value="' . $posted["surl"] . '" />
            <input type="hidden" name="furl"        value="' . $posted["furl"] . '" />

            <input type="hidden" name="service_provider" value="' . $posted["service_provider"] . '" size="68" />
            


              <script type="text/javascript">
                document.getElementById("pay").submit();
              </script>
            </form>
          </div>
        </div>';





            elseif ($method_id == 2):
                unset($_SESSION["data"]);
                $icid = md5(rand(1,999999));
                $getcur = $extra['currency'];
                $lastcur = isset($currentcur->error) ? defined($getcur.'_') ? constant($getcur.'_') : die('There\'s a problem with currency. Please contact with admin.') : $currentcur->rates->$getcur;
                $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra");
                $insert = $insert->execute(array("c_id" => $user['client_id'], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $icid));
                if ($insert):
                    $success = 1;
                    $successText = "Your payment was initiated successfully, you are being redirected..";
                    $payment_url = site_url('lib/stripe/index.php');
                else:
                    $error = 1;
                    $errorText = "There was an error starting your payment, please try again later..";
                endif;
            elseif ($method_id == 8):
                $getcur = $extra['currency'];
                $lastcur = isset($currentcur->error) ? defined($getcur.'_') ? constant($getcur.'_') : die('There\'s a problem with currency. Please contact with admin.') : $currentcur->rates->$getcur;
                // Create a new API wrapper instance
                $cps_api = new CoinpaymentsAPI($extra["coinpayments_private_key"], $extra["coinpayments_public_key"], 'json');

                // This would be the price for the product or service that you're selling
                $cp_amount = str_replace(',', '.', $amount_fee);
                $cp_amount = number_format($cp_amount * $lastcur, 2, '.', '');

                // The currency for the amount above (original price)
                $currency1 = $extra['currency'];

                // Litecoin Testnet is a no value currency for testing
                // The currency the buyer will be sending equal to amount of $currency1
                $currency2 = $extra["coinpayments_currency"];

                // Enter buyer email below
                $buyer_email = $user["email"];

                // Set a custom address to send the funds to.
                // Will override the settings on the Coin Acceptance Settings page
                $address = '';

                // Enter a buyer name for later reference
                $buyer_name = $user["name"];

                // Enter additional transaction details
                $item_name = 'Add Balance';
                $item_number = $cp_amount;
                $custom = 'Express order';
                $invoice = 'addbalancetosmm001';
                $ipn_url = site_url('payment/coinpayments');
                

                // Make call to API to create the transaction
                try {
                    $transaction_response = $cps_api->CreateComplexTransaction($cp_amount, $currency1, $currency2, $buyer_email, $address, $buyer_name, $item_name, $item_number, $invoice, $custom, $ipn_url);
                } catch (Exception $e) {
                    echo 'Error: ' . $e->getMessage();
                    exit();
                }

                if ($transaction_response['error'] == 'ok'):
                    unset($_SESSION["data"]);
                    $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra");
                    $insert->execute(array("c_id" => $user['client_id'], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $transaction_response['result']['txn_id']));
                    $success = 1;
                    $successText = "Your payment was initiated successfully, you are being redirected..";
                    $payment_url = $transaction_response['result']['checkout_url'];
                else:
                    $error = 1;
                    $errorText = "There was an error starting your payment, please try again later..";
                endif;
            elseif ($method_id == 9):
                require_once("vendor/2checkout/2checkout-php/lib/Twocheckout.php");
                Twocheckout::privateKey($extra['private_key']);
                Twocheckout::sellerId($extra['seller_id']);

                // If you want to turn off SSL verification (Please don't do this in your production environment)
                Twocheckout::verifySSL(false);  // this is set to true by default

                // To use your sandbox account set sandbox to true
                Twocheckout::sandbox(false);

                // All methods return an Array by default or you can set the format to 'json' to get a JSON response.
                Twocheckout::format('json');

                $icid = md5(rand(1,999999));
                $getcur = $extra['currency'];
                $lastcur = isset($currentcur->error) ? defined($getcur.'_') ? constant($getcur.'_') : die('There\'s a problem with currency. Please contact with admin.') : $currentcur->rates->$getcur;
                $tc_amount = str_replace(',', '.', $amount_fee);
                $params = array(
                    'sid' => $icid,
                    'mode' => '2CO',
                    'li_0_name' => 'Add Balance',
                    'li_0_price' => number_format($tc_amount * $lastcur, 2, '.', '')
                );

                unset($_SESSION["data"]);
                $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra");
                $insert->execute(array("c_id" => $user['client_id'], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $icid));
                $success = 1;
                $successText = "Your payment was initiated successfully, you are being redirected..";
                Twocheckout_Charge::form($params, 'auto');
            elseif ($method_id == 11):
                $mollie = new MollieApiClient();
                $mollie->setApiKey($extra['live_api_key']);

                $icid = md5(rand(1,999999));
                $getcur = $extra['currency'];
                $lastcur = isset($currentcur->error) ? defined($getcur.'_') ? constant($getcur.'_') : die('There\'s a problem with currency. Please contact with admin.') : $currentcur->rates->$getcur;
                $ml_amount = str_replace(',', '.', $amount_fee);
                $payment = $mollie->payments->create([
                    "amount" => [
                        "currency" => $extra['currency'],
                        "value" => number_format($ml_amount * $lastcur, 2, '.', '')
                    ],
                    "description" => $user["email"],
                    "redirectUrl" => site_url(),
                    "webhookUrl" => site_url('payment/mollie'),
                    "metadata" => [
                        "order_id" => $icid,
                    ],
                ]);

                unset($_SESSION["data"]);
                $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra");
                $insert->execute(array("c_id" => $user['client_id'], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $icid));
                $success = 1;
                $successText = "Your payment was initiated successfully, you are being redirected..";
                $payment_url = $payment->getCheckoutUrl();
            elseif ($method_id == 12):
                require_once("lib/encdec_paytm.php");

                $checkSum = "";
                $paramList = array();

                $amount_fee = $amount_fee * $settings['inr_value'];

                $icid = md5(rand(1,999999));
                $getcur = $extra['currency'];
                // $lastcur = isset($currentcur->error) ? defined($getcur.'_') ? constant($getcur.'_') : die('There\'s a problem with currency. Please contact with admin.') : $currentcur->rates->$getcur;
                
                
                
                $ptm_amount = str_replace(',', '.', $amount_fee);

                $paramList["MID"] = $extra['merchant_mid'];
                $paramList["ORDER_ID"] = $icid;
                $paramList["CUST_ID"] = $user['client_id'];
                $paramList["EMAIL"] = $user['email'];
                $paramList["INDUSTRY_TYPE_ID"] = "Retail";
                $paramList["CHANNEL_ID"] = "WEB";
                $paramList["TXN_AMOUNT"] = number_format($ptm_amount , 2, '.', '');
                $paramList["WEBSITE"] = $extra['merchant_website'];
                $paramList["CALLBACK_URL"] = site_url('payment/paytm');

                $checkSum = getChecksumFromArray($paramList, $extra['merchant_key']);

                unset($_SESSION["data"]);
                $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra");
                $insert->execute(array("c_id" => $user['client_id'], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $icid));
                $success = 1;
                $successText = "Your payment was initiated successfully, you are being redirected..";
                echo '<form method="post" action="https://securegw.paytm.in/theia/processTransaction" name="f1">
                    <table border="1">
                        <tbody>';
                        foreach($paramList as $name => $value) {
                            echo '<input type="hidden" name="' . $name .'" value="' . $value . '">';
                        }
                        echo '<input type="hidden" name="CHECKSUMHASH" value="'. $checkSum .'">
                        </tbody>
                    </table>
                    <script type="text/javascript">
                        document.f1.submit();
                    </script>
                </form>';
            elseif ($method_id == 13):
                unset($_SESSION["data"]);
                $im_amount_fee = str_replace(',', '.', $amount_fee);
                $icid = md5(rand(1,999999));
                $getcur = $extra['currency'];
                $lastcur = isset($currentcur->error) ? defined($getcur.'_') ? constant($getcur.'_') : die('There\'s a problem with currency. Please contact with admin.') : $currentcur->rates->$getcur;
                $getamo = number_format($im_amount_fee * $lastcur, 2, '.', '');
                $jsondata = json_encode(array('c' => $getcur, 'a' => $getamo));
                
                $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra, data=:data");
                $insert->execute(array("c_id" => $user['client_id'], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $icid, "data" => $jsondata));
                if ($insert):
                    $success = 1;
                    $successText = "Your payment was initiated successfully, you are being redirected..";
                    $payment_url = site_url('lib/pay/instamojo-payment.php?hash='.$icid);
                else:
                    $error = 1;
                    $errorText = "There was an error starting your payment, please try again later..";
                endif;
            elseif ($method_id == 23):
                    
                    $amount = (double)$amount;
                
                    $client_id = $extra['usd'];
                    
                   // $users = session('user_current_info');
                    $order_id = strtotime('NOW');
                    $perfectmoney = array(
                        'PAYEE_ACCOUNT' 	=> $client_id,
                        'PAYEE_NAME' 		=> $extra['merchant_website'],
                        'PAYMENT_UNITS' 	=> "USD",
                        'STATUS_URL' 		=> site_url('payment/perfectmoney'),
                        'PAYMENT_URL' 		=> site_url('payment/perfectmoney'),
                        'NOPAYMENT_URL' 	=> site_url('payment/perfectmoney'),
                        'BAGGAGE_FIELDS' 	=> 'IDENT',
                        'ORDER_NUM' 		=> $order_id,
                        'PAYMENT_ID' 		=> strtotime('NOW'),
                        'CUST_NUM' 		    => "USERID" . rand(10000,99999999),
                        'memo' 		        => "Balance recharge - ".  $user['email'],
        
                    );
                    $tnx_id = $perfectmoney['PAYMENT_ID'];
                    
                    $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra");
                    $insert->execute(array("c_id" => $user['client_id'], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $tnx_id));
                    $success = 1;
                    $successText = "Your payment was initiated successfully, you are being redirected..";
                    
                    
                
                 echo '<div class="dimmer active" style="min-height: 400px;">
                  <div class="loader"></div>
                  <div class="dimmer-content">
                    <center><h2>Please do not refresh this page</h2></center>
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="margin:auto;background:#fff;display:block;" width="200px" height="200px" viewBox="0 0 100 100" preserveAspectRatio="xMidYMid">
                      <circle cx="50" cy="50" r="32" stroke-width="8" stroke="#e15b64" stroke-dasharray="50.26548245743669 50.26548245743669" fill="none" stroke-linecap="round">
                        <animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 50;360 50 50"></animateTransform>
                      </circle>
                      <circle cx="50" cy="50" r="23" stroke-width="8" stroke="#f8b26a" stroke-dasharray="36.12831551628262 36.12831551628262" stroke-dashoffset="36.12831551628262" fill="none" stroke-linecap="round">
                        <animateTransform attributeName="transform" type="rotate" dur="1s" repeatCount="indefinite" keyTimes="0;1" values="0 50 50;-360 50 50"></animateTransform>
                      </circle>
                    </svg>
                    <form method="post" action="https://perfectmoney.is/api/step1.asp" id="redirection_form">
                      <input type="hidden" name="PAYMENT_AMOUNT" value="'.$amount.'">
                      <input type="hidden" name="PAYEE_ACCOUNT" value="'.$perfectmoney["PAYEE_ACCOUNT"].'">
                      <input type="hidden" name="PAYEE_NAME" value="'.$perfectmoney["PAYEE_NAME"].'">
                      <input type="hidden" name="PAYMENT_UNITS" value="'.$perfectmoney["PAYMENT_UNITS"].'">
                      <input type="hidden" name="STATUS_URL" value="'.$perfectmoney["STATUS_URL"].'">
                      <input type="hidden" name="PAYMENT_URL" value="'.$perfectmoney["PAYMENT_URL"].'">
                      <input type="hidden" name="NOPAYMENT_URL" value="'.$perfectmoney["NOPAYMENT_URL"].'">
                      <input type="hidden" name="BAGGAGE_FIELDS" value="'.$perfectmoney["BAGGAGE_FIELDS"].'">
                      <input type="hidden" name="ORDER_NUM" value="'.$perfectmoney["ORDER_NUM"].'">
                      <input type="hidden" name="CUST_NUM" value="'.$perfectmoney["CUST_NUM"].'">
                      <input type="hidden" name="PAYMENT_ID" value="'.$perfectmoney["PAYMENT_ID"].'>
                      <input type="hidden" name="PAYMENT_URL_METHOD" value="POST">
                      <input type="hidden" name="NOPAYMENT_URL_METHOD" value="POST">
                      <input type="hidden" name="SUGGESTED_MEMO" value="'.$perfectmoney["memo"].'">
                      <script type="text/javascript">
                        document.getElementById("redirection_form").submit();
                      </script>
                    </form>
                  </div>
                </div>';
            
        
            elseif ($method_id == 14):
                 require_once($_SERVER['DOCUMENT_ROOT']."/lib/paytm/encdec_paytm.php");
    
                    $icid = $paytmqr_orderid;
                    //$icid = "ORDS57382437";
    
                    $TXN_AMOUNT = $amount;
            
                    $responseParamList = array();

                	$requestParamList = array();

                	$requestParamList = array("MID" => $extra['merchant_mid'] , "ORDERID" => $icid);  
                	
                    if (!countRow(['table' => 'payments', 'where' => ['client_id' => $user['client_id'], 'payment_method' => 14, 'payment_status' => 3, 'payment_delivery' => 2, 'payment_extra' => $icid]]) &&
                	!countRow(['table' => 'payments', 'where' => ['payment_extra' => $icid]])) {
                        $responseParamList = getTxnStatusNew($requestParamList);

                        if($amount == $responseParamList["TXNAMOUNT"]) {
    
    
                            $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra");
                            $insert->execute(array("c_id" => $user['client_id'], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $icid));
                            $success = 1;
                            $successText = "Your payment was initiated successfully, you are being redirected..";
                            
                            echo '<form method="post" action="'.site_url('payment/paytmqr').'" name="f1">
                            		<table border="1">
                            			<tbody>';
                            			foreach($requestParamList as $name => $value) {
                            				echo '<input type="hidden" name="' .$name.'" value="' .$value .'">';
                            			}
                            			echo '</tbody>
                            			</table>
                            		<script type="text/javascript">
                            			document.f1.submit();
                            		</script>
                            	</form>';
                            	
                        }else{
                    	    $error = 1;
                            $errorText = "Amount is invalid";
                	    }
                        	
                	}else{
                	    $error = 1;
                        $errorText = "This transaction id is already used";
                	}
                    	
elseif ($method_id == 19):
                
                include( $_SERVER['DOCUMENT_ROOT']."/vendor/pix_auto/autoload.php"); 
                
                $access_token = $extra['access_token'];
                
                $cp_amount = str_replace(',', '.', $amount_fee);
                
                $ref = md5(uniqid());
                
                                 
                $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra");
                $insert->execute(array("c_id" => $user['client_id'], "amount" => $cp_amount, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $ref));

                
                MercadoPago\SDK::setAccessToken($access_token);

                  $payment = new MercadoPago\Payment();
                  $payment->description = 'Recarga de saldo para '.$user['username'];
                  $payment->transaction_amount = (double)$cp_amount;
                  $payment->payment_method_id = "pix";
        
                  $payment->notification_url   = site_url('lib/pix_auto/index.php');
                  $payment->external_reference = $ref;
        
                  $payment->payer = array(
                      "email" => $user["email"],
                      "first_name" => explode(' ',$user["name"])[0],
                      "address"=>  array(
                          "zip_code" => "74180-040",
                          "street_name" => "Av. 136",
                          "street_number" => "960",
                          "neighborhood" => "St. Marista",
                          "city" => "Goiânia",
                          "federal_unit" => "GO"
                       )
                    );
        
                  $payment->save();
        
    
                  //echo json_encode($payment->point_of_interaction);
 
                  echo '
                  
                  <style>
                    .wrapper-content{
                        display:none!important;
                    }
                  </style>
                  
                       <div class="row" style="margin-top: 90px;">
                            <div class="col-md-12 text-center">
      
                              <div id="div_pix" class="row">
                                <div class="col-md-12">
                                  <h3><span style="font-weight: bold; color: rgb(239, 239, 239);">Estamos aguardando a confimação do pagamento...</span></h3>
                                </div>
                                <div class="col-md-12">
                                  <img src="data:image/jpg;base64,'.$payment->point_of_interaction->transaction_data->qr_code_base64.'" id="img-pix" class="img-thumbnail" width="20%" alt="">
                                </div>
                                <div class="col-md-12">
                                  <h4><span style="font-weight: bold; color: rgb(239, 239, 239);">Você pode copiar o código abaixo também</span></h4>
                                </div>
                                <div class="col-md-12 text-center">
                                  <center>
                                    <textarea id="codigo-pix" style="width: 400px;height: 109px;" rows="8" class="form-control" name="" rows="8" cols="80">'.$payment->point_of_interaction->transaction_data->qr_code.'</textarea>
                                  </center>
                                  <button onclick="$(\'#codigo-pix\').select();document.execCommand(\'copy\');" style="margin-top:10px;" type="button" class="btn btn-sm btn-big-ter text-light" name="button">
                                    <h4><span style="font-weight: bold; color: rgb(239, 239, 239);">Copiar</span></h4>
                                  </button>
                                </div>
                                <div class="col-md-12">
                                  <br><br>
                                  <p><span style="color: rgb(239, 239, 239); font-weight: bold;">Pague e receba o saldo automáticamente.</span></p>
                                </div>
                              </div>
                              <div class="new-order-button-submit component_button_submit "><button type="submit" class="btn btn-primary" style="font-style: normal; font-weight: 700; font-size: 16px; line-height: 1.5; background: rgb(255 255 255); padding-top: 12px; padding-bottom: 12px; border-width: 0px; border-style: none; border-color: rgb(0, 0, 0); border-radius: 12px; transition: color 0.15s ease-in-out 0s, background-color 0.15s ease-in-out 0s, border-color 0.15s ease-in-out 0s, box-shadow 0.15s ease-in-out 0s; letter-spacing: 0px; box-shadow: rgba(104, 206, 212, 0.24) 0px 8px 24px 0px; width: 82.98400000000004px;"><a href="/">Voltar</a></button></div>
                        
                            </div>
                        </div>
                  
                  ';
                    
            elseif ($method_id == 15):
                unset($_SESSION["data"]);
                $rp_amount_fee = str_replace(',', '.', $amount_fee);
                $icid = md5(rand(1,999999));
                $getcur = $extra['currency'];
                $lastcur = isset($currentcur->error) ? defined($getcur.'_') ? constant($getcur.'_') : die('There\'s a problem with currency. Please contact with admin.') : $currentcur->rates->$getcur;
                $getamo = number_format($rp_amount_fee * $lastcur, 2, '.', '');
                $jsondata = json_encode(array('c' => $getcur, 'a' => $getamo));
                
                $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra, data=:data");
                $insert->execute(array("c_id" => $user['client_id'], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $icid, "data" => $jsondata));
                if ($insert):
                    $success = 1;
                    $successText = "Your payment was initiated successfully, you are being redirected..";
                    $payment_url = site_url('lib/pay/razorpay-payment.php?hash='.$icid);
                else:
                    $error = 1;
                    $errorText = "There was an error starting your payment, please try again later..";
                endif;
            elseif ($method_id == 16):
                unset($_SESSION["data"]);
                $ic_amount_fee = str_replace(',', '.', $amount_fee);
                $icid = md5(rand(1,999999));
                $getcur = $extra['currency'];
                $lastcur = isset($currentcur->error) ? defined($getcur.'_') ? constant($getcur.'_') : die('There\'s a problem with currency. Please contact with admin.') : $currentcur->rates->$getcur;
                $getamo = number_format($ic_amount_fee * $lastcur, 2, '.', '');
                $jsondata = json_encode(array('c' => $getcur, 'a' => $getamo));
                
                $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra, data=:data");
                $insert->execute(array("c_id" => $user['client_id'], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $icid, "data" => $jsondata));
                if ($insert):
                    $success = 1;
                    $successText = "Your payment was initiated successfully, you are being redirected..";
                    $payment_url = site_url('lib/pay/iyzico-payment.php?hash='.$icid);
                else:
                    $error = 1;
                    $errorText = "There was an error starting your payment, please try again later..";
                endif;
            elseif ($method_id == 17):
                unset($_SESSION["data"]);
                $ae_amount_fee = str_replace(',', '.', $amount_fee);
                $icid = md5(rand(1,999999));
                $getcur = $extra['currency'];
                $lastcur = isset($currentcur->error) ? defined($getcur.'_') ? constant($getcur.'_') : die('There\'s a problem with currency. Please contact with admin.') : $currentcur->rates->$getcur;
                $getamo = number_format($ae_amount_fee * $lastcur, 2, '.', '');
                $jsondata = json_encode(array('c' => $getcur, 'a' => $getamo));
                
                $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra, data=:data");
                $insert->execute(array("c_id" => $user['client_id'], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $icid, "data" => $jsondata));
                if ($insert):
                    $success = 1;
                    $successText = "Your payment was initiated successfully, you are being redirected..";
                    $payment_url = site_url('lib/pay/authorize-net-payment.php?hash='.$icid);
                else:
                    $error = 1;
                    $errorText = "There was an error starting your payment, please try again later..";
                endif;
            
            
            
             elseif ($method_id == 22):
                unset($_SESSION["data"]);
                /*require_once("lib/encdec_paytm.php");*/
                $icid = md5(rand(1,999999));
                $getcur = $extra['currency'];
                $lastcur = 1;
                $ptm_amount = str_replace(',', '.', $amount_fee);
              
                $paramList = array();

                $paramList["amount"] = number_format($ptm_amount * $lastcur, 2, '.', '');
                $paramList["currency"] = $getcur;
                $paramList["succes_url"] = site_url('payment/cashmaal');
                $paramList["cancel_url"] = site_url('payment/cashmaal');
                $paramList["client_email"] = $user['email'];
                $paramList["web_id"] = $extra['web_id'];
                $paramList["order_id"] = $icid;
                unset($_SESSION["data"]);
                if($extra['web_id']==''):
                    $error = 1;
                    $errorText = "Admin has not configured this gateway yet..";
                else:
                    //haina
                    $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra");
                    $insert->execute(array("c_id" => $user['client_id'], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $icid));
                    if($insert):
                        $success = 1;
                        $successText = "Your payment was initiated successfully, you are being redirected..";
                        echo '<form method="post" action="https://www.cashmaal.com/Pay/" name="f2">
                        <table border="1">
                            <tbody>
                             <input type="hidden" name="pay_method" value="">';
                            foreach($paramList as $name => $value) {
                                echo '<input type="hidden" name="' . $name .'" value="' . $value . '">';
                            }
                            echo '<input type="hidden" name="addi_info" value="eg. John Domain renewal payment">';
                            echo '<center>Redirecting...<input type="hidden" name="Submit" value="Pay With Cash-Maal"></center>
                            </tbody>
                        </table>
                        <script type="text/javascript">
                            document.f2.submit();
                        </script>
                        </form>';
                        
                    else:
                        $error = 1;
                        $errorText = "There was an error starting your payment, please try again later..";
                
                    endif;
             
                endif;
            
            elseif ($method_id == 7):
                $merchant_id = $extra["merchant_id"];
                $merchant_key = $extra["merchant_key"];
                $merchant_salt = $extra["merchant_salt"];
                $email = $user["email"];
                $payment_amount = $amount_fee * 100;
                $merchant_oid = rand(9999,9999999);
                $user_name = $user["name"];
                $user_address = "Belirtilmemiş";
                $user_phone = $user["telephone"];
                $payment_type = "eft";
                $user_ip = GetIP();
                $timeout_limit = "360";
                $debug_on = 1;
                $test_mode = 0;
                $no_installment = 0;
                $max_installment = 0;
                $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $payment_type . $test_mode;
                $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));
                $post_vals = array('merchant_id' => $merchant_id, 'user_ip' => $user_ip, 'merchant_oid' => $merchant_oid, 'email' => $email, 'payment_amount' => $payment_amount, 'payment_type' => $payment_type, 'paytr_token' => $paytr_token, 'debug_on' => $debug_on, 'timeout_limit' => $timeout_limit, 'test_mode' => $test_mode);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);
                $result = @curl_exec($ch);
                if (curl_errno($ch)) die("PAYTR IFRAME connection error. err:" . curl_error($ch));
                curl_close($ch);
                $result = json_decode($result, 1);
                if ($result['status'] == 'success'):
                    unset($_SESSION["data"]);
                    $token = $result['token'];
                    $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra");
                    $insert->execute(array("c_id" => $user["client_id"], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $merchant_oid));
                    $success = 1;
                    $successText = "Your payment was initiated successfully, you are being redirected..";
                    $payment_url = "https://www.paytr.com/odeme/api/" . $token;
                else:
                    $error = 1;
                    $errorText = "There was an error starting your payment, please try again later..";
                endif;
                

                  
            elseif ($method_id == 4):
                $getcur = $extra['currency'];
                $lastcur = isset($currentcur->error) ? defined($getcur.'_') ? constant($getcur.'_') : die('There\'s a problem with currency. Please contact with admin.') : $currentcur->rates->$getcur;
                $merchant_id = $extra["merchant_id"];
                $merchant_key = $extra["merchant_key"];
                $merchant_salt = $extra["merchant_salt"];
                $email = $user["email"];
                $payment_amount = $amount_fee * 100;
                $payment_amount = str_replace(',','',number_format($payment_amount * $lastcur));
                $merchant_oid = rand(9999,9999999);
                $user_name = $user["name"];
                $user_address = "Belirtilmemiş";
                $user_phone = $user["telephone"];
                $currency = $extra['currency'];
                $merchant_ok_url = URL;
                $merchant_fail_url = URL;
                $user_basket = base64_encode(json_encode(array(array($amount . " " . $currency . " Bakiye", $amount_fee, 1))));
                $user_ip = GetIP();
                $timeout_limit = "360";
                $debug_on = 1;
                $test_mode = 0;
                $no_installment = 0;
                $max_installment = 0;
                $hash_str = $merchant_id . $user_ip . $merchant_oid . $email . $payment_amount . $user_basket . $no_installment . $max_installment . $currency . $test_mode;
                $paytr_token = base64_encode(hash_hmac('sha256', $hash_str . $merchant_salt, $merchant_key, true));
                $post_vals = array('merchant_id' => $merchant_id, 'user_ip' => $user_ip, 'merchant_oid' => $merchant_oid, 'email' => $email, 'payment_amount' => $payment_amount, 'paytr_token' => $paytr_token, 'user_basket' => $user_basket, 'debug_on' => $debug_on, 'no_installment' => $no_installment, 'max_installment' => $max_installment, 'user_name' => $user_name, 'user_address' => $user_address, 'user_phone' => $user_phone, 'merchant_ok_url' => $merchant_ok_url, 'merchant_fail_url' => $merchant_fail_url, 'timeout_limit' => $timeout_limit, 'currency' => $currency, 'test_mode' => $test_mode);
                $ch = curl_init();
                curl_setopt($ch, CURLOPT_URL, "https://www.paytr.com/odeme/api/get-token");
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $post_vals);
                curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
                curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
                curl_setopt($ch, CURLOPT_FRESH_CONNECT, true);
                curl_setopt($ch, CURLOPT_TIMEOUT, 20);
                $result = @curl_exec($ch);
                if (curl_errno($ch)) die("PAYTR IFRAME connection error. err:" . curl_error($ch));
                curl_close($ch);
                $result = json_decode($result, 1);
                if ($result['status'] == 'success'):
                    unset($_SESSION["data"]);
                    $token = $result['token'];
                    $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip, payment_extra=:extra");
                    $insert->execute(array("c_id" => $user["client_id"], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP(), "extra" => $merchant_oid));
                    $success = 1;
                    $successText = "Your payment was initiated successfully, you are being redirected..";
                    $payment_url = "https://www.paytr.com/odeme/guvenli/" . $token;
                else:
                    $error = 1;
                    $errorText = "There was an error starting your payment, please try again later..";
                endif;
            elseif ($method_id == 5):
                $getcur = $extra['currency'];
                $lastcur = isset($currentcur->error) ? defined($getcur.'_') ? constant($getcur.'_') : die('There\'s a problem with currency. Please contact with admin.') : $currentcur->rates->$getcur;
                $payment_types = "";
                foreach ($extra["payment_type"] as $i => $v) {
                    $payment_types.= $v . ",";
                }
                $amount_fee = $amount_fee * 100;
                $amount_fee = number_format($amount_fee * $lastcur, 2, '.', '');
                $payment_types = substr($payment_types, 0, -1);
                $hashOlustur = base64_encode(hash_hmac('sha256', $user["email"] . "|" . $user["email"] . "|" . $user['client_id'] . $extra['apiKey'], $extra['apiSecret'], true));
                $postData = array('apiKey' => $extra['apiKey'], 'hash' => $hashOlustur, 'returnData' => $user["email"], 'userEmail' => $user["email"], 'userIPAddress' => GetIP(), 'userID' => $user["client_id"], 'proApi' => TRUE, 'productData' => ["name" => $amount . $settings['currency'] . " Tutarında Bakiye (" . $paymentCode . ")", "amount" => $amount_fee, "extraData" => $paymentCode, "paymentChannel" => $payment_types,
                "commissionType" => $extra["commissionType"]
                ]);
                $curl = curl_init();
                curl_setopt_array($curl, array(CURLOPT_URL => "http://api.paywant.com/gateway.php", CURLOPT_RETURNTRANSFER => true, CURLOPT_ENCODING => "", CURLOPT_MAXREDIRS => 10, CURLOPT_TIMEOUT => 30, CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1, CURLOPT_CUSTOMREQUEST => "POST", CURLOPT_POSTFIELDS => http_build_query($postData),));
                $response = curl_exec($curl);
                $err = curl_error($curl);
                if (!$err):
                    $jsonDecode = json_decode($response, false);
                    if ($jsonDecode->Status == 100):
                        if (!strpos($jsonDecode->Message, "https")) $jsonDecode->Message = str_replace("http", "https", $jsonDecode->Message);
                        unset($_SESSION["data"]);
                        $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip ");
                        $insert->execute(array("c_id" => $user["client_id"], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP()));
                        $success = 1;
                        $successText = "Your payment was initiated successfully, you are being redirected..";
                        $payment_url = $jsonDecode->Message;
                    else:
                        //echo $response; // Dönen hatanın ne olduğunu bastır
                        $error = 1;
                        $errorText = "There was an error starting your payment, please try again later.." . $response;
                    endif;

                  
                else:
                    $error = 1;
                    $errorText = "There was an error starting your payment, please try again later..";
                endif;
            elseif ($method_id == 3):
                if ($extra["processing_fee"]):
                    $amount_fee = $amount_fee + "0.49";
                endif;
                $getcur = $extra['currency'];
                $lastcur = isset($currentcur->error) ? defined($getcur.'_') ? constant($getcur.'_') : die('There\'s a problem with currency. Please contact with admin.') : $currentcur->rates->$getcur;
                $form_data = ["website_index" => $extra["website_index"], "apikey" => $extra["apiKey"], "apisecret" => $extra["apiSecret"], "item_name" => "Bakiye Ekleme", "order_id" => $paymentCode, "buyer_name" => $user["name"], "buyer_surname" => " ", "buyer_email" => $user["email"], "buyer_phone" => $user["telephone"], "city" => "NA", "billing_address" => "NA", "ucret" => number_format($amount_fee * $lastcur, 2, '.', '')];
                print_r(generate_shopier_form(json_decode(json_encode($form_data))));
                if ($_SESSION["data"]["payment_shopier"] == true):
                    $insert = $conn->prepare("INSERT INTO payments SET client_id=:c_id, payment_amount=:amount, payment_privatecode=:code, payment_method=:method, payment_mode=:mode, payment_create_date=:date, payment_ip=:ip ");
                    $insert->execute(array("c_id" => $user['client_id'], "amount" => $amount, "code" => $paymentCode, "method" => $method_id, "mode" => "Auto", "date" => date("Y.m.d H:i:s"), "ip" => GetIP()));
                    $success = 1;
                    $successText = "Your payment was initiated successfully, you are being redirected..";
                    $payment_url = $response;
                    unset($_SESSION["data"]);
                else:
                    $error = 1;
                    $errorText = "There was an error starting your payment, please try again later..";
                endif;
            endif;
        }
    endif;
    if ($payment_url):
        echo '<script>setInterval(function(){window.location="' . $payment_url . '"},2000)</script>';
    endif;