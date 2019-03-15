<?php

if (!defined('ABSPATH'))
    die('Restricted Access');

class JSSTjssupportticketModel {

    function getControlPanelData() {
        $curdate = date_i18n('Y-m-d');
        $fromdate = date_i18n('Y-m-d', strtotime("now -1 month"));

        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` WHERE priorityid = priority.id AND status = 0 AND (lastreply = '0000-00-00 00:00:00' OR lastreply = '') AND date(created) >= '" . $fromdate . "' AND date(created) <= '" . $curdate . "' ) AS totalticket
                    FROM `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ORDER BY priority.priority";
        $openticket_pr = jssupportticket::$_db->get_results($query);
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` WHERE priorityid = priority.id AND isanswered = 1 AND status != 4 AND status != 0 AND date(created) >= '" . $fromdate . "' AND date(created) <= '" . $curdate . "') AS totalticket
                    FROM `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ORDER BY priority.priority";
        $answeredticket_pr = jssupportticket::$_db->get_results($query);
        $query = "SELECT priority.priority,(SELECT COUNT(id) FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` WHERE priorityid = priority.id AND isanswered != 1 AND status != 4 AND (lastreply != '0000-00-00 00:00:00' AND lastreply != '') AND date(created) >= '" . $fromdate . "' AND date(created) <= '" . $curdate . "') AS totalticket
                    FROM `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ORDER BY priority.priority";
        $pendingticket_pr = jssupportticket::$_db->get_results($query);
        jssupportticket::$_data['stack_chart_horizontal']['title'] = "['" . __('Tickets', 'js-support-ticket') . "',";
        jssupportticket::$_data['stack_chart_horizontal']['data'] = "['" . __('Pending', 'js-support-ticket') . "',";

        foreach ($pendingticket_pr AS $pr) {
            jssupportticket::$_data['stack_chart_horizontal']['title'] .= "'" . __($pr->priority,'js-support-ticket') . "',";
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= $pr->totalticket . ",";
        }
        jssupportticket::$_data['stack_chart_horizontal']['title'] .= "]";
        jssupportticket::$_data['stack_chart_horizontal']['data'] .= "],['" . __('Answered', 'js-support-ticket') . "',";

        foreach ($answeredticket_pr AS $pr) {
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= $pr->totalticket . ",";
        }

        jssupportticket::$_data['stack_chart_horizontal']['data'] .= "],['" . __('New', 'js-support-ticket') . "',";

        foreach ($openticket_pr AS $pr) {
            jssupportticket::$_data['stack_chart_horizontal']['data'] .= $pr->totalticket . ",";
        }

        jssupportticket::$_data['stack_chart_horizontal']['data'] .= "]";

        //To show priority colors on chart
        $query = "SELECT prioritycolour FROM `".jssupportticket::$_db->prefix."js_ticket_priorities` ORDER BY priority ";
        $jsonColorList = "[";
        foreach(jssupportticket::$_db->get_results($query) as $priority){
            $jsonColorList.= "'".$priority->prioritycolour."',";
        }
        $jsonColorList .= "]";
        jssupportticket::$_data['stack_chart_horizontal']['colors'] = $jsonColorList;
        //end priority colors

        jssupportticket::$_data['ticket_total']['openticket'] = 0;
        jssupportticket::$_data['ticket_total']['overdueticket'] = 0;
        jssupportticket::$_data['ticket_total']['pendingticket'] = 0;
        jssupportticket::$_data['ticket_total']['answeredticket'] = 0;

        $count = count($openticket_pr);
        for ($i = 0; $i < $count; $i++) {
            jssupportticket::$_data['ticket_total']['openticket'] += $openticket_pr[$i]->totalticket;
            jssupportticket::$_data['ticket_total']['pendingticket'] += $pendingticket_pr[$i]->totalticket;
            jssupportticket::$_data['ticket_total']['answeredticket'] += $answeredticket_pr[$i]->totalticket;
        }

        $query = "SELECT ticket.id,ticket.ticketid,ticket.subject,ticket.name,ticket.created,priority.priority,priority.prioritycolour,ticket.status
		 			FROM `" . jssupportticket::$_db->prefix . "js_ticket_tickets` AS ticket
		 			JOIN `" . jssupportticket::$_db->prefix . "js_ticket_priorities` AS priority ON priority.id = ticket.priorityid
		 			ORDER BY ticket.status ASC, ticket.created DESC LIMIT 0, 5";
        jssupportticket::$_data['tickets'] = jssupportticket::$_db->get_results($query);
        jssupportticket::$_data['version'] = jssupportticket::$_config['versioncode'];
        return;
    }

    function makeDir($path) {
        if (!file_exists($path)) { // create directory
            mkdir($path, 0755);
            $ourFileName = $path . '/index.html';
            $ourFileHandle = fopen($ourFileName, 'w') or die(__('Cannot open file', 'js-support-ticket'));
            fclose($ourFileHandle);
        }
    }

    function checkExtension($filename) {
        $i = strrpos($filename, ".");
        if (!$i)
            return 'N';
        $l = strlen($filename) - $i;
        $ext = substr($filename, $i + 1, $l);
        $extensions = explode(",", jssupportticket::$_config['file_extension']);
        $match = 'N';
        foreach ($extensions as $extension) {
            if (strtolower($extension) == strtolower($ext)) {
                $match = 'Y';
                break;
            }
        }
        return $match;
    }

    function getUserListForRegistration() {
        $query = "SELECT DISTINCT user.ID AS userid, user.user_login AS username, user.user_email AS useremail, user.display_name AS userdisplayname
                    FROM `" . jssupportticket::$_wpprefixforuser . "users` AS user ";
        $users = jssupportticket::$_db->get_results($query);
        return $users;
    }

    function getusersearchajax() {
        $username = JSSTrequest::getVar('username');
        $name = JSSTrequest::getVar('name');
        $emailaddress = JSSTrequest::getVar('emailaddress');
        $canloadresult = false;

        $userlimit = JSSTrequest::getVar('userlimit',null,0);
        $maxrecorded = 4;
        $query = "SELECT DISTINCT COUNT(user.ID) 
                    FROM `" . jssupportticket::$_wpprefixforuser . "users` AS user
                    WHERE NOT EXISTS(SELECT umeta_id FROM `".jssupportticket::$_wpprefixforuser."usermeta` WHERE meta_value LIKE '%administrator%' AND user_id = user.id) ";
        if (strlen($name) > 0) {
            $query .= " AND user.display_name LIKE '%$name%'";
            $canloadresult = true;
        }
        if (strlen($emailaddress) > 0) {
            $query .= " AND user.user_email LIKE '%$emailaddress%'";
            $canloadresult = true;
        }
        if (strlen($username) > 0) {
            $query .= " AND user.user_login LIKE '%$username%'";
            $canloadresult = true;
        }
        $total = jssupportticket::$_db->get_var($query);
        $limit = $userlimit * $maxrecorded;
        if($limit >= $total){
            $limit = 0;
        }
        $query = "SELECT DISTINCT user.ID AS userid, user.user_login AS username, user.user_email AS useremail, user.display_name AS userdisplayname
                    FROM `" . jssupportticket::$_wpprefixforuser . "users` AS user
                    WHERE NOT EXISTS(SELECT umeta_id FROM `".jssupportticket::$_wpprefixforuser."usermeta` WHERE meta_value LIKE '%administrator%' AND user_id = user.id) ";
        if (strlen($name) > 0) {
            $query .= " AND user.display_name LIKE '%$name%'";
            $canloadresult = true;
        }
        if (strlen($emailaddress) > 0) {
            $query .= " AND user.user_email LIKE '%$emailaddress%'";
            $canloadresult = true;
        }
        if (strlen($username) > 0) {
            $query .= " AND user.user_login LIKE '%$username%'";
            $canloadresult = true;
        }
        $query .= " LIMIT $limit, $maxrecorded";
        $users = jssupportticket::$_db->get_results($query);
        $html = $this->makeUserList($users,$total,$maxrecorded,$userlimit);
        return $html;
    }

    function getuserlistajax(){
        $userlimit = JSSTrequest::getVar('userlimit',null,0);
        $maxrecorded = 4;
        $query = "SELECT DISTINCT COUNT(user.id)
                    FROM `" . jssupportticket::$_wpprefixforuser . "users` AS user
                    WHERE NOT EXISTS(SELECT umeta_id FROM `".jssupportticket::$_wpprefixforuser."usermeta` WHERE meta_value LIKE '%administrator%' AND user_id = user.id)";
        $total = jssupportticket::$_db->get_var($query);
        $limit = $userlimit * $maxrecorded;
        if($limit >= $total){
            $limit = 0;
        }
        $query = "SELECT DISTINCT user.ID AS userid, user.user_login AS username, user.user_email AS useremail, user.display_name AS userdisplayname, user.user_nicename AS usernicename
                    FROM `" . jssupportticket::$_wpprefixforuser . "users` AS user
                    WHERE NOT EXISTS(SELECT umeta_id FROM `".jssupportticket::$_wpprefixforuser."usermeta` WHERE meta_value LIKE '%administrator%' AND user_id = user.id)
                    LIMIT $limit, $maxrecorded";
        $users = jssupportticket::$_db->get_results($query);
        $html = $this->makeUserList($users,$total,$maxrecorded,$userlimit);
        return $html;
    }

    function makeUserList($users,$total,$maxrecorded,$userlimit){
        $html = '';
        if(!empty($users)){
            if(is_array($users)){
                $html ='
                    <div class="js-col-md-2 js-title">'.__('User id','js-support-ticket').'</div>
                    <div class="js-col-md-3 js-title">'.__('Username','js-support-ticket').'</div>
                    <div class="js-col-md-4 js-title">'.__('Email address','js-support-ticket').'</div>
                    <div class="js-col-md-3 js-title">'.__('Name','js-support-ticket').'</div>';
                foreach($users AS $user){
                    $html .='
                        <div class="user-records-wrapper js-value" style="display:inline-block;width:100%;">
                            <div class="js-col-xs-12 js-col-md-2">
                                <span class="js-user-title-xs">'.__('User id','js-support-ticket').' : </span>'.$user->userid.'
                            </div>                            
                            <div class="js-col-xs-12 js-col-md-3">
                                <span class="js-user-title-xs">'.__('Username','js-support-ticket').' : </span>';
                                $username = empty($user->userdisplayname) ? $user->usernicename : $user->userdisplayname;
                                    $html .='<a href="#" class="js-userpopup-link" data-id="'.$user->userid.'" data-email="'.$user->useremail.'" data-name="'.$username.'">'.$user->username.'</a> </div>';
                            $html .=
                            '<div class="js-col-xs-12 js-col-md-4">
                                <span class="js-user-title-xs">'.__('Email address','js-support-ticket').' : </span>'.$user->useremail.'
                            </div>
                            <div class="js-col-xs-12 js-col-md-3">
                                <span class="js-user-title-xs">'.__('Display name','js-support-ticket').' : </span>'.$username.'
                            </div>
                        </div>';
                }
                $num_of_pages = ceil($total / $maxrecorded);
                $num_of_pages = ($num_of_pages > 0) ? ceil($num_of_pages) : floor($num_of_pages);
                if($num_of_pages > 0){
                    $page_html = '';
                    $prev = $userlimit;
                    if($prev > 0){
                        $page_html .= '<a class="jsst_userlink" href="#" onclick="updateuserlist('.($prev - 1).');">'.__('Previous','js-support-ticket').'</a>';
                    }
                    for($i = 0; $i < $num_of_pages; $i++){
                        if($i == $userlimit)
                            $page_html .= '<span class="jsst_userlink selected" >'.($i + 1).'</span>';
                        else
                            $page_html .= '<a class="jsst_userlink" href="#" onclick="updateuserlist('.$i.');">'.($i + 1).'</a>';

                    }
                    $next = $userlimit + 1;
                    if($next < $num_of_pages){
                        $page_html .= '<a class="jsst_userlink" href="#" onclick="updateuserlist('.$next.');">'.__('Next','js-support-ticket').'</a>';
                    }
                    if($page_html != ''){
                        $html .= '<div class="jsst_userpages">'.$page_html.'</div>';
                    }
                }
            }
        }else{
            $html = JSSTlayout::getNoRecordFound();
        }
        return $html;        
    }

    function gettingStartData(){
        $query = "SELECT COUNT(ID) FROM `".jssupportticket::$_db->prefix."posts` WHERE post_type = 'page' AND post_content LIKE '%[jssupportticket]%';";
        $pageexist = jssupportticket::$_db->get_var($query);
        if($pageexist > 0){ // page exist
            $query = "SELECT post_status FROM `".jssupportticket::$_db->prefix."posts` WHERE post_type = 'page' AND post_content LIKE '%[jssupportticket]%';";
            $poststatus = jssupportticket::$_db->get_var($query);
        }else{ // page not exist
            $poststatus = 0;
        }
        jssupportticket::$_data['pageexist'] = $pageexist;
        jssupportticket::$_data['poststatus'] = $poststatus;
        return;
    }

    function getPageList() {
        $query = "SELECT ID AS id, post_title AS text FROM `" . jssupportticket::$_db->prefix . "posts` WHERE post_type = 'page' AND post_status = 'publish' AND post_content NOT LIKE '%[jssupportticket]%'; ";
        $emails = jssupportticket::$_db->get_results($query);
        if (jssupportticket::$_db->last_error != null) {
            JSSTincluder::getJSModel('systemerror')->addSystemError();
        }
        return $emails;
    }

    function addPageSlug($data){
        if(!is_numeric($data['ID'])) return false;
        $query = "UPDATE `".jssupportticket::$_db->prefix."posts` SET post_content = CONCAT('[jssupportticket]<br/>',post_content) WHERE ID = ".$data['ID'];
        jssupportticket::$_db->query($query);
        if (jssupportticket::$_db->last_error == null) {
            JSSTmessage::setMessage(__('Shortcode has been sucessfully added', 'js-support-ticket'), 'updated');
        } else {
            JSSTincluder::getJSModel('systemerror')->addSystemError(); // if there is an error add it to system errorrs
            JSSTmessage::setMessage(__('Shortcode has not been added', 'js-support-ticket'), 'error');
        }
    }
    //translation code
        function getListTranslations() {
        
        $result = array();
        $result['error'] = false;

        $path = jssupportticket::$_path.'languages';

        if( ! is_writeable($path)){
            $result['error'] = __('Dir is not writeable','js-support-ticket').' '.$path;

        }else{

            if($this->isConnected()){

                $url = "https://www.joomsky.com/translations/api/1.0/index.php";
                $body_array = array();
                $body_array['product'] ='js-support-ticket-wp';
                $body_array['domain'] = get_site_url();
                $body_array['producttype'] = jssupportticket::$_config['versiontype'];
                $body_array['productcode'] = 'jsticket';
                $body_array['productversion'] = jssupportticket::$_config['versioncode'];
                $body_array['JVERSION'] = get_bloginfo('version');
                $body_array['method'] = 'getTranslations';


                $response = wp_remote_post( $url, array('body' => $body_array,'timeout'=>7,'sslverify'=>false));
                if( !is_wp_error($response) && $response['response']['code'] == 200 && $response['body'] != ''){
                    $response = $response['body'];
                }else{
                    if(!is_wp_error($response)){
                        $error = $response['response']['message'];
                    }else{
                        $error = $response->get_error_message();
                    }
                    $result['error'] = $error;
                    $response = '';
                }

                $result['data'] = $response;
            }else{
                $result['error'] = __('Unable to connect to server','js-support-ticket');
            }
        }

        $result = json_encode($result);

        return $result;
    }

    function makeLanguageCode($lang_name){
        $langarray = wp_get_installed_translations('core');
        $langarray = $langarray['default'];
        $match = false;
        if(array_key_exists($lang_name, $langarray)){
            $lang_name = $lang_name;
            $match = true;
        }else{
            $m_lang = '';
            foreach($langarray AS $k => $v){
                if($lang_name{0}.$lang_name{1} == $k{0}.$k{1}){
                    $m_lang .= $k.', ';
                }
            }

            if($m_lang != ''){
                $m_lang = substr($m_lang, 0,strlen($m_lang) - 2);
                $lang_name = $m_lang;
                $match = 2;
            }else{
                $lang_name = $lang_name;
                $match = false;
            }
        }

        return array('match' => $match , 'lang_name' => $lang_name);
    }

    function validateAndShowDownloadFileName( ){
        $lang_name = JSSTrequest::getVar('langname');
        if($lang_name == '') return '';
        $result = array();
        $f_result = $this->makeLanguageCode($lang_name);
        $path = jssupportticket::$_path.'languages';
        $result['error'] = false;
        if($f_result['match'] === false){
            $result['error'] = $lang_name. ' ' . __('Language is not installed','js-support-ticket');
        }elseif( ! is_writeable($path)){
            $result['error'] = $lang_name. ' ' . __('Language directory is not writeable','js-support-ticket').': '.$path;
        }else{
            $result['input'] = '<input id="languagecode" class="text_area" type="text" value="'.$lang_name.'" name="languagecode">';
            if($f_result['match'] === 2){
                $result['input'] .= '<div id="js-emessage-wrapper-other" style="display:block;margin:20px 0px 20px;">';
                $result['input'] .= __('Required language is not installed but similar language like','js-support-ticket').': "<b>'.$f_result['lang_name'].'</b>" '.__('is found in your system','js-support-ticket');
                $result['input'] .= '</div>';

            }
            $result['path'] = __('Language code','js-support-ticket');
        }
        $result = json_encode($result);
        return $result;
    }

    function getLanguageTranslation(){

        $lang_name = JSSTrequest::getVar('langname');
        $language_code = JSSTrequest::getVar('filename');

        $result = array();
        $result['error'] = false;
        $path = jssupportticket::$_path.'languages';

        if($lang_name == '' || $language_code == ''){
            $result['error'] = __('Empty values','js-support-ticket');
            return json_encode($result);
        }
        
        $final_path = $path.'/js-support-ticket-'.$language_code.'.po';


        $langarray = wp_get_installed_translations('core');
        $langarray = $langarray['default'];

        if(!array_key_exists($language_code, $langarray)){
            $result['error'] = $lang_name. ' ' . __('Language is not installed','js-support-ticket');
            return json_encode($result);
        }elseif( ! is_writeable($path)){
            $result['error'] = $lang_name. ' ' . __('Language directory is not writeable','js-support-ticket').': '.$path;
            return json_encode($result);
        }

        if( ! file_exists($final_path)){
            touch($final_path);
        }

        if( ! is_writeable($final_path)){
            $result['error'] = __('File is not writeable','js-support-ticket').': '.$final_path;
        }else{

            if($this->isConnected()){

                $url = "https://www.joomsky.com/translations/api/1.0/index.php";
                $body_array = array();
                $body_array['product'] ='js-support-ticket-wp';
                $body_array['domain'] = get_site_url();
                $body_array['producttype'] = jssupportticket::$_config['versiontype'];
                $body_array['productcode'] = 'jsticket';
                $body_array['productversion'] = jssupportticket::$_config['versioncode'];
                $body_array['JVERSION'] = get_bloginfo('version');
                $body_array['translationcode'] = $lang_name;
                $body_array['method'] = 'getTranslationFile';

                $response = wp_remote_post( $url, array('body' => $body_array,'timeout'=>7,'sslverify'=>false));
                if( !is_wp_error($response) && $response['response']['code'] == 200 && $response['body'] != ''){
                    $response = json_decode($response['body'],true);
                }else{
                    if(!is_wp_error($response)){
                        $error = $response['response']['message'];
                    }else{
                        $error = $response->get_error_message();
                    }
                    $response = '';
                }

                if($response != ''){
                    $array = $response;
                }else{
                    $result['error'] = $error;
                }

                $ret = $this->writeLanguageFile( $final_path , $array['file']);

                if($ret != false){
                    $url = "https://www.joomsky.com/translations/api/1.0/index.php";
                    $body_array = array();
                    $body_array['product'] ='js-support-ticket-wp';
                    $body_array['domain'] = get_site_url();
                    $body_array['producttype'] = jssupportticket::$_config['versiontype'];
                    $body_array['productcode'] = 'jsticket';
                    $body_array['productversion'] = jssupportticket::$_config['versioncode'];
                    $body_array['JVERSION'] = get_bloginfo('version');
                    $body_array['folder'] = $array['foldername'];
                    
                    $response = wp_remote_post( $url, array('body' => $body_array,'timeout'=>7,'sslverify'=>false));
                    if( !is_wp_error($response) && $response['response']['code'] == 200 && $response['body'] != ''){
                        $response = $response['body'];
                    }else{
                        if(!is_wp_error($response)){
                            $error = $response['response']['message'];
                        }else{
                            $error = $response->get_error_message();
                        }
                        $response = '';
                        $result['error'] = $error;
                    }

                }
                $result['data'] = __('File Downloaded Successfully','js-support-ticket');
            }else{
                $result['error'] = __('Unable to connect to server','js-support-ticket');
            }
        }

        $result = json_encode($result);

        return $result;

    }

    function writeLanguageFile( $path , $url ){
		include(ABSPATH . "wp-admin/includes/admin.php");
		$tmpfile = download_url( $url);
		copy( $tmpfile, $path );
		@unlink( $tmpfile ); // must unlink afterwards

        //make mo for po file
        $this->phpmo_convert($path);
        return $result;
    }

    function isConnected(){
        
        $connected = @fsockopen("www.google.com", 80); 
        if ($connected){
            $is_conn = true; //action when connected
            fclose($connected);
        }else{
            $is_conn = false; //action in connection failure
        }
        return $is_conn;
    }

    function phpmo_convert($input, $output = false) {
        if ( !$output )
            $output = str_replace( '.po', '.mo', $input );
        $hash = $this->phpmo_parse_po_file( $input );
        if ( $hash === false ) {
            return false;
        } else {
            $this->phpmo_write_mo_file( $hash, $output );
            return true;
        }
    }

    function phpmo_clean_helper($x) {
        if (is_array($x)) {
            foreach ($x as $k => $v) {
                $x[$k] = $this->phpmo_clean_helper($v);
            }
        } else {
            if ($x[0] == '"')
                $x = substr($x, 1, -1);
            $x = str_replace("\"\n\"", '', $x);
            $x = str_replace('$', '\\$', $x);
        }
        return $x;
    }
    /* Parse gettext .po files. */
    /* @link http://www.gnu.org/software/gettext/manual/gettext.html#PO-Files */
    function phpmo_parse_po_file($in) {
    if (!file_exists($in)){ return false; }
    $ids = array();
    $strings = array();
    $language = array();
    $lines = file($in);
    foreach ($lines as $line_num => $line) {
        if (strstr($line, 'msgid')){
            $endpos = strrchr($line, '"');
            $id = substr($line, 7, $endpos-2);
            $ids[] = $id;
        }elseif(strstr($line, 'msgstr')){
            $endpos = strrchr($line, '"');
            $string = substr($line, 8, $endpos-2);
            $strings[] = array($string);
        }else{}
    }
    for ($i=0; $i<count($ids); $i++){
        //Shoaib
        if(isset($ids[$i]) && isset($strings[$i])){
            if($entry['msgstr'][0] == '""'){
                continue;
            }
            $language[$ids[$i]] = array('msgid' => $ids[$i], 'msgstr' =>$strings[$i]);
        }        
    }
    return $language;
    }
    /* Write a GNU gettext style machine object. */
    /* @link http://www.gnu.org/software/gettext/manual/gettext.html#MO-Files */
    function phpmo_write_mo_file($hash, $out) {
        // sort by msgid
        ksort($hash, SORT_STRING);
        // our mo file data
        $mo = '';
        // header data
        $offsets = array ();
        $ids = '';
        $strings = '';
        foreach ($hash as $entry) {
            $id = $entry['msgid'];
            $str = implode("\x00", $entry['msgstr']);
            // keep track of offsets
            $offsets[] = array (
                            strlen($ids), strlen($id), strlen($strings), strlen($str)
                            );
            // plural msgids are not stored (?)
            $ids .= $id . "\x00";
            $strings .= $str . "\x00";
        }
        // keys start after the header (7 words) + index tables ($#hash * 4 words)
        $key_start = 7 * 4 + sizeof($hash) * 4 * 4;
        // values start right after the keys
        $value_start = $key_start +strlen($ids);
        // first all key offsets, then all value offsets
        $key_offsets = array ();
        $value_offsets = array ();
        // calculate
        foreach ($offsets as $v) {
            list ($o1, $l1, $o2, $l2) = $v;
            $key_offsets[] = $l1;
            $key_offsets[] = $o1 + $key_start;
            $value_offsets[] = $l2;
            $value_offsets[] = $o2 + $value_start;
        }
        $offsets = array_merge($key_offsets, $value_offsets);
        // write header
        $mo .= pack('Iiiiiii', 0x950412de, // magic number
        0, // version
        sizeof($hash), // number of entries in the catalog
        7 * 4, // key index offset
        7 * 4 + sizeof($hash) * 8, // value index offset,
        0, // hashtable size (unused, thus 0)
        $key_start // hashtable offset
        );
        // offsets
        foreach ($offsets as $offset)
            $mo .= pack('i', $offset);
        // ids
        $mo .= $ids;
        // strings
        $mo .= $strings;
        file_put_contents($out, $mo);
    }


}

?>
