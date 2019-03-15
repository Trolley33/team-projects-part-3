<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

    if(! function_exists('googleRecaptchaHTTPPost')){
        function googleRecaptchaHTTPPost($sharedkey , $grresponse) {
            $url = "https://www.google.com/recaptcha/api/siteverify";
            
            $body_array = array();
            $body_array['secret'] = $sharedkey;
            $body_array['response'] = $grresponse;
            $body_array['remoteip'] = $_SERVER['REMOTE_ADDR'];
            $response = wp_remote_post( $url, array('body' => $body_array,'timeout'=>7,'sslverify'=>false));
            if( !is_wp_error($response) && $response['response']['code'] == 200 && $response['body'] != ''){
                $result = json_decode($response['body'],true);
            }else{
                 if(!is_wp_error($response)){
                    $error = $response['response']['message'];
                }else{
                        $error = $response->get_error_message();
                }
                echo $error;
                $result = '';
            }

            if($result == ''){
                return FALSE;
            }
            //reCaptcha success check
            if($result['success']) {
                return TRUE;
            } else {
                return FALSE;
            }
        }
    }
?>