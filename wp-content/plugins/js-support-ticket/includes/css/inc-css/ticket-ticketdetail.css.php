<?php 

$jssupportticket_css = '';

/*Code for Css*/
$jssupportticket_css .= '

/* Tickets Details*/
	div.js-ticket-ticket-detail-wrapper{float: left;width: 100%;margin-top: 20px;}
	div.js-ticket-detail-wrapper{float: left;width: 100%; padding: 0px !important;}
	div.js-ticket-detail-box{float: left;width: 100%;border-bottom: 1px solid #DEDFE0;}
	div.js-ticket-detail-box div.js-ticket-detail-left{float: left;width: 20%;padding: 20px 5px;}
	div.js-ticket-detail-left div.js-ticket-user-img-wrp{display:inline-block;width:100%;text-align: center;margin: 5px 0px;}
	div.js-ticket-detail-left div.js-ticket-user-name-wrp{display:inline-block;width:100%;text-align: center;margin: 5px 0px;}
	div.js-ticket-detail-left div.js-ticket-user-email-wrp{display:inline-block;width:100%;text-align: center;margin: 5px 0px;}
	div.js-ticket-detail-box div.js-ticket-detail-right{float: left;width: calc(100% - 20%);}
	div.js-ticket-detail-box div.js-ticket-detail-right div.js-ticket-rows-wrapper{float: left;}
	div.js-ticket-detail-box div.js-ticket-detail-right div.js-ticket-rows-wrp{float: left;width: 100%;position: relative;padding:20px 0px 0px 20px;min-height: 296px;}
	div.js-ticket-detail-right div.js-ticket-row{float: left;width: 100%;padding: 0px 0 8px 0px;}
	div.js-ticket-detail-right div.js-ticket-row div.js-ticket-field-title{display: inline-block;width:auto;margin: 0px 5px 0px 0px;}
	div.js-ticket-detail-right div.js-ticket-row div.js-ticket-field-value{display: inline-block;width:auto;}
	div.js-ticket-status-note{display: inline-block;}
	div.js-ticket-detail-right div.js-ticket-row div.js-ticket-field-value.js-ticket-priorty{padding: 3px;min-width: 120px;text-align: center;}
	div.js-ticket-detail-right div.js-ticket-openclosed-box{display: inline-block;position: absolute;padding: 20px 5px; text-align: center;right: 10px;font-weight:bold;min-width: 80px;}
	div.js-ticket-detail-right div.js-ticket-right-bottom{display: inline-block;float: left;width: 100%;padding:10px 0px 0px 20px;}
	div.js-ticket-detail-wrapper div.js-ticket-action-btn-wrp{display: inline-block;float: left;width: 100%; padding:8px 5px;}
	div.js-ticket-detail-wrapper div.js-ticket-action-btn-wrp div.js-ticket-btn-box{display: inline-block;float: left;min-width:89px;text-align: center;margin-right: 5px;margin-left: 5px; margin-bottom: 5px;margin-top: 5px;}
	div.js-ticket-detail-wrapper div.js-ticket-action-btn-wrp div.js-ticket-btn-box a.js-button{display: inline-block;width: 100%;padding: 5px;}
	div.js-ticket-detail-wrapper div.js-ticket-more-actions-btn-wrp{display: inline-block;width: 100%;float: left;z-index: 9;text-align: center;}
	div.js-ticket-time-stamp-wrp{float: left;width: calc(100% - 40px);margin: 5px 20px 0px; border-top:1px solid #DFDCE3;}
	div.js-ticket-time-stamp-wrp span.js-ticket-ticket-created-date{display: inline-block;float: right;padding: 10px 10px;font-size: 14px;}
	
	div#action-div div.js-row{display: inline-block; border-top: 1px solid #ddeeee;width:60%;margin:0px 20%;padding-top: 10px;margin-top: 10px;}
	
	div.js-ticket-post-reply-wrapper {float: left;width: 100%;margin-top: 20px;}
	div.js-ticket-post-reply-wrapper div.js-ticket-thread-heading{display: inline-block;width: 100%;padding: 13px 15px;font-size: 18px;margin-bottom: 20px;}
	div.js-ticket-post-reply-box{margin-bottom: 20px;}
	div.js-ticket-attachments-wrp{display: inline-block;width: calc(100% - 40px); margin:0px 20px;padding: 15px 0px 10px 0px;}
	div.js-ticket-attachments-wrp div.js_ticketattachment{display: inline-block;width: calc(100% / 2 - 10px);padding: 5px 5px;margin: 0px 5px 10px;float: left;}
	div.js-ticket-attachments-wrp div.js_ticketattachment span.js-ticket-download-file-title{padding: 4px 0px;display: inline-block;float: left;}
	div.js-ticket-attachments-wrp div.js_ticketattachment a.js-download-button{display: inline-block;width: auto;padding: 3px 3px;text-align: center;float: right;}
	div.js-ticket-attachments-wrp div.js_ticketattachment a.js-download-button img.js-ticket-download-img{vertical-align:middle;}
	div.js-ticket-attachments-wrp a.js-all-download-button{display: inline-block;padding: 9px 5px;text-align: center;min-width: 145px;margin-left: 5px;}
	div.js-ticket-attachments-wrp a.js-all-download-button img.js-ticket-all-download-img{vertical-align: baseline;margin-right: 5px;}
	div.js-ticket-edit-options-wrp{float: left;width: calc(100% - 40px);padding: 15px 0px;margin: 5px 20px 0px;}
	div.js-ticket-edit-options-wrp a.js-button{display: inline-block;width:auto;padding: 5px;}
	div.js-ticket-field-value p {margin: 0px;line-height: 30px;}
	
	/* Post Reply Section */
	div.js-ticket-reply-forms-wrapper {float:left;width: 100%;}
	div.js-ticket-reply-forms-wrapper div.js-ticket-reply-forms-heading{display: inline-block;width: 100%;padding: 13px 15px;font-size: 18px;margin-bottom: 20px;}
	div.js-ticket-reply-forms-wrapper div.js-ticket-post-reply{display: inline-block;width: 100%;}
	div.js-ticket-reply-forms-wrapper div.js-ticket-post-reply div.js-ticket-reply-field-wrp{display: inline-block;width: 100%;}
	div.js-ticket-reply-attachments{display: inline-block;width: 100%;margin-bottom: 20px;}
	div.js-ticket-reply-attachments div.js-attachment-field-title{display: inline-block;width: 100%;padding: 15px 0px;}
	div.js-ticket-reply-attachments div.js-attachment-field{display: inline-block;width: 100%;}
	
	div.tk_attachment_value_wrapperform{float: left;width:100%;padding:0px 0px;}
	div.tk_attachment_value_wrapperform span.tk_attachment_value_text{float: left;width: calc(100% / 3 - 10px);padding: 5px 5px;margin: 5px 5px;position: relative;}
	div.tk_attachment_value_wrapperform span.tk_attachment_value_text input.js-attachment-inputbox{width: 100%;}
	span.tk_attachment_value_text span.tk_attachment_remove{background: url('.jssupportticket::$_pluginpath.'includes/images/close.png) no-repeat;background-size: 100% 100%;position: absolute;width: 20px;height: 20px;top: 12px;right:6px;}
	span.tk_attachments_configform{display: inline-block;float:left;line-height: 15px;margin-top: 10px;width: 100%; font-size: 11px;}
	span.tk_attachments_addform{position: relative; display: inline-block;padding:10px 10px;cursor: pointer;margin-top:10px;text-align: center;min-width: 120px;}
	
	div.js-ticket-closeonreply-wrp{float: left;width: 100%; margin-bottom: 10px;}
	div.js-ticket-closeonreply-wrp div.js-ticket-closeonreply-title{float: left;width: 100%;margin-bottom: 10px;}
	div.js-ticket-closeonreply-wrp div.js-form-title-position-reletive-left{width: 50%;padding: 10px;float: left;}
	
	div.js-ticket-reply-form-button-wrp{float: left;width: 100%;text-align: center;padding: 20px 0px 0px;margin-top: 40px;}
	div.js-ticket-reply-form-button-wrp input.js-ticket-save-button{min-width:120px;padding: 15px 5px;}
	div.js-ticket-reply-form-button-wrp input.js-ticket-cancel-button{min-width:120px;padding: 15px 5px;}
	
	div.replyFormStatus{width: 50%;padding: 10px;}
	div.replyFormStatus{width: 50%;padding: 10px;}
	
	label#forcloseonreply{display: inline-block;margin: 0px !important;}	

	select ::-ms-expand {display:none !important;}
	select{-webkit-appearance:none !important;}
';
/*Code For Colors*/
$jssupportticket_css .= '
/* Ticket Details Colors*/
	div.js-ticket-detail-wrapper{border:1px solid #DEDFE0;}
	div.js-ticket-detail-box div.js-ticket-detail-right div.js-ticket-rows-wrp{background-color: #fafafa}
	div.js-ticket-detail-box div.js-ticket-detail-right{border-left:1px solid #DEDFE0;}
	div.js-ticket-detail-right div.js-ticket-row div.js-ticket-field-title{color:#373435;}
	div.js-ticket-detail-right div.js-ticket-row div.js-ticket-field-value span.js-ticket-subject-link{color:#428bca;}
	div.js-ticket-detail-right div.js-ticket-openclosed-box{color:#ffffff}
	div.js-ticket-detail-right div.js-ticket-right-bottom{background-color:#fef1e6;color:#ffffff;border-top:1px solid #DEDFE0;}
	div.js-ticket-detail-wrapper div.js-ticket-action-btn-wrp div.js-ticket-btn-box{background-color:#e7ecf2;border:1px solid #DEDFE0;}
	
	div.js-ticket-post-reply-wrapper div.js-ticket-thread-heading{background-color:#e7ecf2;border:1px solid #DEDFE0;color:#373435;}
	div.js-ticket-post-reply-box{border:1px solid #DEDFE0;}
	div.js-ticket-white-background{background-color:#ffffff}
	div.js-ticket-background{background-color:#fafafa;border-left:1px solid #DEDFE0;}
	
	div.js-ticket-attachments-wrp{border-top:1px solid #DEDFE0;}
	div.js-ticket-attachments-wrp div.js_ticketattachment{border:1px solid #DEDFE0;background-color:#ffffff}
	div.js-ticket-attachments-wrp div.js_ticketattachment a.js-download-button{background-color:#428bca;color:#ffffffborder:1px solid #DEDFE0;}
	div.js-ticket-attachments-wrp a.js-all-download-button{background-color:#428bca;color:#ffffff;border:1px solid #DEDFE0;}
	
	
	/*Post Reply Section*/
	div.js-ticket-reply-forms-wrapper div.js-ticket-reply-forms-heading{background-color: #e7ecf2;border: 1px solid #DEDFE0;color: #373435;}
	div.tk_attachment_value_wrapperform{border: 1px solid #DEDFE0;background: #fafafa}
	span.tk_attachment_value_text{border: 1px solid #DEDFE0;background-color:#ffffff}
	div.js-ticket-reply-form-button-wrp{border-top: 2px solid #428bca;}
	div.js-ticket-reply-form-button-wrp input.js-ticket-save-button{background-color:#428bca;color:#ffffff}
	div.js-ticket-reply-form-button-wrp input.js-ticket-cancel-button{background-color:#48484a;color:#ffffff}
	div.js-ticket-closeonreply-wrp div.js-form-title-position-reletive-left{border:1px solid #DEDFE0;background-color:#fafafa}
		span.tk_attachments_addform{border: 1px solid #DEDFE0;background:#428bca;color:#ffffff}


	';


wp_add_inline_style('jsticket-style',$jssupportticket_css);


?>