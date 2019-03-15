<?php 

$jssupportticket_css = '';

/*Code for Css*/
$jssupportticket_css .= '

/* Ticket Status */
	form.js-ticket-form{display:inline-block; width: 100%; margin-top: 20px;}
	div.js-ticket-checkstatus-wrp{float: left;width: 100%;}
	div.js-ticket-checkstatus-wrp div.js-ticket-checkstatus-field-wrp{float: left;width: calc(100% / 2 - 50px); margin:0px 25px;margin-bottom: 25px;}
	div.js-ticket-field-title{float: left;width: 100%;margin-bottom: 10px;}
	div.js-ticket-field-wrp{float: left;width: 100%;}
	div.js-ticket-field-wrp input.js-ticket-form-input-field{border-radius: 0px;padding: 11px 5px;width: 100%;background-color:#fafafa; border:1px solid #DEDFE0;}
	div.js-ticket-form-btn-wrp{float: left;width:calc(100% - 20px);margin: 0px 10px;text-align: center;padding: 25px 0px 10px 0px;}
	div.js-ticket-form-btn-wrp input.js-ticket-save-button{padding: 20px 10px;margin-right: 10px;min-width: 120px;border-radius: 0px;}
	div.js-ticket-form-btn-wrp input.js-ticket-cancel-button{padding: 20px 10px;margin-right: 10px;min-width: 120px;border-radius: 0px;}
	div.js-ticket-form-btn-wrp{border-top:2px solid #428bca;}
	div.js-ticket-form-btn-wrp input.js-ticket-save-button{background-color:#428bca;color:#ffffff;}
	a.js-ticket-cancel-button{min-width:120px;padding: 14px 5px;border-radius: 0px;display: inline-block; background: #606062;color:#ffffff;text-decoration: none !important;outline: 0px !important;}
/* Ticket Status */
';
/*Code For Colors*/
$jssupportticket_css .= '



';


wp_add_inline_style('jsticket-style',$jssupportticket_css);

?>