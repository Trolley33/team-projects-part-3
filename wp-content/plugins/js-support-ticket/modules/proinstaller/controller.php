<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTproinstallerController {

    function __construct() {
        self::handleRequest();
    }

    function handleRequest() {
        $module = "proinstaller";
        if ($this->canAddLayout()) {
            $layout = JSSTrequest::getVar('jstlay', null, 'step1');
            switch ($layout) {
                case 'step1':
                    JSSTincluder::getJSModel('proinstaller')->getServerValidate();
                    JSSTincluder::getJSModel('proinstaller')->getStepTwoValidate();
                    jssupportticket::$_data['versioncode'] = JSSTincluder::getJSModel('configuration')->getConfigurationByConfigName('versioncode');
                    jssupportticket::$_data['productcode'] = JSSTincluder::getJSModel('configuration')->getConfigurationByConfigName('productcode');
                    jssupportticket::$_data['producttype'] = JSSTincluder::getJSModel('configuration')->getConfigurationByConfigName('producttype');
                    
                    if(isset($_SESSION['response'])){
                        $response = base64_decode($_SESSION['response']);
                        $response = json_decode($response);
                        if($response[0] != true){
                    	   jssupportticket::$_data['response'] = $response[1];
                        }                       
                            unset($_SESSION['response']);
                    }else{
                        jssupportticket::$_data['response'] = '';
                    }
                    break;
                case 'step2': //installation
                    if(isset($_SESSION['response'])){
                        jssupportticket::$_data['response'] = $_SESSION['response'];
                        unset($_SESSION['response']);
                    }else{
                        jssupportticket::$_data['response'] = '';
                    }
                    if(isset($_SESSION['transactionkey'])){
                        jssupportticket::$_data['transactionkey'] = $_SESSION['transactionkey'];
                        unset($_SESSION['transactionkey']);
                    }else{
                        jssupportticket::$_data['transactionkey'] = '';
                    }
                    // jssupportticket::$_data['versioncode'] = JSSTincluder::getJSModel('configuration')->getConfigurationByConfigName('versioncode');
                    // jssupportticket::$_data['productcode'] = JSSTincluder::getJSModel('configuration')->getConfigurationByConfigName('productcode');
                    // jssupportticket::$_data['producttype'] = JSSTincluder::getJSModel('configuration')->getConfigurationByConfigName('producttype');
                    break;
            }
            JSSTincluder::include_file($layout, $module);
        }
    }
    function canAddLayout() {
        if (isset($_POST['form_request']) && $_POST['form_request'] == 'jssupportticket')
            return false;
        elseif (isset($_GET['action']) && $_GET['action'] == 'jstask')
            return false;
        else
            return true;
    }

    function startinstallation() {
        $enable = true;
        $disabled = explode(', ', ini_get('disable_functions'));
        if ($disabled)
            if (in_array('set_time_limit', $disabled))
                $enable = false;

        if (!ini_get('safe_mode')) {
            if ($enable)
                set_time_limit(0);
        }
        $body_array = array();
        $body_array['transactionkey'] = JSSTrequest::getVar('transactionkey');
        $body_array['serialnumber'] = JSSTrequest::getVar('serialnumber');
        $body_array['domain'] = JSSTrequest::getVar('domain');
        $body_array['producttype'] = JSSTrequest::getVar('producttype');
        $body_array['productcode'] = JSSTrequest::getVar('productcode');
        $body_array['productversion'] = JSSTrequest::getVar('productversion');
        $body_array['JVERSION'] = JSSTrequest::getVar('JVERSION');
        $body_array['level'] = JSSTrequest::getVar('level');
        $body_array['installnew'] = JSSTrequest::getVar('installnew');
        $body_array['productversioninstall'] = JSSTrequest::getVar('productversioninstall');
        $body_array['count'] = JSSTrequest::getVar('count_config');
        $url = JCONSTINST;
        $response = wp_remote_post( $url, array('body' => $body_array,'timeout'=>7,'sslverify'=>false));
        if( !is_wp_error($response) && $response['response']['code'] == 200 && $response['body'] != ''){
            $result = $response['body'];
        }else{
            if(!is_wp_error($response)){
                $error = $response['response']['message'];
            }else{
                $error = $response->get_error_message();
            }
            echo $error;
            $result = '';
        }

        if($result !=''){
            eval($result);
        }
    }
    function getversionlist() {
        $data =  JSSTrequest::get('post');
        $response = JSSTincluder::getJSModel('proinstaller')->getmyversionlist($data);
        $response = base64_encode($response);
        $_SESSION['response'] = $response;
        $response = base64_decode($response);
        $response = json_decode($response);
        if($response[0] == true){
            $url = admin_url("admin.php?page=proinstaller&jstlay=step2");
        }else{
            $url = admin_url("admin.php?page=proinstaller&jstlay=step1");
        }
        wp_redirect($url);
        die();
    }
}

$JSSTproinstallerController = new JSSTproinstallerController();
?>
