<?php 


$jssupportticket_css = '';

/*Code for Css*/
$jssupportticket_css .= '
	/* Add form */
	form.js-ticket-form{display:inline-block; width: 100%; margin-top: 20px;}
	div.js-ticket-add-form-wrapper{float: left;width: 100%;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp{float: left;width: calc(100% / 2 - 10px);margin: 0px 5px; margin-bottom: 20px; }
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp.js-ticket-from-field-wrp-full-width{float: left;width: calc(100% / 1 - 10px); margin-bottom: 30px; }
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field-title{float: left;width: 100%;margin-bottom: 5px;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field{float: left;width: 100%;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field input.js-ticket-form-field-input{float: left;width: 100%;border-radius: 0px;padding: 11px 5px;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field select.js-ticket-form-field-select{float: left;width: 100%;border-radius: 0px;background: url('.jssupportticket::$_pluginpath.'includes/images/selecticon.png) 96% / 4% no-repeat #eee;}
	div.js-ticket-form-btn-wrp{float: left;width:calc(100% - 20px);margin: 0px 10px;text-align: center;padding: 25px 0px 10px 0px;}
	div.js-ticket-form-btn-wrp input.js-ticket-save-button{padding: 20px 10px;margin-right: 10px;min-width: 120px;border-radius: 0px;}
	div.js-ticket-form-btn-wrp input.js-ticket-cancel-button{padding: 20px 10px;margin-right: 10px;min-width: 120px;border-radius: 0px;}
	
	div.js-ticket-reply-attachments{display: inline-block;width: 100%;margin-bottom: 20px;}
	div.js-ticket-reply-attachments div.js-attachment-field-title{display: inline-block;width: 100%;padding: 15px 0px;}
	div.js-ticket-reply-attachments div.js-attachment-field{display: inline-block;width: 100%;}
	div.tk_attachment_value_wrapperform{float: left;width:100%;padding:0px 0px;}
	div.tk_attachment_value_wrapperform span.tk_attachment_value_text{float: left;width: calc(100% / 3 - 10px);padding: 5px 5px;margin: 5px 5px;position: relative;}
	div.tk_attachment_value_wrapperform span.tk_attachment_value_text input.js-attachment-inputbox{width: 100%;}
	span.tk_attachment_value_text span.tk_attachment_remove{background: url('.jssupportticket::$_pluginpath.'includes/images/close.png) no-repeat;background-size: 100% 100%;position: absolute;width: 20px;height: 20px;top: 12px;right:6px;}
	span.tk_attachments_configform{display: inline-block;float:left;line-height: 15px;margin-top: 10px;width: 100%; font-size: 11px;}
	span.tk_attachments_addform{position: relative; display: inline-block;padding:10px 15px;cursor: pointer;margin-top:10px;}
	select.js-ticket-select-field{float: left;width: 100%;border-radius: 0px;background: url('.jssupportticket::$_pluginpath.'includes/images/selecticon.png) 96% / 4% no-repeat ; }
	select{-moz-appearance: none !important; padding:0px 5px !important;}
	span.help-block{font-size: 11px;}
	div.js-user-captcha-padding{float: left;width: 100%;margin-bottom: 20px;}
	div.js-user-captcha-padding div.js-user-captcha-value{padding: 0px !important ;}
	div.js-col-md-12.js-form-wrapper{padding: 0px;}
	div.js-col-md-12.js-user-captcha-title{padding: 0px;}

	select ::-ms-expand {display:none !important;}
	select{-webkit-appearance:none !important;}
	

	
';
/*Code For Colors*/
$jssupportticket_css .= '

	/* Add Form  Colors*/
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field input.js-ticket-form-field-input{background-color:#fafafa;border:1px solid #DEDFE0;}
	div.js-ticket-add-form-wrapper div.js-ticket-from-field-wrp div.js-ticket-from-field select.js-ticket-form-field-select{background-color:#fafafa !important;border:1px solid #DEDFE0;}
	div.js-ticket-form-btn-wrp{border-top:2px solid #428bca;}
	div.js-ticket-form-btn-wrp input.js-ticket-save-button{background-color:#428bca;color:#ffffff;}
		a.js-ticket-cancel-button{min-width:120px;padding: 14px 5px;border-radius: 0px;display: inline-block; background: #606062;color:#ffffff;text-decoration: none !important;outline: 0px !important;}

	div.tk_attachment_value_wrapperform{border: 1px solid #DEDFE0;background: #fafafa;}
	span.tk_attachment_value_text{border: 1px solid #DEDFE0;background-color:#ffffff;}
	span.tk_attachments_addform{border: 1px solid #DEDFE0;background:#428bca;color:#ffffff}
	select.js-ticket-select-field{background-color:#fafafa !important;border:1px solid #DEDFE0;}
	label{font-weight: unset !important;}
	input.js-ticket-recaptcha{border-radius: unset !important;;padding: 11px 5px !important;}
	input.js-ticket-recaptcha{background-color:#fafafa !important;border:1px solid #DEDFE0 !important;}
/* Add Form */

';



wp_add_inline_style('jsticket-style',$jssupportticket_css);


?>