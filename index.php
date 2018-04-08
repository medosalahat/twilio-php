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

 if(isset($_POST) and isset($_POST['phone']) and !empty($_POST['phone']) and is_array($_POST['phone'])
     and isset($_POST['message']) and !empty($_POST['message'])){

    $data_list = [];
    $data  = require_once __DIR__.'/params.php';
    $sid = $data['id']; // Your Account SID from www.twilio.com/console
    $token = $data['token']; // Your Auth Token from www.twilio.com/console
     $message_data = $_POST['message'];
    $client = new Client($sid, $token);
    foreach ($_POST['phone'] as $index=>$item) {

        if( (is_array($item) and !empty($item)) and !empty($message_data)){
            try{
                $message = $client->messages->create(
                   // '+962798981496', // Text this number
                    $item, // Text this number
                    [
                        'from' => $data['phone'], // From a valid Twilio number
                        'body' => $message_data
                    ]
                );
                $data_list[$index]=[
                    'phone'=>$item,
                    'message'=>$message_data,
                     'sid'=>$message->sid,
                    'send'=>true,
                    'error'=>'Sms delivery success'
                ];

            }catch (EnvironmentException $e){
                $data_list[$index]=[
                    'phone'=>$item,
                    'message'=>$message_data,
                    'sid'=>null,
                    'send'=>false,
                    'error'=>$e->getMessage()
                ];
            }
        }else{
            $data_list[$index]=[
                'phone'=>$item,
                'message'=>$message_data,
                'sid'=>null,
                'send'=>false,
                'error'=>' please add phone as array and message  as string to request.'
            ];
        }
    }
    exit(json_encode($data_list));

}else{
    $json = json_encode([
        'status'=>400,
        'message'=>'parameter phone not found or is not a array'
    ]);
    exit($json);
}

