<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTproinstallerModel {

    function getServerValidate() {
        $array = explode('.', phpversion());
        $phpversion = $array[0] . '.' . $array[1];
        $curlexist = function_exists('curl_version');
        //$curlversion = curl_version()['version'];
        $curlversion = '';
        if (extension_loaded('gd') && function_exists('gd_info')) {
            $gd_lib = 1;
        } else {
            $gd_lib = 0;
        }
        $zip_lib = 0;

        if (file_exists(jssupportticket::$_path . 'includes/lib/pclzip.lib.php')) {
            $zip_lib = 1;
        }
        jssupportticket::$_data['phpversion'] = $phpversion;
        jssupportticket::$_data['curlexist'] = $curlexist;
        jssupportticket::$_data['curlversion'] = $curlversion;
        jssupportticket::$_data['gdlib'] = $gd_lib;
        jssupportticket::$_data['ziplib'] = $zip_lib;
    }

    function getConfiguration() {
        include_once( ABSPATH . 'wp-admin/includes/plugin.php' );
        // check for plugin using plugin name
        if (is_plugin_active('js-support-ticket/js-support-ticket.php')) {
            //plugin is activated
            $query = "SELECT config.* FROM `" . jssupportticket::$_db->prefix . "js_ticket_config` AS config";
            $config = jssupportticket::$_db->get_results($query);
            foreach ($config as $conf) {
                jssupportticket::$_config[$conf->configname] = $conf->configvalue;
            }
            jssupportticket::$_config['config_count'] = COUNT($config);
        }
    }

    function makeDir($path) {
        if (!file_exists($path)) { // create directory
            mkdir($path, 0755);
            $ourFileName = $path . '/index.html';
            $ourFileHandle = fopen($ourFileName, 'w') or die("$path  can't create. Please create directory with 0755 permissions");
            fclose($ourFileHandle);
        }
    }


    function getStepTwoValidate() {
        $basepath = ABSPATH;
        if(!is_writable($basepath)){
            $return['tmpdir'] = 0;
        }else{
            $this->makeDir($basepath.'/tmp');
        }        
        $return['dir'] = substr(sprintf('%o', fileperms(jssupportticket::$_path)), -3);
        if(!is_writable(jssupportticket::$_path)){
            $return['dir'] = 0;
        }        
        $return['tmpdir'] = substr(sprintf('%o', fileperms($basepath.'/tmp')), -3);
        if(!is_writable($basepath.'/tmp')){
            $return['tmpdir'] = 0;
        }        
        $query = 'CREATE TABLE js_test_table(
                    id int,
                    name varchar(255)
                );';
        jssupportticket::$_db->query($query);
        $return['create_table'] = 1;

        if (jssupportticket::$_db->last_error != null) {
            $return['create_table'] = 0;
        }

        $query = 'INSERT INTO js_test_table(id,name) VALUES (1,\'Naeem\'),(2,\'Saad\');';
        jssupportticket::$_db->query($query);
        $return['insert_record'] = 1;
        if (jssupportticket::$_db->last_error != null) {
            $return['insert_record'] = 0;
        }
        $query = 'UPDATE js_test_table SET name = \'Shoaib Rehmat\' WHERE id = 1;';
        jssupportticket::$_db->query($query);
        $return['update_record'] = 1;

        if (jssupportticket::$_db->last_error != null) {
            $return['update_record'] = 0;
        }
        $query = 'DELETE FROM js_test_table;';
        jssupportticket::$_db->query($query);
        $return['delete_record'] = 1;
        if (jssupportticket::$_db->last_error != null) {
            $return['delete_record'] = 0;
        }
        $query = 'DROP TABLE js_test_table;';
        jssupportticket::$_db->query($query);
        $return['drop_table'] = 1;
        if (jssupportticket::$_db->last_error != null) {
            $return['drop_table'] = 0;
        }
        if ($return['dir'] >= 755 && $return['tmpdir'] >= 755) {

            $fileurl = 'http://test.setup.joomsky.com/logo.png';
            $filepath = jssupportticket::$_path . 'logo.png';
            $tmpfile = download_url( $fileurl);
            copy( $tmpfile, $filepath );
            @unlink( $tmpfile ); // must unlink afterwards

            $return['file_downloaded'] = 0;
            if (file_exists(jssupportticket::$_path . 'logo.png')) {
                $return['file_downloaded'] = 1;
            }

            $filepath = $basepath . '/tmp/logo.png';
            $tmpfile = download_url( $fileurl);
            copy( $tmpfile, $filepath );
            @unlink( $tmpfile ); // must unlink afterwards

            $return['file_downloaded'] = 0;
            if (file_exists($basepath . '/tmp/logo.png')) {
                $return['file_downloaded'] = 1;
            }

            $url = 'http://test.setup.joomsky.com/logo.png';
            $response = wp_remote_post( $url, array('timeout'=>7,'sslverify'=>false));
            if( !is_wp_error($response) && $response['response']['code'] == 200 && $response['body'] != ''){
                
            }else{
                if(!is_wp_error($response)){
                   $error = $response['response']['message'];
                }else{
                    $error = $response->get_error_message();
                }
                echo $error; 


                $return['file_downloaded'] = 0;
            }
        } else
            $return['file_downloaded'] = 0;
        jssupportticket::$_data['step2'] = $return;
    }

    function getmyversionlist($data) {
        if(trim($data['transactionkey']) == ''){
            $response = '["0","Please insert product key"]';
            return $response;
        }
        $body_array = array();
        $body_array['transactionkey'] = $data['transactionkey'];
        $_SESSION['transactionkey'] = $data['transactionkey'];
        $body_array['serialnumber'] = $data['serialnumber'];
        $body_array['domain'] = $data['domain'];
        $body_array['producttype'] = $data['producttype'];
        $body_array['productcode'] = $data['productcode'];
        $body_array['productversion'] = $data['productversion'];
        $body_array['JVERSION'] = $data['JVERSION'];
        $body_array['count'] = JSSTincluder::getJSModel('configuration')->getCountConfig();
        $body_array['installerversion'] = $data['installerversion'];
        $url = JCONSTINST;
        
        $response = wp_remote_post( $url, array('body' => $body_array,'timeout'=>7,'sslverify'=>false));

        if( !is_wp_error($response) && $response['response']['code'] == 200 && $response['body'] != ''){
            $response = $response['body'];
        }else{
             if(!is_wp_error($response)){
                $error = $response['response']['message'];
            }else{
                 $error = $response->get_error_message();
            }
            $response = '["0","'.$error.'"]';
        }
        return $response;
    }

}

?>
