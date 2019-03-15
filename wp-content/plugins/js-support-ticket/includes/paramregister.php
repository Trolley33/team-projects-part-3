<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

function jsst_generate_rewrite_rules(&$rules, $rule){
    $_new_rules = array();
    foreach($rules AS $key => $value){
        if(strstr($key, $rule)){
            $newkey = substr($key,0,strlen($key) - 3);
            $matcharray = explode('$matches', $value);
            $countmatch = COUNT($matcharray);
            //on all pages
            $changename = false;
            if(file_exists(WP_PLUGIN_DIR.'/js-jobs/js-jobs.php')){
                $changename = true;
            }
            if(file_exists(WP_PLUGIN_DIR.'/js-vehicle-manager/js-vehicle-manager.php')){
                $changename = true;
            }
            $login = ($changename === true) ? 'ticket-login' : 'login';
            $userregister = ($changename === true) ? 'ticket-user-register' : 'userregister';
            $_key = $newkey . '/(my-tickets|add-ticket|'.$login.'|ticket-status|control-panel|print-ticket|ticket|'.$userregister.')(/[^/]*)?(/[^/]*)?(/[^/]*)?/?$';
            $newvalue = $value . '&jsstlayout=$matches['.$countmatch.']&jst1=$matches['.($countmatch + 1).']&jst2=$matches['.($countmatch + 2).']&jst3=$matches['.($countmatch + 3).']';
            $_new_rules[$_key] = $newvalue;
        }
    }
    return $_new_rules;
}

function jsst_post_rewrite_rules_array($rules){
    $newrules = array();
    $newrules = jsst_generate_rewrite_rules($rules, '([^/]+)(?:/([0-9]+))?/?$');
    $newrules += jsst_generate_rewrite_rules($rules, '([^/]+)(/[0-9]+)?/?$');
    $newrules += jsst_generate_rewrite_rules($rules, '([0-9]+)(?:/([0-9]+))?/?$');
    $newrules += jsst_generate_rewrite_rules($rules, '([0-9]+)(/[0-9]+)?/?$');
    return $newrules + $rules;
}
add_filter('post_rewrite_rules', 'jsst_post_rewrite_rules_array');

function jsst_page_rewrite_rules_array($rules){
    $newrules = array();
    $newrules = jsst_generate_rewrite_rules($rules, '(.?.+?)(?:/([0-9]+))?/?$');
    $newrules += jsst_generate_rewrite_rules($rules, '(.?.+?)(/[0-9]+)?/?$');
    return $newrules + $rules;
}
add_filter('page_rewrite_rules', 'jsst_page_rewrite_rules_array');

function jsst_rewrite_rules( $wp_rewrite ) {
      // Hooks params
      $rules = array();
      // Homepage params
      $pageid = get_option('page_on_front');
      $changename = false;
      if(file_exists(WP_PLUGIN_DIR.'/js-jobs/js-jobs.php')){
          $changename = true;
      }
      if(file_exists(WP_PLUGIN_DIR.'/js-vehicle-manager/js-vehicle-manager.php')){
          $changename = true;
      }
      $login = ($changename === true) ? 'st-ticket-login' : 'st-login';
      $userregister = ($changename === true) ? 'ticket-user-register' : 'st-userregister';
      $rules['(st-my-tickets|st-add-ticket|'.$login.'|st-ticket-status|st-control-panel|st-print-ticket|st-ticket|'.$userregister.')(/[^/]*)?(/[^/]*)?(/[^/]*)?/?$'] = 'index.php?page_id='.$pageid.'&jsstlayout=$matches[1]&jst1=$matches[2]&jst2=$matches[3]&jst3=$matches[4]';
      $wp_rewrite->rules = $rules + $wp_rewrite->rules;
      return $wp_rewrite->rules;
}
add_filter( 'generate_rewrite_rules', 'jsst_rewrite_rules' );

function jsst_query_var( $qvars ) {
    $qvars[] = 'jsstlayout';
    $qvars[] = 'jst1';
    $qvars[] = 'jst2';
    $qvars[] = 'jst3';
    return $qvars;
}
add_filter( 'query_vars', 'jsst_query_var' , 10, 1 );

function jsst_parse_request($q) {
    // echo '<pre style="color:#000;">';print_r($q->query_vars);echo '</pre>';
    if(isset($q->query_vars['jsstlayout']) && !empty($q->query_vars['jsstlayout'])){
        $layout = $q->query_vars['jsstlayout'];
        if(substr($layout, 0, 3) == 'st-'){
            $layout = substr($layout,3);
        }
        switch ($layout) {
            case 'ticket':
                jssupportticket::$_data['sanitized_args']['jstmod'] = 'ticket';
                jssupportticket::$_data['sanitized_args']['jstlay'] = 'ticketdetail';
                jssupportticket::$_data['sanitized_args']['jssupportticketid'] = str_replace('/', '',$q->query_vars['jst1']);
            break;
            case 'print-ticket':
                jssupportticket::$_data['sanitized_args']['jstmod'] = 'ticket';
                jssupportticket::$_data['sanitized_args']['jstlay'] = 'printticket';
                if(!empty($q->query_vars['jst1'])){
                    jssupportticket::$_data['sanitized_args']['jssupportticketid'] = str_replace('/', '',$q->query_vars['jst1']);
                }
            break;
            case 'ticket-login':
            case 'login':
                jssupportticket::$_data['sanitized_args']['jstmod'] = 'jssupportticket';
                jssupportticket::$_data['sanitized_args']['jstlay'] = 'login';
            break;
            case 'control-panel':
                jssupportticket::$_data['sanitized_args']['jstmod'] = 'jssupportticket';
                jssupportticket::$_data['sanitized_args']['jstlay'] = 'controlpanel';
                if(!empty($q->query_vars['jst1'])){
                    jssupportticket::$_data['sanitized_args']['jssupportticketid'] = str_replace('/', '',$q->query_vars['jst1']);
                }
            break;
            case 'ticket-status':
                jssupportticket::$_data['sanitized_args']['jstmod'] = 'ticket';
                jssupportticket::$_data['sanitized_args']['jstlay'] = 'ticketstatus';
            break;
            case 'add-ticket':
                jssupportticket::$_data['sanitized_args']['jstmod'] = 'ticket';
                jssupportticket::$_data['sanitized_args']['jstlay'] = 'addticket';
                if(!empty($q->query_vars['jst1'])){
                    jssupportticket::$_data['sanitized_args']['jssupportticketid'] = str_replace('/', '',$q->query_vars['jst1']);
                }
            break;
            case 'my-tickets':
                jssupportticket::$_data['sanitized_args']['jstmod'] = 'ticket';
                jssupportticket::$_data['sanitized_args']['jstlay'] = 'myticket';
            break;
            case 'ticket-user-register':
            case 'userregister':
                jssupportticket::$_data['sanitized_args']['jstmod'] = 'jssupportticket';
                jssupportticket::$_data['sanitized_args']['jstlay'] = 'userregister';
            break;
            default:
                jssupportticket::$_data['sanitized_args']['jstmod'] = 'jssupportticket';
                jssupportticket::$_data['sanitized_args']['jstlay'] = 'controlpanel';
            break;
        }
    }
}
add_action('parse_request', 'jsst_parse_request');

function jsst_redirect_canonical($redirect_url, $requested_url) {
    global $wp_rewrite;
    if(is_home() || is_front_page()){
        $array = array('/st-ticket','/st-my-tickets','/st-add-ticket','/st-login','/st-ticket-login','/st-ticket-status','/st-control-panel', '/st-print-ticket');
        $ret = false;
        foreach($array AS $layout){
            if(strstr($requested_url, $layout)){
                $ret = true;
                break;
            }
        }
        if($ret == true){
            return $requested_url;
        }
    }
      return $redirect_url;
}
add_filter('redirect_canonical', 'jsst_redirect_canonical', 11, 2);

?>
