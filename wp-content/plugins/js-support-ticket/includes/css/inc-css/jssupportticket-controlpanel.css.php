<?php 
$jssupportticket_css = '';

/*Code for Css*/
$jssupportticket_css .= '
	/* Dashboard Menu Links */
	div#js-dash-menu-link-wrp{display: inline-block;width: 100%;margin: 25px 0px 0px 0px;}
	div.js-section-heading{display: inline-block;width: 100%;padding: 13px 15px;font-size: 18px;}
	div.js-menu-links-wrp{display: inline-block;width: 100%;margin-top: 15px;}
	div.js-menu-links-wrp a.js-ticket-dash-menu{display: inline-block; float: left;width:calc(100% / 6 - 10px); margin-right:5px;margin-left: 5px;margin-bottom: 10px;margin-top: 10px;padding: 15px 0px;}
	div.js-menu-links-wrp a.js-ticket-dash-menu div.js-ticket-dash-menu-icon{float: left;width: 100%;text-align: center;}
	div.js-menu-links-wrp a.js-ticket-dash-menu div.js-ticket-dash-menu-icon img.js-ticket-dash-menu-img{}
	div.js-menu-links-wrp a.js-ticket-dash-menu span.js-ticket-dash-menu-text{display: inline-block;float: left;width: 100%;text-align: center;margin: 20px 0px;}
	.js-col-xs-12.js-col-sm-6.js-col-md-4.js-ticket-dash-menu:hover {box-shadow: 0 1px 3px 0 rgba(60,64,67,0.302),0 4px 8px 3px rgba(60,64,67,0.149);background-color: #fafafb;}
	
';
/*Code For Colors*/
$jssupportticket_css .= '
	/* Dashboard Menu Links Colors */
	div.js-section-heading{background-color:#fafafa;border:1px solid #DEDFE0;color:#373435;}
	div.js-menu-links-wrp a.js-ticket-dash-menu{background-color:#fafafa;border:1px solid #DEDFE0;}
	div.js-menu-links-wrp a.js-ticket-dash-menu span.js-ticket-dash-menu-text{color:#838386;}
	/* Dashboard Menu Links */

';


wp_add_inline_style('jsticket-style',$jssupportticket_css);


?>