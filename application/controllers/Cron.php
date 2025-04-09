<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Cron extends Home_Controller {

    public function __construct()
    {
        parent::__construct();
    }

    //expire payments
    public function expire_payments()
    {   
        cron_recurring_payments();
        $this->admin_model->check_expire_recurring_invoices();
        $payments = $this->common_model->get_expire_payments();
        $trial_users = $this->common_model->get_trial_users();

        foreach ($payments as $payment) {
            $data = array(
                'status' => 'expire'
            );
            $data = $this->security->xss_clean($data);
            $this->common_model->update($data, $payment->id, 'payment');
        }

        //check trial expire users
        foreach ($trial_users as $user) {
            $user_data = array(
                'status' => 1,
                'user_type' => 'registered',
                'trial_expire' => '0000-00-00'
            );
            $user_data = $this->security->xss_clean($user_data);
            $this->common_model->update($user_data, $user->id, 'users');
        }

        $this->reminder();
        $this->update_currency();
    }
    

    public function reminder(){

        $payments = $this->common_model->get_reminder_expire_payments();
        foreach ($payments as $payment) {
            $user = get_by_id($payment->user_id, 'users');
            $subject = $this->settings->site_name.' Subscription Expire Reminder';
            $msg = 'Hello '.$user->name.', Your '.$this->settings->site_name.' account will expired on '.my_date_show($payment->expire_on).'. You can continue to use by renew you plan. Till the upgrade, you can still continue all actions.';
            $this->email_model->send_email($user->email, $subject, $msg);
        }
    }


    public function update_currency() {

        $currency = $this->admin_model->get_cur();
        $rate = $this->admin_model->get_rates_date();

        if (date('Y-m-d') != $rate->date) {
            $req_url = 'https://currencyapi.net/api/v1/rates?key=a4c1324d48e2118afa7a9190f2af341dade3&base=USD';
            $response_json = file_get_contents($req_url);
            // Continuing if we got a result
            if(false !== $response_json) {
                // Try or catch for json_decode operation
                try {
                    // Decoding
                    $response = json_decode($response_json);
                    $this->db->truncate('rates');

                    foreach ($currency as $value) {
                        if (!empty($value->currency_code)) {
                            $val = $value->currency_code;
                            if(empty($response->rates->$val)){$response_val = '';}else{$response_val = $response->rates->$val;}
                            $item_data = array(
                                'code' => $value->currency_code,
                                'rate' => $response_val,
                                'date' => my_date_now()
                            );
                            $this->admin_model->insert($item_data, 'rates');
                        }
                    }
                }
                catch(Exception $e) {
                    throw new Exception("Error Processing Request", 1);
                }
            }
        }
    }


}