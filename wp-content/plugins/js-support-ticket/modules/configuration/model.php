<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTconfigurationModel {

    function getConfigurations() {
        $query = "SELECT configname,configvalue
					FROM `" . jssupportticket::$_db->prefix . "js_ticket_config`";
        $data = jssupportticket::$_db->get_results($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        foreach ($data AS $config) {
            jssupportticket::$_data[0][$config->configname] = $config->configvalue;
        }
        jssupportticket::$_data[1] = JSSTincluder::getJSModel('email')->getAllEmailsForCombobox();
        return;
    }

    function storeConfiguration($data) {
        $notsave = false;
        foreach ($data AS $key => $value) {
            $query = true;
            if ($key == 'pagination_default_page_size') {
                if ($value < 3) {
                    JSSTmessage::setMessage(__('Pagination default page size not saved', 'js-support-ticket'), 'error');
                    continue;
                }
            }
            if ($key == 'data_directory') {
                $data_directory = $value;
                if(empty($data_directory)){
                    JSSTmessage::setMessage(__('Data directory can not empty.', 'js-support-ticket'), 'error');
                    continue;
                }
                if(strpos($data_directory, '/') !== false){
                    JSSTmessage::setMessage(__('Data directory is not proper.', 'js-support-ticket'), 'error');
                    continue;
                }
                $path = jssupportticket::$_path.'/'.$data_directory;
                if ( ! file_exists($path)) {
                   mkdir($path, 0755);
                }
                if( ! is_writeable($path)){
                    JSSTmessage::setMessage(__('Data directory is not writeable.', 'js-support-ticket'), 'error');
                    continue;
                }
            }
            if ($key == 'system_slug') {
                if(empty($value)){
                    JSSTmessage::setMessage(__('System slug not be empty.', 'js-support-ticket'), 'error');
                    continue;
                }
                $value = str_replace(' ', '-', $value);
                $query = 'SELECT COUNT(ID) FROM `'.jssupportticket::$_db->prefix.'posts` WHERE post_name = "'.$value.'"';
                $countslug = jssupportticket::$_db->get_var($query);
                if($countslug >= 1){
                    JSSTmessage::setMessage(__('System slug is confilicted with post or page slug.', 'js-support-ticket'), 'error');
                    continue;
                }
            }
            jssupportticket::$_db->update(jssupportticket::$_db->prefix . 'js_ticket_config', array('configvalue' => $value), array('configname' => $key));
            if (jssupportticket::$_db->last_error != null) {
                JSSTincluder::getJSModel('systemerror')->addSystemError();
                $notsave = true;
            }
        }
        if ($notsave == false) {
            JSSTmessage::setMessage(__('Configuration has been stored', 'js-support-ticket'), 'updated');
        } else {
            JSSTmessage::setMessage(__('Configuration not has been stored', 'js-support-ticket'), 'error');
        }
        update_option('rewrite_rules', '');
        return;
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


    function getConfigValue($configname){
        $query = "SELECT configvalue FROM `".jssupportticket::$_db->prefix."js_ticket_config` WHERE configname = '".$configname."'";
        $configvalue = jssupportticket::$_db->get_var($query);
        return $configvalue;
    }    
    function getConfigurationByConfigName($configname) {
        $query = "SELECT configvalue 
                  FROM  `".jssupportticket::$_db->prefix."js_ticket_config` WHERE configname ='" . $configname . "'";
        $result = jssupportticket::$_db->get_var($query);
        return $result;
    }
    function getCountConfig() {
        $query = "SELECT COUNT(*) 
                  FROM `".jssupportticket::$_db->prefix."js_ticket_config`";
        $result = jssupportticket::$_db->get_var($query);
        return $result;
    }

    function getPageList() {
        $query = "SELECT ID AS id, post_title AS text FROM `" . jssupportticket::$_db->prefix . "posts` WHERE post_type = 'page' AND post_status = 'publish' ";
        $emails = jssupportticket::$_db->get_results($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        return $emails;
    }
}

?>
