<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class mpesaController extends Controller
{
    //API endpoints

    public function getAccessToken()
    {
  
    $consumer_key=env('MPESA_CONSUMER_KEY');
    $consumer_secret=env('MPESA_CONSUMER_SECRET');
       
    $credentials = base64_encode($consumer_key.":".$consumer_secret);
    $url = env('MPESA_ENV')==0?'https://sandbox.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials':
    'https://api.safaricom.co.ke/oauth/v1/generate?grant_type=client_credentials';
    $curl = curl_init();
    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_HTTPHEADER, array("Authorization: Basic ".$credentials));
    curl_setopt($curl, CURLOPT_HEADER,false);
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
    $curl_response = curl_exec($curl);
    $access_token=json_decode($curl_response);
    return $access_token->access_token;
  
    }
    public function makeHttp($url, $body)
    {
       
        $curl = curl_init();
        curl_setopt_array(
            $curl,
            array(
                    CURLOPT_URL => $url,
                    CURLOPT_HTTPHEADER => array('Content-Type:application/json','Authorization:Bearer '. $this->getAccessToken()),
                    CURLOPT_RETURNTRANSFER => true,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => json_encode($body)
                )
        );
        $curl_response = curl_exec($curl);
        curl_close($curl);
        return $curl_response;
    } 
    public function registerURLS()
    {
        $test_url = env('MPESA_TEST_URL');
        $body = array(
            'ShortCode'=> env('MPESA_SHORTCODE'),
            'ResponseType' => 'Completed',
            'ConfirmationURL'=>$test_url.'/api/confirmation',
            'ValidationURL'=>$test_url.'/api/validation'
        );
        $url= env('MPESA_ENV')==0?'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/registerurl'
        :'https://api.safaricom.co.ke/mpesa/c2b/v1/registerurl';
      
        $response = $this->makeHttp($url, $body);

        return $response;
    }  
    public function simulateTransaction(Request $request)
    {
        $body = array(
            'ShortCode'=>env('MPESA_SHORTCODE'),
            'Msisdn' => '254708374149',
            'Amount' => $request->amount,
            'BillRefNumber' => $request->account,
            'CommandID' => 'CustomerPayBillOnline'
        );

       
        $url= env('MPESA_ENV')==0?'https://sandbox.safaricom.co.ke/mpesa/c2b/v1/simulate'
        :'https://api.safaricom.co.ke/mpesa/c2b/v1/simulate';
      
        $response = $this->makeHttp($url, $body);

        return $response;
    }
    public function stkPush(Request $request)
    {
        $test_url = env('MPESA_TEST_URL');
        $timestamp = date('YmdHis');
        $password = env('MPESA_STK_SHORTCODE').env('MPESA_PASSKEY').$timestamp;

        $curl_post_data = array(
            'BusinessShortCode' => env('MPESA_STK_SHORTCODE'),
            'Password' => $password,
            'Timestamp' => $timestamp,
            'TransactionType' => 'CustomerPayBillOnline',
            'Amount' => $request->amount,
            'PartyA' => $request->phone,
            'PartyB' => env('MPESA_STK_SHORTCODE'),//paybill
            'PhoneNumber' => $request->phone,
            'CallBackURL' => $test_url.'/api/stkpush',
            'AccountReference' => 'test',
            'TransactionDesc' => 'Payment'
          );
        $url= env('MPESA_ENV')==0?'https://sandbox.safaricom.co.ke/mpesa/stkpush/v1/processrequest'
          :'https://api.safaricom.co.ke/mpesa/stkpush/v1/processrequest';
        

        $response = $this->makeHttp($url, $curl_post_data);

        return $response;
    }
}
