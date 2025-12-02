<?php

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Api_Whatsapp
{
  function wa_notif($msgg, $phone)
  {

    $token = "FB02586BE9E8449E84C9AA49021B99E3";

    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://app.fastwa.com/api/v1/954F277620EF33D443167C2E131D320C/send_text',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => array('api_key' => $token, 'phone' => $phone, 'message' => $msgg),

    ));
    $response = curl_exec($curl);
    curl_close($curl);
    return $response;
  }
}
