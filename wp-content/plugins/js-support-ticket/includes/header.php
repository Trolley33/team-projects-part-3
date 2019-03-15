<?php

if (!defined('ABSPATH'))
    die('Restricted Access');
if (jssupportticket::$_config['show_header'] != 1)
    return false;

$div = '';
$headertitle='';
$editid = JSSTrequest::getVar('jssupportticketid');
$isnew = ($editid == null) ? true : false;
$array[] = array('link' => jssupportticket::makeUrl(array('jstmod'=>'jssupportticket', 'jstlay'=>'controlpanel')), 'text' => __('Control Panel', 'js-support-ticket'));
$module = JSSTrequest::getVar('jstmod', null, 'jssupportticket');
$layout = JSSTrequest::getVar('jstlay', null);
if (isset(jssupportticket::$_data['short_code_header'])) {
    switch (jssupportticket::$_data['short_code_header']){
        case 'myticket':
            $module = 'ticket';
            $layout = 'myticket';
            break;
        case 'addticket':
            $module = 'ticket';
            $layout = 'addticket';
            break;
    }
}
if ($module != null) {
    switch ($module) {
        case 'ticket':
            // Add default module link
            switch ($layout) {
                case 'addticket':
                    $layout1 = 'myticket';
                    $array[] = array('link' => jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>$layout1)), 'text'=>__('My Tickets','js-support-ticket'));
                    $text =  __('Add Ticket', 'js-support-ticket');
                    $array[] = array('link' => jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'addticket')), 'text' => $text);
                    break;
                case 'myticket':
                    $array[] = array('link' => jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'myticket')), 'text' => __('My Tickets', 'js-support-ticket'));
                    break;
                case 'ticketdetail':
                    $layout1 = 'myticket';
                    $array[] = array('link' => jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>$layout1)), 'text' => __('My Tickets', 'js-support-ticket'));
                    $array[] = array('link' => '#', 'text' => __('Ticket Detail', 'js-support-ticket'));
                    break;
                case 'ticketstatus':
                    $array[] = array('link' => jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>'ticketstatus')), 'text' => __('Ticket Status', 'js-support-ticket'));
                    break;
            }
            break;
        case 'jssupportticket':
            switch ($layout) {
                case 'login':
                    $array[] = array('link' => jssupportticket::makeUrl(array('jstmod'=>$module, 'jstlay'=>$layout)), 'text' => __('Login', 'js-support-ticket'));
                    break;
            }
            break;

            break;
    }
}

//Layout variy for and User
$linkname = 'user';
$myticket = 'myticket';
$addticket = 'addticket';
$login = 'login';

$flage = true;
if (jssupportticket::$_config['tplink_home_' . $linkname] == 1) {
    $linkarray[] = array(
        'class'     => 'js-ticket-homeclass',
        'link'      => jssupportticket::makeUrl(array('jstmod'=>'jssupportticket', 'jstlay'=>'controlpanel')),
        'title'     => __('Dashboard', 'js-support-ticket'),
        'jstmod'    => '',
        'imgsrc'    => jssupportticket::$_pluginpath . 'includes/images/dashboard-icon/header-icon/dashboard.png',
        'imgtitle'  => 'Dashboard-icon',
    );
    $flage = false;
}

if (jssupportticket::$_config['tplink_openticket_' . $linkname] == 1) {
    $linkarray[] = array(
        'class' => 'js-ticket-openticketclass',
        'link' => jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>$addticket)),
        'title' => __('New Ticket', 'js-support-ticket'),
        'jstmod' => 'ticket',
        'imgsrc'    => jssupportticket::$_pluginpath . 'includes/images/dashboard-icon/header-icon/add-ticket.png',
        'imgtitle'  => 'New Ticket',
    );
    $flage = false;
}

if (jssupportticket::$_config['tplink_tickets_' . $linkname] == 1) {
    $linkarray[] = array(
        'class' => 'js-ticket-myticket',
        'link' => jssupportticket::makeUrl(array('jstmod'=>'ticket', 'jstlay'=>$myticket)),
        'title' => __('My Tickets', 'js-support-ticket'),
        'jstmod' => 'ticket',
        'imgsrc'    => jssupportticket::$_pluginpath . 'includes/images/dashboard-icon/header-icon/my-tickets.png',
        'imgtitle'  => 'My Tickets',
    );
    $flage = false;
}

if (jssupportticket::$_config['tplink_login_logout_user'] == 1){
    $loginval = JSSTincluder::getJSModel('configuration')->getConfigValue('set_login_link');
    $loginlink = JSSTincluder::getJSModel('configuration')->getConfigValue('login_link');
    if ($loginval == 2 && $loginlink != ""){
        $hreflink = $loginlink;
    }else{
        $hreflink= jssupportticket::makeUrl(array('jstmod'=>'jssupportticket', 'jstlay'=>'login'));
    }
    if (!is_user_logged_in()){
        $imgsrc = jssupportticket::$_pluginpath . 'includes/images/dashboard-icon/header-icon/login.png'; 
        $title = __('Login', 'js-support-ticket');
    }else{    
        $imgsrc = jssupportticket::$_pluginpath . 'includes/images/dashboard-icon/header-icon/logout.png';
        $title = __('Log out', 'js-support-ticket');
        $hreflink= wp_logout_url(jssupportticket::makeUrl(array('jstmod'=>'jssupportticket', 'jstlay'=>'controlpanel')) );
    }
    $linkarray[] = array(
        'class'     => 'js-ticket-loginlogoutclass',
        'link'      => $hreflink,
        'title'     => $title,
        'jstmod'    => 'ticket',
        'imgsrc'    => $imgsrc,
        'imgtitle'  => 'Login',
    );
    $flage = false;
}


if (isset($array)) {
    foreach ($array AS $obj);
}
$extramargin = '';
$displayhidden = '';
if ($flage)
    $displayhidden = 'display:none;';
$div .= '
		<div id="jsst-header-main-wrapper" style="' . $displayhidden . '">';
$div .='<div id="jsst-header" class="' . $extramargin . '" >';
$div .='<div id="jsst-header-heading" class="" ><a class="js-ticket-header-links" href="' . esc_url($obj['link']) . '">' . $obj['text'] . '</a></div>';
$div .='<div id="jsst-tabs-wrp" class="" >';
if (isset($linkarray))
    foreach ($linkarray AS $link) {
        $div .= '<span class="jsst-header-tab ' . $link['class'] . '"><a class="js-cp-menu-link" href="' . esc_url($link['link']) . '"><img class="cp-menu-link-img" title="'. $link['imgtitle']. '" src="'.$link['imgsrc'].'">' . $link['title'] . '</a></span>';
    }
    
$div .= '</div></div></div>';
echo $div;
?>
