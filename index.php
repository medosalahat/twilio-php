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
require_once __DIR__ . '/vendor/autoload.php';

use Twilio\Exceptions\EnvironmentException;
use Twilio\Rest\Client;

if (isset($_POST) and isset($_POST['phone']) and !empty($_POST['phone'])) {

    $data_list = [];
    $data = require_once __DIR__ . '/params.php';
    $sid = $data['id']; // Your Account SID from www.twilio.com/console
    $token = $data['token']; // Your Auth Token from www.twilio.com/console
    $code = rand(10000,20000);
    $message_data = ' Security code :  '.$code;
    $client = new Client($sid, $token);
    $phone = $_POST['phone'];


    if ((!empty($phone)) and !empty($message_data)) {
        try {
            $message = $client->messages->create(
            // '+962798981496', // Text this number
                $phone, // Text this number
                array(
                    'from' => $data['phone'], // From a valid Twilio number
                    'body' => $message_data
                )


            );
            $data_list = array(
                'status' => 200,
                'phone' => $phone,
                'message' => $message_data,
                'sid' => $message->sid,
                'send' => true,
                'response' => 'Sms delivery success'
            );

        } catch (EnvironmentException $e) {
            $data_list = array(
                'status' => 400,
                'phone' => $phone,
                'message' => $message_data,
                'sid' => null,
                'send' => false,
                'response' => $e->getMessage()
            );
        }
    } else {
        $data_list = array(
            'status' => 400,
            'phone' => $phone,
            'message' => $message_data,
            'sid' => null,
            'send' => false,
            'response' => ' please add phone as array and message  as string to request.'
        );
    }

    exit(json_encode($data_list));

} else {
    $json = json_encode([
        'status' => 400,
        'response' => 'parameter phone not found or message'
    ]);
    exit($json);
}

