<?php

use Twilio\Rest\Client;

class Sms_model extends CI_Model {

    public function send_user($to_number, $message, $user_id)
    {

        require_once('application/libraries/twilio/src/Twilio/autoload.php');
        // Use the REST API Client to make requests to the Twilio REST API
        $user = $this->common_model->get_by_id($user_id, 'users');
  
        // Your Account SID and Auth Token from twilio.com/console
        if (settings()->global_twilio == 1) {
            $sid = settings()->twillo_account_sid;
            $token = settings()->twillo_auth_token;
            $twillo_number = settings()->twillo_number;
        }else{
            $sid = $user->twillo_account_sid;
            $token = $user->twillo_auth_token;
            $twillo_number = $user->twillo_number;
        }

        $client = new Client($sid, $token);

        try{
            // Use the client to do fun stuff like send text messages!
            $message = $client->messages->create(
                // the number you'd like to send the message to
                $to_number,
                [
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => $twillo_number,
                    // the body of the text message you'd like to send
                    'body' => $message
                ]
            );
            return 1;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }



    public function send_whatsapp_user($to_number, $message, $company)
    {
        if (settings()->global_wapp_msg == 1) {
            if (settings()->whatsapp_type == 'ultramsg') {
                $token = settings()->ultramsg_token;
                $instance_id = settings()->ultramsg_instance_id;
                $this->ultramsg($to_number, $message, $instance_id, $token);
            } else {
                $token = settings()->wazfy_token;
                $instance_id = settings()->wazfy_instance_id;
                $this->wazfy($to_number, $message, $instance_id, $token);

            }
        }else{
            if (isset($company) && $company->whatsapp_type == 'ultramsg') {
                $token = $company->ultramsg_token;
                $instance_id = $company->ultramsg_instance_id;
                $this->ultramsg($to_number, $message, $instance_id, $token);
            } else {
                $token = $company->wazfy_token;
                $instance_id = $company->wazfy_instance_id;
                $this->wazfy($to_number, $message, $instance_id, $token);
            }
        }
    }


    public function ultramsg($to_number, $message, $instance_id, $token){
        $params=array(
            'token' => $token,
            'to' => $to_number,
            'body' => $message
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => "https://api.ultramsg.com/".$instance_id."/messages/chat",
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_SSL_VERIFYHOST => 0,
          CURLOPT_SSL_VERIFYPEER => 0,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => http_build_query($params),
          CURLOPT_HTTPHEADER => array(
            "content-type: application/x-www-form-urlencoded"
          ),
        ));

        $response = curl_exec($curl);
        $err = curl_error($curl);

        curl_close($curl);

        if ($err) {
          //echo "Error #:" . $err;
            return false;
        } else {
          //echo $response;
            return true;
        }
    }

    public function wazfy($to_number, $message, $instance_id, $token){
        
        $apiUrl = "https://wazfy.com/api/send";
        $params = array(
            "number" => str_replace( '+', '', $to_number),
            "type" => "text",
            "message" => $message,
            "instance_id" => $instance_id,
            "access_token" => $token,
        );
        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $apiUrl,
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => "",
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 30,
          CURLOPT_SSL_VERIFYHOST => 0,
          CURLOPT_SSL_VERIFYPEER => 0,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => "POST",
          CURLOPT_POSTFIELDS => json_encode($params),
          CURLOPT_HTTPHEADER => array(
            "content-type: application/json"
          ),
        ));

        if (curl_errno($curl)) {
            $error_msg = curl_error($curl);
            echo "<pre>";
            print_r($error_msg);
            exit();
        }
        $response = curl_exec($curl);
        $err = curl_error($curl);
        $result = json_decode($response);

        if($result->status=="success"){
            return true;
        }else{
            return false;
            //echo "<pre>"; print_r($response);
        }
    }


    public function send($to_number, $message)
    {

        require_once('application/libraries/twilio/src/Twilio/autoload.php');
        // Use the REST API Client to make requests to the Twilio REST API
        
        // Your Account SID and Auth Token from twilio.com/console
        if (settings()->global_twilio == 1) {
            $sid = settings()->twillo_account_sid;
            $token = settings()->twillo_auth_token;
            $twillo_number = settings()->twillo_number;
        }else{
            $sid = user()->twillo_account_sid;
            $token = user()->twillo_auth_token;
            $twillo_number = user()->twillo_number;
        }

        
        $client = new Client($sid, $token);

        try{
            // Use the client to do fun stuff like send text messages!
            $message = $client->messages->create(
                // the number you'd like to send the message to
                $to_number,
                [
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => $twillo_number,
                    // the body of the text message you'd like to send
                    'body' => $message
                ]
            );
            return 1;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }


    public function send_cron($to_number, $message, $user_id)
    {

        require_once('application/libraries/twilio/src/Twilio/autoload.php');
        // Use the REST API Client to make requests to the Twilio REST API
        
        // Your Account SID and Auth Token from twilio.com/console
        if (settings()->global_twilio == 1) {
            $sid = settings()->twillo_account_sid;
            $token = settings()->twillo_auth_token;
            $twillo_number = settings()->twillo_number;
        }else{
            $user = get_by_id($user_id, 'users');
            $sid = $user->twillo_account_sid;
            $token = $user->twillo_auth_token;
            $twillo_number = $user->twillo_number;
        }

        
        $client = new Client($sid, $token);

        try{
            // Use the client to do fun stuff like send text messages!
            $message = $client->messages->create(
                // the number you'd like to send the message to
                $to_number,
                [
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => $twillo_number,
                    // the body of the text message you'd like to send
                    'body' => $message
                ]
            );
            return 1;
        }
        catch(Exception $e){
            return $e->getMessage();
        }
    }


    

    public function send_admin($to_number, $message)
    {

        require_once('application/libraries/twilio/src/Twilio/autoload.php');
        // Use the REST API Client to make requests to the Twilio REST API
        
        // Your Account SID and Auth Token from twilio.com/console
        $sid = settings()->twillo_account_sid;
        $token = settings()->twillo_auth_token;
        $client = new Client($sid, $token);

        try{
            // Use the client to do fun stuff like send text messages!
            $message = $client->messages->create(
                // the number you'd like to send the message to
                $to_number,
                [
                    // A Twilio phone number you purchased at twilio.com/console
                    'from' => settings()->twillo_number,
                    // the body of the text message you'd like to send
                    'body' => $message
                ]
            );
            return 1;
        }
        catch(Exception $e){
            return $e->getMessage();
        }

    }

}