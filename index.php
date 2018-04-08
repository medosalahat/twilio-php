<?php
/**
 * Created by PhpStorm.
 * User: salahat
 * Date: 09/04/18
 * Time: 12:38 ص
 */
namespace sms;
ini_set('display_errors', true);
error_reporting(E_ALL);
require_once __DIR__.'/vendor/autoload.php';


use Twilio\Exceptions\EnvironmentException;
use Twilio\Rest\Client;

 if(isset($_POST) and isset($_POST['data']) and !empty($_POST['data']) and is_array($_POST['data'])){

    $data_list = [];
    $data  = require_once __DIR__.'/params.php';
    $sid = $data['id']; // Your Account SID from www.twilio.com/console
    $token = $data['token']; // Your Auth Token from www.twilio.com/console

    $client = new Client($sid, $token);
    foreach ($_POST['data'] as $index=>$item) {

        if( (is_array($item) and !empty($item)) and (isset($item['phone']) and isset($item['message']))){
            try{
                $message = $client->messages->create(
                   // '+962798981496', // Text this number
                    $item['phone'], // Text this number
                    [
                        'from' => $data['phone'], // From a valid Twilio number
                        'body' => $item['message']
                    ]
                );
                $data_list[$index]=[
                    'phone'=>$item['phone'],
                    'message'=>$item['message'],
                     'sid'=>$message->sid,
                    'send'=>true,
                    'error'=>'Sms delivery success'
                ];

            }catch (EnvironmentException $e){
                $data_list[$index]=[
                    'phone'=>$item['phone'],
                    'message'=>$item['message'],
                    'sid'=>null,
                    'send'=>false,
                    'error'=>$e->getMessage()
                ];
            }
        }else{
            $data_list[$index]=[
                'phone'=>(isset($item['phone']))?$item['phone']:null,
                'message'=>(isset($item['message']))?$item['message']:null,
                'sid'=>null,
                'send'=>false,
                'error'=>' please add phone and message index to row.'
            ];
        }
    }
    exit(json_encode($data_list));

}else{
    $json = json_encode([
        'status'=>400,
        'message'=>'parameter data not found or is not a array'
    ]);
    exit($json);
}

