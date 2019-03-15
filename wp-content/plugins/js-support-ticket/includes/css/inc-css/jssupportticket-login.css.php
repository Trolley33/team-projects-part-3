<?php 

$jssupportticket_css = '';

/*Code for Css*/
$jssupportticket_css .= '
/* Login Page */
	div.js-ticket-login-wrapper{float: left;width: 100%;}
	div.js-ticket-login-wrapper div.js-ticket-login{float: left;width: 100%;}
	div.js-ticket-login-wrapper div.js-ticket-login form#loginform-custom{width:100%;float: left;padding: 20px;margin: 0px;}
	form#loginform-custom p.login-username{width:48%;float:left;margin-right:4% !important;}
	form#loginform-custom p.login-username label{font-weight: unset;}
	form#loginform-custom p.login-password{width:48%;float:left;margin-bottom: 15px!important;}
	form#loginform-custom p.login-password label{font-weight: unset;}
	form#loginform-custom p.login-remember label{font-weight: unset;}
	form#loginform-custom p.login-remember {margin-top: 10px !important;float: left;width: 100%;}
	form#loginform-custom p.login-remember label input#rememberme{vertical-align: middle;}
	form#loginform-custom p.login-submit{width:100%;float:left;padding:20px 0px;text-align: center;margin-top:15px !important;}
	form#loginform-custom p.login-username input#user_login{border-radius: unset;width:100%;}
	form#loginform-custom p.login-password input#user_pass{border-radius: unset;width:100%;}
	form#loginform-custom p.login-submit input#wp-submit{}
	
	form.js-ticket-form{display:inline-block; width: 100%; margin-top: 20px;}
	div.js-ticket-add-form-wrapper{float: left;width: 100%;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp{float: left;width: calc(100% / 2 - 10px);margin: 0px 5px; margin-bottom: 20px; }
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field-title{float: left;width: 100%;margin-bottom: 5px;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field{float: left;width: 100%;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field input.js-ticket-form-field-input{float: left;width: 100%;border-radius: 0px;padding: 11px 5px;}
	div.js-ticket-form-btn-wrp{float: left;width:calc(100% - 20px);margin: 0px 10px;text-align: center;padding: 25px 0px 10px 0px;}
	div.js-ticket-form-btn-wrp input.js-ticket-save-button{padding: 20px 10px;margin-right: 10px;min-width: 120px;border-radius: 0px;}
	div.js-ticket-form-btn-wrp input.js-ticket-cancel-button{padding: 20px 10px;margin-right: 10px;min-width: 120px;border-radius: 0px;}
/* Login Page */
';
/*Code For Colors*/
$jssupportticket_css .= '

/* Add Form  Colors*/
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field input.js-ticket-form-field-input{background-color:#fafafa;border:1px solid #DEDFE0;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field select.js-ticket-form-field-select{background-color:#fafafa !important;border:1px solid #DEDFE0;}
	div.js-ticket-form-btn-wrp{border-top:2px solid #428bca;}
	div.js-ticket-form-btn-wrp input.js-ticket-save-button{background-color:#428bca;color:#ffffff;}

/* Add Form */

/* Login Page Colors */
	form#loginform-custom p.login-submit{border-top:1px solid #428bca;}
	form#loginform-custom p.login-username input#user_login{background-color:#fafafa; border:1px solid #DEDFE0;}
	form#loginform-custom p.login-password input#user_pass{background-color:#fafafa; border:1px solid #DEDFE0;}
	form#loginform-custom p.login-submit input#wp-submit{background-color:#428bca;}
	form#jsst_registration_form{float: left;width: 100%;margin-top: 20px;}

';


wp_add_inline_style('jsticket-style',$jssupportticket_css);


?>