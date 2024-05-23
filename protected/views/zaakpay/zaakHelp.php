<?php

/* -------------------------------------------------------+
  | Zaakpay Payment gateway integration - Opencart
  | http://www.kvcodes.com//2014/01/zaakpay-payment-gateway-integration-opencart-2/
  +--------------------------------------------------------+
  | Author: Varadharaj V
  | Email: varadharajv@kvcodes.com
  +--------------------------------------------------------+ */

class ControllerPaymentZaakpay extends Controller {

    protected function index() {
        $this->data['button_confirm'] = $this->language->get('button_confirm');
        $this->data['button_back'] = $this->language->get('button_back');

        $order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $this->data['action'] = 'https://api.zaakpay.com/transact';
        $this->data['merchantIdentifier'] = $this->config->get('Zaakpay_merchantIdentifier');
        $secret_key = $this->config->get('Zaakpay_secret_key');
        $merchantIdentifier = $this->config->get('Zaakpay_merchantIdentifier');
        $ip = $_SERVER['REMOTE_ADDR'];
        $merchantIpAddress = $ip;
        $this->data['secret_key'] = $this->config->get('Zaakpay_secret_key');
        $this->data['orderId'] = $this->session->data['order_id'];
        $orderId = $this->data['orderId'];
        $this->data['returnUrl'] = HTTPS_SERVER . 'index.php?route=common/response';
        $returnUrl = HTTPS_SERVER . 'index.php?route=common/response';
        $this->data['buyerEmail'] = $order_info['email'];
        $buyerEmail = $order_info['email'];
        $this->data['buyerFirstName'] = $order_info['payment_firstname'];
        $buyerFirstName = $order_info['payment_firstname'];

        $this->data['buyerLastName'] = $order_info['payment_lastname'];
        $buyerLastName = $order_info['payment_lastname'];

        $this->data['buyerAddress'] = $order_info['payment_address_1'];
        $buyerAddress = $order_info['payment_address_1'];

        $this->data['buyerCity'] = $order_info['payment_city'];
        $buyerCity = $order_info['payment_city'];
        $this->data['buyerState'] = $order_info['payment_zone'];
        $buyerState = $order_info['payment_zone'];
        $buyerCountry = $order_info['payment_country'];
        $buyerPincode = $order_info['payment_postcode'];
        $buyerPhoneNumber = $order_info['telephone'];
        if ($this->config->get('Zaakpay_test') == "on") {
            $mode = '0';
        } else {
            $mode = '1';
        }
        $this->data['buyerCountry'] = $order_info['payment_country'];
        $this->data['buyerPincode'] = $order_info['payment_postcode'];
        $this->data['buyerPhoneNumber'] = $order_info['telephone'];
        $this->data['txnType'] = "1";
        $this->data['zpPayOption'] = "1";
        if ($this->config->get('Zaakpay_test') == "on")
            $this->data['mode'] = '0';
        else
            $this->data['mode'] = '1';
        $this->data['currency'] = "INR";
        $this->data['amount'] = (int) $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false) * 100;
        $this->data['merchantIpAddress'] = $ip;
        $this->data['purpose'] = "1";
        $this->data['productDescription'] = $this->session->data['payment_method']['title'];
        $txnDate = date('Y-m-d');
        $this->data['txnDate'] = date('Y-m-d');
        $amount = (int) $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value'], false) * 100;
        $txnType = '1';
        $zpPayOption = '1';
        $currency = "INR";
        $purpose = "1";
        $productDescription = $this->session->data['payment_method']['title'];
        /* 		
          if(isset($this->session->data['shipping_address_id'])){
          $shipping_address_id = $this->session->data['shipping_address_id'];
          $shipping_address = $this->model_account_address->getAddress($shipping_address_id);

          $this->data['ShipToAddress']	= $shipping_address['address_1'].",".$shipping_address['address_2'];
          $this->data['ShipToCity']  	= $shipping_address['city'];
          $this->data['ShipToState']  	= $shipping_address['zone'];
          $this->data['ShipToCountry']   	= $shipping_address['country_id'];//Iso 3 char code is supported
          $this->data['ShipToPincode'] = $shipping_address['postcode'];
          $this->data['ShipToPhoneNumber']   	= $order_info['telephone'];
          $this->data['ShipToFirstname']	= $shipping_address['firstname'];
          $this->data['ShipToLastname']	= $shipping_address['lastname'];
          }
          else{
          $this->data['ShipToAddress']	= $order_info['payment_address_1'].",".$order_info['payment_address_2'];
          $this->data['ShipToCity']  	= $order_info['payment_city'];
          $this->data['ShipToState']  	= $order_info['payment_zone'];
          $this->data['ShipToCountry']   	=  $order_info['payment_country_id'];//Iso 3 char code is supported
          $this->data['ShipToPincode'] = $order_info['payment_postcode'];
          $this->data['ShipToPhoneNumber']   	=  $order_info['telephone'];
          $this->data['ShipToFirstname']	= $order_info['payment_firstname'];
          $this->data['ShipToLastname']	= $order_info['payment_lastname'];
          }

          //$product1Description = '';
          //$product2Description = '';
          //$product3Description = '';
          //$product4Description = '';

          if(isset($this->session->data['shipping_address_id'])){
          $shipping_address_id = $this->session->data['shipping_address_id'];
          $shipping_address = $this->model_account_address->getAddress($shipping_address_id);
          $ShipToAddress =$shipping_address['address_1'].",".$shipping_address['address_2'];
          $ShipToCity = $shipping_address['city'];
          $ShipToState = $shipping_address['zone'];
          $ShipToCountry = $shipping_address['country'];
          $ShipToPincode = $shipping_address['postcode'];
          $ShipToPhoneNumber = $order_info['telephone'];
          $ShipToFirstname = 	$shipping_address['firstname'];
          $ShipToLastname = $shipping_address['lastname'];
          }
          else{

          $ShipToAddress	= $order_info['payment_address_1'].",".$order_info['payment_address_2'];
          $ShipToCity  	= $order_info['payment_city'];
          $ShipToState  	= $order_info['payment_zone'];
          $ShipToCountry   	=  $order_info['payment_country'];//Iso 3 char code is supported
          $ShipToPincode = $order_info['payment_postcode'];
          $ShipToPhoneNumber   	=  $order_info['telephone'];
          $ShipToFirstname	= $order_info['payment_firstname'];
          $ShipToLastname	= $order_info['payment_lastname'];
          }

         */
        $post_variables = Array(
            "merchantIdentifier" => $merchantIdentifier,
            "orderId" => $orderId,
            "returnUrl" => $returnUrl,
            "buyerEmail" => $buyerEmail,
            "buyerFirstName" => $buyerFirstName,
            "buyerLastName" => $buyerLastName,
            "buyerAddress" => $buyerAddress,
            "buyerCity" => $buyerCity,
            "buyerState" => $buyerState,
            "buyerCountry" => $buyerCountry,
            "buyerPincode" => $buyerPincode,
            "buyerPhoneNumber" => $buyerPhoneNumber,
            "txnType" => $txnType,
            "zpPayOption" => $zpPayOption,
            "mode" => $mode,
            "currency" => $currency,
            "amount" => $amount, //Amount should be in paisa
            "merchantIpAddress" => $merchantIpAddress,
            "purpose" => $purpose,
            "productDescription" => $productDescription,
            /*
              "ShipToAddress" => $ShipToAddress,
              "ShipToCity" => $ShipToCity,
              "ShipToState" => $ShipToState,
              "ShipToCountry" => $ShipToCountry,
              "ShipToPincode" => $ShipToPincode,
              "ShipToPhoneNumber" => $ShipToPhoneNumber,
              "ShipToFirstname" => $ShipToFirstname,
              "ShipToLastname" => $ShipToLastname,
             */
            "txnDate" => $txnDate
        );

        $all = '';
        foreach ($post_variables as $name => $value) {
            if ($name != 'button_confirm') {
                if ($name != 'button_back') {
                    if ($name != 'action') {
                        if ($name != 'secret_key') {
                            if ($name != 'checksum') {
                                $all .= "'";
                                if ($name == 'returnUrl') {
                                    $all .= ControllerPaymentZaakpay::sanitizedURL($value);
                                } else {

                                    $all .= ControllerPaymentZaakpay::sanitizedParamFn($value);
                                }
                                $all .= "'";
                            }
                        }
                    }
                }
            }
        }

        foreach ($this->data as $name => $value) {
            if ($name != 'button_confirm') {
                if ($name != 'button_back') {
                    if ($name != 'action') {
                        if ($name != 'secret_key') {
                            if ($name != 'checksum') {
                                if ($name == 'returnUrl') {
                                    $this->data[$name] = ControllerPaymentZaakpay::sanitizedURL($value);
                                } else {
                                    $this->data[$name] = ControllerPaymentZaakpay::sanitizedParamFn($value);
                                }
                            }
                        }
                    }
                }
            }
        }

        if ($this->config->get('Zaakpay_log') == "on") {
            error_log("AllParams : " . $all);
            error_log("Secret Key : " . $secret_key);
        }

        $checksum = ControllerPaymentZaakpay::calculateChecksum($secret_key, $all);
        $this->data['checksum'] = $checksum;

        $this->id = 'payment';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/Zaakpay.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/Zaakpay.tpl';
        } else {
            $this->template = 'default/template/payment/Zaakpay.tpl';
        }

        $this->render();
    }

    function calculateChecksum($secret_key, $all) {
        $hash = hash_hmac('sha256', $all, $secret_key);
        $checksum = $hash;
        return $checksum;
    }

    function verifyChecksum($checksum, $all, $secret_key) {
        $cal_checksum = ControllerPaymentZaakpay::calculateChecksum($secret_key, $all);
        $bool = 0;
        if ($checksum == $cal_checksum) {
            $bool = 1;
        }

        return $bool;
    }

    function sanitizedParamFn($param) {
        $pattern[0] = "%,%";
        $pattern[1] = "%#%";
        $pattern[2] = "%\(%";
        $pattern[3] = "%\)%";
        $pattern[4] = "%\{%";
        $pattern[5] = "%\}%";
        $pattern[6] = "%<%";
        $pattern[7] = "%>%";
        $pattern[8] = "%`%";
        $pattern[9] = "%!%";
        $pattern[10] = "%\\$%";
        $pattern[11] = "%\%%";
        $pattern[12] = "%\^%";
        $pattern[13] = "%=%";
        $pattern[14] = "%\+%";
        $pattern[15] = "%\|%";
        $pattern[16] = "%\\\%";
        $pattern[17] = "%:%";
        $pattern[18] = "%'%";
        $pattern[19] = "%\"%";
        $pattern[20] = "%;%";
        $pattern[21] = "%~%";
        $pattern[22] = "%\[%";
        $pattern[23] = "%\]%";
        $pattern[24] = "%\*%";
        $pattern[25] = "%&%";
        $sanitizedParam = preg_replace($pattern, "", $param);
        return $sanitizedParam;
    }

    function sanitizedURL($param) {
        $pattern[0] = "%,%";
        $pattern[1] = "%\(%";
        $pattern[2] = "%\)%";
        $pattern[3] = "%\{%";
        $pattern[4] = "%\}%";
        $pattern[5] = "%<%";
        $pattern[6] = "%>%";
        $pattern[7] = "%`%";
        $pattern[8] = "%!%";
        $pattern[9] = "%\\$%";
        $pattern[10] = "%\%%";
        $pattern[11] = "%\^%";
        $pattern[12] = "%\+%";
        $pattern[13] = "%\|%";
        $pattern[14] = "%\\\%";
        $pattern[15] = "%'%";
        $pattern[16] = "%\"%";
        $pattern[17] = "%;%";
        $pattern[18] = "%~%";
        $pattern[19] = "%\[%";
        $pattern[20] = "%\]%";
        $pattern[21] = "%\*%";
        $sanitizedParam = preg_replace($pattern, "", $param);
        return $sanitizedParam;
    }

}

?>