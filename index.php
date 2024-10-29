<?php
/*
Plugin Name: AppReview
Plugin URI: http://podspod.o0o0.jp/appreview/
Description: An iOS and MacOS app's review can be written so easily! And the affiliate of LinkShare is also easy.
Version: 0.2.9
Author: podspod
Author URI: http://podspod.o0o0.jp/
*/ 
//ini_set('display_errors', true);
//include_once( dirname( __FILE__ ) . "/functions.php" );

function appreview_init() {
 $plugin_dir = basename(dirname(__FILE__));
 load_plugin_textdomain( 'appreview', false, $plugin_dir );
}
add_action('init', 'appreview_init');

function appreview_admin_head() {
	$pluginPath = "/wp-content/plugins/";
	$pluginUrl = get_settings('siteurl') . $pluginPath . plugin_basename(dirname(__FILE__));
	$css = $pluginUrl . '/style.css';
	$js = $pluginUrl . '/js/jquery.activity-indicator-1.0.0.js';
	echo '<link rel="stylesheet" type="text/css" href="' . $css . '" />';
	echo '<script type="text/javascript" src="' . $js . '"></script>'; 
	appreview_assist_affiliate();
}
add_action('admin_head', 'appreview_admin_head');

function appreview_assist_affiliate() {
	if ( strpos($_SERVER['REQUEST_URI'], '/wp-admin/') !== false ) {
		$linkshareId = get_option("linkshareId");
		$appreview_countrycode = get_option("appreview_countrycode");
		if ( $appreview_countrycode == undefined || $appreview_countrycode == null || $appreview_countrycode == '' ) {
			$appreview_countrycode = 'US';
		}
		$appreview_mediatype = get_option("appreview_mediatype");
		if ( $appreview_mediatype == undefined || $appreview_mediatype == null || $appreview_mediatype == '' ) {
			$appreview_mediatype = 'toppaidapplications';
		}
		$appreview_genre = get_option("appreview_genre");
		if ( $appreview_genre == undefined || $appreview_genre == null || $appreview_genre == '' ) {
			$appreview_genre = '';
		}
		$appreview_limit = get_option("appreview_limit");
		if ( $appreview_limit == undefined || $appreview_limit == null || $appreview_limit == '' ) {
			$appreview_limit = '10';
		}
?>
	<style>
		#assistaffiliatediv {
			height: 600px;
		}
		#appstoreranking {
			height: 420px;
			overflow: auto;
		}
		#appstoreranking .loading {
			margin: 0 auto;
			width: 500px;
			padding-top: 160px;
			text-align: center;
			font-size: 18px;
		}
		.appicon {
			float: left;
			margin-right: 4px;
			border-radius: 9px;
        		-webkit-border-radius: 9px;    /* Safari,Google Chrome用 */  
        		-moz-border-radius: 9px;   /* Firefox用 */  
		}
		.hide {
			display: none;
		}
		.clearfix:after {  
  			content: ".";   
  			display: block;   
  			height: 0;   
  			clear: both;   
  			visibility: hidden;  
		}
		.clearfix { display: inline-table; }  
		.app-ranking {
			margin-left: 10px;
		}
		.app-ranking li {
			float: left;
			width: 330px;
			height: 60px;
		}
	</style>
	<script type="text/javascript">
		(function($){
			jQuery(document).ready( function() {
				$("#postdivrich").after('\
<div id="assistaffiliatediv" class="postbox">\
<div class="handlediv" title="<?=mb_convert_encoding(__("Click to change", 'appreview'), "utf8", "auto")?>"><br></div><h3 class="hndle"><span><?=mb_convert_encoding(__("App Review Assistant", 'appreview'), "utf8", "auto")?></span></h3>\
<div style="margin: 10px; padding: 4px; border: 1px solid #094; background: #99FF99">\
<form action="https://www.paypal.com/cgi-bin/webscr" method="post">\
<input type="hidden" name="cmd" value="_s-xclick">\
<input type="hidden" name="hosted_button_id" value="CVAMQ46RJ5YRW">\
<table>\
<tr><td><input type="hidden" name="on0" value="If you like this plugin, please donate to support development.">If you like this plugin, please donate to support development and maintenance!</td><td><select name="os0">\
	<option value="(^^)">(^^) $5.00 USD</option>\
	<option value="(^O^)">(^O^) $10.00 USD</option>\
	<option value="v(^O^)v">v(^O^)v $20.00 USD</option>\
</select> </td>\
<td><input type="hidden" name="currency_code" value="USD">\
<input type="image" src="https://www.paypalobjects.com/en_US/i/btn/btn_paynowCC_LG.gif" border="0" name="submit" alt="PayPal - The safer, easier way to pay online!"></td>\
<td><img alt="" border="0" src="https://www.paypalobjects.com/ja_JP/i/scr/pixel.gif" width="1" height="1">\
</form></td></tr>\
</table>\
</div>\
<?php if ( $linkshareId == "" ) { ?>\
<div style="margin: 10px; margin-bottom: 0px; padding: 4px; border: 1px solid #FF8000; background-color: #FFCC00"><?=__("A site code has not been set up.", 'appreview')?><a href="http://www.linkshare.ne.jp/scland/mgm/?id=6yhEkcHUhxI" ><?=__("LinkShare", 'appreview')?></a><IMG border=0 width=1 height=1 src="http://ad.linksynergy.com/fs-bin/show?id=&bids=78559.10000104&type=3&subid=0" ><img border=0 width=1 height=1 src="http://ad.linksynergy.com/fs-bin/show?id=kuNv29ABV1c&bids=78559.10000104&type=3&subid=0" /><?=__(" registered, yet ?", 'appreview')?></div>\
<?php } ?>\
<form method="post" action="options.php" style="padding: 8px;">\
<?php wp_nonce_field("update-options"); ?>\
<span style="margin-left: 14px"><?=__("site code:", 'appreview')?></span><input type="text" id="linkshareId" name="linkshareId" value="<?=$linkshareId?>" />\
<select id="appreview_mediatype" name="appreview_mediatype">\
<option value="toppaidapplications" <?=($appreview_mediatype=='toppaidapplications')?'selected':''?>><?=__("iOS App(Paid)", 'appreview')?></option>\
<option value="topgrossingapplications" <?=($appreview_mediatype=='topgrossingapplications')?'selected':''?>><?=__("iOS App(Top Gross Paid)", 'appreview')?></option>\
<option value="toppaidipadapplications" <?=($appreview_mediatype=='toppaidipadapplications')?'selected':''?>><?=__("iPad App(Paid)", 'appreview')?></option>\
<option value="topgrossingipadapplications" <?=($appreview_mediatype=='topgrossingipadapplications')?'selected':''?>><?=__("iPad App(Top Gross Paid)", 'appreview')?></option>\
<option value="newpaidapplications" <?=($appreview_mediatype=='newpaidapplications')?'selected':''?>><?=__("iOS App(New Paid)", 'appreview')?></option>\
<option value="toppaidmacapps" <?=($appreview_mediatype=='toppaidmacapps')?'selected':''?>><?=__("Mac App(Paid)", 'appreview')?></option>\
<option value="topgrossingmacapps" <?=($appreview_mediatype=='topgrossingmacapps')?'selected':''?>><?=__("Mac App(Top Gross Paid)", 'appreview')?></option>\
</select>\
<select id="appreview_countrycode" name="appreview_countrycode">\
<option value="DZ" <?=($appreview_countrycode=="DZ")?'selected':''?>>Algeria</option>\
<option value="AO" <?=($appreview_countrycode=="AO")?'selected':''?>>Angola</option>\
<option value="AI" <?=($appreview_countrycode=="AI")?'selected':''?>>Anguilla</option>\
<option value="AG" <?=($appreview_countrycode=="AG")?'selected':''?>>Antigua and Barbuda</option>\
<option value="AR" <?=($appreview_countrycode=="AR")?'selected':''?>>Argentina</option>\
<option value="AM" <?=($appreview_countrycode=="AM")?'selected':''?>>Armenia</option>\
<option value="AU" <?=($appreview_countrycode=="AU")?'selected':''?>>Australia</option>\
<option value="AT" <?=($appreview_countrycode=="AT")?'selected':''?>>Austria</option>\
<option value="AZ" <?=($appreview_countrycode=="AZ")?'selected':''?>>Azerbaijan</option>\
<option value="BS" <?=($appreview_countrycode=="BS")?'selected':''?>>Bahamas</option>\
<option value="BH" <?=($appreview_countrycode=="BH")?'selected':''?>>Bahrain</option>\
<option value="BB" <?=($appreview_countrycode=="BB")?'selected':''?>>Barbados</option>\
<option value="BY" <?=($appreview_countrycode=="BY")?'selected':''?>>Belarus</option>\
<option value="BE" <?=($appreview_countrycode=="BE")?'selected':''?>>Belgium</option>\
<option value="BZ" <?=($appreview_countrycode=="BZ")?'selected':''?>>Belize</option>\
<option value="BM" <?=($appreview_countrycode=="BM")?'selected':''?>>Bermuda</option>\
<option value="BO" <?=($appreview_countrycode=="BO")?'selected':''?>>Bolivia</option>\
<option value="BW" <?=($appreview_countrycode=="BW")?'selected':''?>>Botswana</option>\
<option value="BR" <?=($appreview_countrycode=="BR")?'selected':''?>>Brazil</option>\
<option value="VG" <?=($appreview_countrycode=="VG")?'selected':''?>>British Virgin Islands</option>\
<option value="BN" <?=($appreview_countrycode=="BN")?'selected':''?>>Brunei Darussalam</option>\
<option value="BG" <?=($appreview_countrycode=="BG")?'selected':''?>>Bulgaria</option>\
<option value="CA" <?=($appreview_countrycode=="CA")?'selected':''?>>Canada</option>\
<option value="KY" <?=($appreview_countrycode=="KY")?'selected':''?>>Cayman Islands</option>\
<option value="CL" <?=($appreview_countrycode=="CL")?'selected':''?>>Chile</option>\
<option value="CN" <?=($appreview_countrycode=="CN")?'selected':''?>>China</option>\
<option value="CO" <?=($appreview_countrycode=="CO")?'selected':''?>>Colombia</option>\
<option value="CR" <?=($appreview_countrycode=="CR")?'selected':''?>>Costa Rica</option>\
<option value="HR" <?=($appreview_countrycode=="HR")?'selected':''?>>Croatia</option>\
<option value="CY" <?=($appreview_countrycode=="CY")?'selected':''?>>Cyprus</option>\
<option value="CZ" <?=($appreview_countrycode=="CZ")?'selected':''?>>Czech Republic</option>\
<option value="DK" <?=($appreview_countrycode=="DK")?'selected':''?>>Denmark</option>\
<option value="DM" <?=($appreview_countrycode=="DM")?'selected':''?>>Dominica</option>\
<option value="DO" <?=($appreview_countrycode=="DO")?'selected':''?>>Dominican Republic</option>\
<option value="EC" <?=($appreview_countrycode=="EC")?'selected':''?>>Ecuador</option>\
<option value="EG" <?=($appreview_countrycode=="EG")?'selected':''?>>Egypt</option>\
<option value="SV" <?=($appreview_countrycode=="SV")?'selected':''?>>El Salvador</option>\
<option value="EE" <?=($appreview_countrycode=="EE")?'selected':''?>>Estonia</option>\
<option value="FI" <?=($appreview_countrycode=="FI")?'selected':''?>>Finland</option>\
<option value="FR" <?=($appreview_countrycode=="FR")?'selected':''?>>France</option>\
<option value="DE" <?=($appreview_countrycode=="DE")?'selected':''?>>Germany</option>\
<option value="GH" <?=($appreview_countrycode=="GH")?'selected':''?>>Ghana</option>\
<option value="GR" <?=($appreview_countrycode=="GR")?'selected':''?>>Greece</option>\
<option value="GD" <?=($appreview_countrycode=="GD")?'selected':''?>>Grenada</option>\
<option value="GT" <?=($appreview_countrycode=="GT")?'selected':''?>>Guatemala</option>\
<option value="GY" <?=($appreview_countrycode=="GY")?'selected':''?>>Guyana</option>\
<option value="HN" <?=($appreview_countrycode=="HN")?'selected':''?>>Honduras</option>\
<option value="HK" <?=($appreview_countrycode=="HK")?'selected':''?>>Hong Kong</option>\
<option value="HU" <?=($appreview_countrycode=="HU")?'selected':''?>>Hungary</option>\
<option value="IS" <?=($appreview_countrycode=="IS")?'selected':''?>>Iceland</option>\
<option value="IN" <?=($appreview_countrycode=="IN")?'selected':''?>>India</option>\
<option value="ID" <?=($appreview_countrycode=="ID")?'selected':''?>>Indonesia</option>\
<option value="IE" <?=($appreview_countrycode=="IE")?'selected':''?>>Ireland</option>\
<option value="IL" <?=($appreview_countrycode=="IL")?'selected':''?>>Israel</option>\
<option value="IT" <?=($appreview_countrycode=="IT")?'selected':''?>>Italy</option>\
<option value="JM" <?=($appreview_countrycode=="JM")?'selected':''?>>Jamaica</option>\
<option value="JP" <?=($appreview_countrycode=="JP")?'selected':''?>>Japan</option>\
<option value="JO" <?=($appreview_countrycode=="JO")?'selected':''?>>Jordan</option>\
<option value="KZ" <?=($appreview_countrycode=="KZ")?'selected':''?>>Kazakstan</option>\
<option value="KE" <?=($appreview_countrycode=="KE")?'selected':''?>>Kenya</option>\
<option value="KR" <?=($appreview_countrycode=="KR")?'selected':''?>>Korea, Republic Of</option>\
<option value="KW" <?=($appreview_countrycode=="KW")?'selected':''?>>Kuwait</option>\
<option value="LV" <?=($appreview_countrycode=="LV")?'selected':''?>>Latvia</option>\
<option value="LB" <?=($appreview_countrycode=="LB")?'selected':''?>>Lebanon</option>\
<option value="LT" <?=($appreview_countrycode=="LT")?'selected':''?>>Lithuania</option>\
<option value="LU" <?=($appreview_countrycode=="LU")?'selected':''?>>Luxembourg</option>\
<option value="MO" <?=($appreview_countrycode=="MO")?'selected':''?>>Macau</option>\
<option value="MK" <?=($appreview_countrycode=="MK")?'selected':''?>>Macedonia</option>\
<option value="MG" <?=($appreview_countrycode=="MG")?'selected':''?>>Madagascar</option>\
<option value="MY" <?=($appreview_countrycode=="MY")?'selected':''?>>Malaysia</option>\
<option value="ML" <?=($appreview_countrycode=="ML")?'selected':''?>>Mali</option>\
<option value="MT" <?=($appreview_countrycode=="MT")?'selected':''?>>Malta</option>\
<option value="MU" <?=($appreview_countrycode=="MU")?'selected':''?>>Mauritius</option>\
<option value="MX" <?=($appreview_countrycode=="MX")?'selected':''?>>Mexico</option>\
<option value="MD" <?=($appreview_countrycode=="MD")?'selected':''?>>Moldova</option>\
<option value="MS" <?=($appreview_countrycode=="MS")?'selected':''?>>Montserrat</option>\
<option value="NL" <?=($appreview_countrycode=="NL")?'selected':''?>>Netherlands</option>\
<option value="NZ" <?=($appreview_countrycode=="NZ")?'selected':''?>>New Zealand</option>\
<option value="NI" <?=($appreview_countrycode=="NI")?'selected':''?>>Nicaragua</option>\
<option value="NE" <?=($appreview_countrycode=="NE")?'selected':''?>>Niger</option>\
<option value="NG" <?=($appreview_countrycode=="NG")?'selected':''?>>Nigeria</option>\
<option value="NO" <?=($appreview_countrycode=="NO")?'selected':''?>>Norway</option>\
<option value="OM" <?=($appreview_countrycode=="OM")?'selected':''?>>Oman</option>\
<option value="PK" <?=($appreview_countrycode=="PK")?'selected':''?>>Pakistan</option>\
<option value="PA" <?=($appreview_countrycode=="PA")?'selected':''?>>Panama</option>\
<option value="PY" <?=($appreview_countrycode=="PY")?'selected':''?>>Paraguay</option>\
<option value="PE" <?=($appreview_countrycode=="PE")?'selected':''?>>Peru</option>\
<option value="PH" <?=($appreview_countrycode=="PH")?'selected':''?>>Philippines</option>\
<option value="PL" <?=($appreview_countrycode=="PL")?'selected':''?>>Poland</option>\
<option value="PT" <?=($appreview_countrycode=="PT")?'selected':''?>>Portugal</option>\
<option value="QA" <?=($appreview_countrycode=="QA")?'selected':''?>>Qatar</option>\
<option value="RO" <?=($appreview_countrycode=="RO")?'selected':''?>>Romania</option>\
<option value="RU" <?=($appreview_countrycode=="RU")?'selected':''?>>Russia</option>\
<option value="SA" <?=($appreview_countrycode=="SA")?'selected':''?>>Saudi Arabia</option>\
<option value="SN" <?=($appreview_countrycode=="SN")?'selected':''?>>Senegal</option>\
<option value="SG" <?=($appreview_countrycode=="SG")?'selected':''?>>Singapore</option>\
<option value="SK" <?=($appreview_countrycode=="SK")?'selected':''?>>Slovakia</option>\
<option value="SI" <?=($appreview_countrycode=="SI")?'selected':''?>>Slovenia</option>\
<option value="ZA" <?=($appreview_countrycode=="ZA")?'selected':''?>>South Africa</option>\
<option value="ES" <?=($appreview_countrycode=="ES")?'selected':''?>>Spain</option>\
<option value="LK" <?=($appreview_countrycode=="LK")?'selected':''?>>Sri Lanka</option>\
<option value="KN" <?=($appreview_countrycode=="KN")?'selected':''?>>St. Kitts and Nevis</option>\
<option value="LC" <?=($appreview_countrycode=="LC")?'selected':''?>>St. Lucia</option>\
<option value="VC" <?=($appreview_countrycode=="VC")?'selected':''?>>St. Vincent and The Grenadines</option>\
<option value="SR" <?=($appreview_countrycode=="SR")?'selected':''?>>Suriname</option>\
<option value="SE" <?=($appreview_countrycode=="SE")?'selected':''?>>Sweden</option>\
<option value="CH" <?=($appreview_countrycode=="CH")?'selected':''?>>Switzerland</option>\
<option value="TW" <?=($appreview_countrycode=="TW")?'selected':''?>>Taiwan</option>\
<option value="TZ" <?=($appreview_countrycode=="TZ")?'selected':''?>>Tanzania</option>\
<option value="TH" <?=($appreview_countrycode=="TH")?'selected':''?>>Thailand</option>\
<option value="TT" <?=($appreview_countrycode=="TT")?'selected':''?>>Trinidad and Tobago</option>\
<option value="TN" <?=($appreview_countrycode=="TN")?'selected':''?>>Tunisia</option>\
<option value="TR" <?=($appreview_countrycode=="TR")?'selected':''?>>Turkey</option>\
<option value="TC" <?=($appreview_countrycode=="TC")?'selected':''?>>Turks and Caicos Islands</option>\
<option value="UG" <?=($appreview_countrycode=="UG")?'selected':''?>>Uganda</option>\
<option value="GB" <?=($appreview_countrycode=="GB")?'selected':''?>>United Kingdom</option>\
<option value="AE" <?=($appreview_countrycode=="AE")?'selected':''?>>United Arab Emirates</option>\
<option value="UY" <?=($appreview_countrycode=="UY")?'selected':''?>>Uruguay</option>\
<option value="US" <?=($appreview_countrycode=="US")?'selected':''?>>United States</option>\
<option value="UZ" <?=($appreview_countrycode=="UZ")?'selected':''?>>Uzbekistan</option>\
<option value="VE" <?=($appreview_countrycode=="VE")?'selected':''?>>Venezuela</option>\
<option value="VN" <?=($appreview_countrycode=="VN")?'selected':''?>>Vietnam</option>\
<option value="YE" <?=($appreview_countrycode=="YE")?'selected':''?>>Yemen</option>\
</select>\
<select id="appreview_genre" name="appreview_genre">\
<option class="option_default" value="99999" <?=($appreview_genre=="99999")?'selected':''?>><?=__('All', 'appreview')?></option>\
<option class="option_mac" value="12001" <?=($appreview_genre=="12001")?'selected':''?>><?=__('Business', 'appreview')?></option>\
<option class="option_mac" value="12002" <?=($appreview_genre=="12002")?'selected':''?>><?=__('Developer Tools', 'appreview')?></option>\
<option class="option_mac" value="12003" <?=($appreview_genre=="12003")?'selected':''?>><?=__('Education', 'appreview')?></option>\
<option class="option_mac" value="12004" <?=($appreview_genre=="12004")?'selected':''?>><?=__('Entertainment', 'appreview')?></option>\
<option class="option_mac" value="12005" <?=($appreview_genre=="12005")?'selected':''?>><?=__('Finance', 'appreview')?></option>\
<option class="option_mac" value="12006" <?=($appreview_genre=="12006")?'selected':''?>><?=__('Games', 'appreview')?></option>\
<option class="option_mac" value="12007" <?=($appreview_genre=="12007")?'selected':''?>><?=__('Health &amp; Fitness', 'appreview')?></option>\
<option class="option_mac" value="12008" <?=($appreview_genre=="12008")?'selected':''?>><?=__('Lifestyle', 'appreview')?></option>\
<option class="option_mac" value="12010" <?=($appreview_genre=="12010")?'selected':''?>><?=__('Medical', 'appreview')?></option>\
<option class="option_mac" value="12011" <?=($appreview_genre=="12011")?'selected':''?>><?=__('Music', 'appreview')?></option>\
<option class="option_mac" value="12012" <?=($appreview_genre=="12012")?'selected':''?>><?=__('News', 'appreview')?></option>\
<option class="option_mac" value="12013" <?=($appreview_genre=="12013")?'selected':''?>><?=__('Photography', 'appreview')?></option>\
<option class="option_mac" value="12014" <?=($appreview_genre=="12014")?'selected':''?>><?=__('Productivity', 'appreview')?></option>\
<option class="option_mac" value="12015" <?=($appreview_genre=="12015")?'selected':''?>><?=__('Reference', 'appreview')?></option>\
<option class="option_mac" value="12016" <?=($appreview_genre=="12016")?'selected':''?>><?=__('Social Networking', 'appreview')?></option>\
<option class="option_mac" value="12017" <?=($appreview_genre=="12017")?'selected':''?>><?=__('Sports', 'appreview')?></option>\
<option class="option_mac" value="12018" <?=($appreview_genre=="12018")?'selected':''?>><?=__('Travel', 'appreview')?></option>\
<option class="option_mac" value="12019" <?=($appreview_genre=="12019")?'selected':''?>><?=__('Utilities', 'appreview')?></option>\
<option class="option_mac" value="12020" <?=($appreview_genre=="12020")?'selected':''?>><?=__('Video', 'appreview')?></option>\
<option class="option_mac" value="12021" <?=($appreview_genre=="12021")?'selected':''?>><?=__('Weather', 'appreview')?></option>\
<option class="option_mac" value="12022" <?=($appreview_genre=="12022")?'selected':''?>><?=__('Graphics &amp; Design', 'appreview')?></option>\
<option class="option_ios" value="6018" <?=($appreview_genre=='6018')?'selected':''?>><?=__('Books')?></option>\
<option class="option_ios" value="6000" <?=($appreview_genre=='6000')?'selected':''?>><?=__('Business')?></option>\
<option class="option_ios" value="6022" <?=($appreview_genre=='6022')?'selected':''?>><?=__('Catalogs')?></option>\
<option class="option_ios" value="6017" <?=($appreview_genre=='6017')?'selected':''?>><?=__('Education')?></option>\
<option class="option_ios" value="6016" <?=($appreview_genre=='6016')?'selected':''?>><?=__('Entertainment')?></option>\
<option class="option_ios" value="6015" <?=($appreview_genre=='6015')?'selected':''?>><?=__('Finance')?></option>\
<option class="option_ios" value="6014" <?=($appreview_genre=='6014')?'selected':''?>><?=__('Games')?></option>\
<option class="option_ios" value="6013" <?=($appreview_genre=='6013')?'selected':''?>><?=__('Health &amp; Fitness')?></option>\
<option class="option_ios" value="6012" <?=($appreview_genre=='6012')?'selected':''?>><?=__('Lifestyle')?></option>\
<option class="option_ios" value="6020" <?=($appreview_genre=='6020')?'selected':''?>><?=__('Medical')?></option>\
<option class="option_ios" value="6011" <?=($appreview_genre=='6011')?'selected':''?>><?=__('Music')?></option>\
<option class="option_ios" value="6010" <?=($appreview_genre=='6010')?'selected':''?>><?=__('Navigation')?></option>\
<option class="option_ios" value="6009" <?=($appreview_genre=='6009')?'selected':''?>><?=__('News')?></option>\
<option class="option_ios" value="6021" <?=($appreview_genre=='6021')?'selected':''?>><?=__('Newsstand')?></option>\
<option class="option_ios" value="6008" <?=($appreview_genre=='6008')?'selected':''?>><?=__('Photo &amp; Video')?></option>\
<option class="option_ios" value="6007" <?=($appreview_genre=='6007')?'selected':''?>><?=__('Productivity')?></option>\
<option class="option_ios" value="6006" <?=($appreview_genre=='6006')?'selected':''?>><?=__('Reference')?></option>\
<option class="option_ios" value="6005" <?=($appreview_genre=='6005')?'selected':''?>><?=__('Social Networking')?></option>\
<option class="option_ios" value="6004" <?=($appreview_genre=='6004')?'selected':''?>><?=__('Sports')?></option>\
<option class="option_ios" value="6003" <?=($appreview_genre=='6003')?'selected':''?>><?=__('Travel')?></option>\
<option class="option_ios" value="6002" <?=($appreview_genre=='6002')?'selected':''?>><?=__('Utilities')?></option>\
<option class="option_ios" value="6001" <?=($appreview_genre=='6001')?'selected':''?>><?=__('Weather')?></option>\
</select>\
<select id="appreview_limit" name="appreview_limit">\
<option value="10" <?=($appreview_limit=="10")?'selected':''?>><?=__("10 affairs", 'appreview')?></option>\
<option value="100" <?=($appreview_limit=="100")?'selected':''?>><?=__("100 affairs", 'appreview')?></option>\
<option value="300" <?=($appreview_limit=="300")?'selected':''?>><?=__("300 affairs", 'appreview')?></option>\
</select>\
<span style="margin: 8px;">\
<input type="hidden" name="action" value="update" />\
<input type="hidden" name="page_options" value="linkshareId,appreview_countrycode,appreview_mediatype,appreview_genre,appreview_limit" />\
<input type="submit" class="button-primary" value="<?php _e("Save Changes", 'appreview') ?>" />\
</span>\
<div id="appstoreranking"><div class="loading"><?=__("Updating now...(Please wait a several minutes.)", 'appreview')?></div></div>\
					'); 
				refleshCategory( false );
				$("#appreview_mediatype").bind( "change", function() {
					refleshCategory( true );
				} );

				function refleshCategory(isReflesh) {
					switch ( $("#appreview_mediatype").val() ) {
						case "toppaidapplications":
						case "topgrossingapplications":
						case "toppaidipadapplications":
						case "topgrossingipadapplications":
						case "newpaidapplications":
							$('.option_mac').hide();
							$('.option_ios').show();
							break;
						default:
						case "toppaidmacapps":
						case "topgrossingmacapps":
							$('.option_mac').show();
							$('.option_ios').hide();
							break;
					}
					if ( isReflesh == true ) {
						$("#appreview_genre").val("99999");
					}
				}
				$("#appstoreranking").activity();
				$("#appstoreranking").load("/wp-content/plugins/appreview/getFeed.php?linkshareId=<?=$linkshareId?>&appreview_countrycode=<?=$appreview_countrycode?>&appreview_mediatype=<?=$appreview_mediatype?>&appreview_genre=<?=$appreview_genre?>&appreview_limit=<?=$appreview_limit?>", function() {
					$("#appstoreranking").activity(false);
				});
				//$('#reloadButton').bind( 'click', function() {
					//reloadRanking();
				//} );
				function reloadRanking() {
					$("#appstoreranking").activity();
					$("#appstoreranking").load("/wp-content/plugins/appreview/getFeed.php?mode=reload&linkshareId=<?=$linkshareId?>&appreview_countrycode=<?=$appreview_countrycode?>&appreview_mediatype=<?=$appreview_mediatype?>&appreview_genre=<?=$appreview_genre?>&appreview_limit=<?=$appreview_limit?>", function() {
						$("#appstoreranking").activity(false);
					});
				}
			});
		})(jQuery);
			var iTunesButtonFormat = '<img height="15" width="61" class="iTunesButton" style="float: left; margin-top: 4px; margin-right: 2px;" src="http://ax.phobos.apple.com.edgesuite.net/images/badgeitunes61x15dark.gif" />';
			//function writingReview( icon, link, name, link_enclosure ) {
			function writingReview( icon, link, name, price, genre, release ) {
				var today = new Date();
				//var priceCaution = "<?=__("[Price is ", 'appreview')?>" + today.getFullYear() + "<?=__("/", 'appreview')?>" + (today.getMonth() + 1) + "<?=__("/", 'appreview')?>" +  today.getDate() + "<?=__("]", 'appreview')?>";
				var priceCaution = "<?=__("[Price is ", 'appreview')?>" + today.getFullYear() + "/" + (today.getMonth() + 1) + "/" +  today.getDate() + "<?=__("]", 'appreview')?>";
				switchEditors.switchto(document.getElementById("content-html"));
				if ( document.getElementById('title').value == '' ) {
					document.getElementById('title').value = name;
				}
				document.getElementById('content').value += '<a href="' + link + '" target="_blank"><img src="' + icon + '" style="float: left; margin: 4px; margin-bottom: 0px; margin-right: 6px; border-radius: 9px; -webkit-border-radius: 9px; -moz-border-radius: 9px;" />' + stripslashes(name) + '<br />' + '</a>' + genre + '<br /><?=__("Price:", 'appreview')?>' + price + priceCaution + '<br /><a href="' + link + '" target="_blank">' + iTunesButtonFormat + '</a>' + release;
				//document.getElementById('content').value += '<img src="' + stripslashes(link_enclosure) + '" width="200px" />';
				//$('#content-tmce').trigger('click');
				switchEditors.switchto(document.getElementById("content-tmce"));
			}
			function addslashes(str) {
				str=str.replace(/\\/g,'\\\\');
				str=str.replace(/\'/g,'\\\'');
				str=str.replace(/\"/g,'\\"');
				str=str.replace(/\0/g,'\\0');
				return str;
			}
			function stripslashes(str) {
				str=str.replace(/\\'/g,'\'');
				str=str.replace(/\\"/g,'"');
				str=str.replace(/\\0/g,'\0');
				str=str.replace(/\\\\/g,'\\');
				return str;
			}
		</script>
<?php
	}
}
?>
