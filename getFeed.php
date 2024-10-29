<?php
	require_once('../../../wp-load.php');
	ini_set('display_errors', true);
	define( 'MAGPIE_OUTPUT_ENCODING', 'UTF-8' );
	require_once 'magpierss/rss_fetch.inc';

	$linkshareId = $_GET['linkshareId'];
	if ( !isset( $_GET['appreview_countrycode'] ) || $_GET['appreview_countrycode'] == null || $_GET['appreview_countrycode'] == '' ) {
		$appreview_countrycode = 'us';
	} else {
		$appreview_countrycode = $_GET['appreview_countrycode'];
	}
	if ( !isset( $_GET['appreview_mediatype'] ) || $_GET['appreview_mediatype'] == null || $_GET['appreview_mediatype'] == '' ) {
		$appreview_mediatype = 'toppaidapplications';
	} else {
		$appreview_mediatype = $_GET['appreview_mediatype'];
	}
	if ( !isset( $_GET['appreview_genre'] ) || $_GET['appreview_genre'] == null || $_GET['appreview_genre'] == '' || $_GET['appreview_genre'] == '99999' ) {
		$appreview_genre = '99999';
	} else {
		$appreview_genre = $_GET['appreview_genre'];
	}
	if ( !isset( $_GET['appreview_limit'] ) || $_GET['appreview_limit'] == null || $_GET['appreview_limit'] == '' ) {
		$appreview_limit = '10';
	} else {
		$appreview_limit = $_GET['appreview_limit'];
	}
	$linkshareBase = "http://click.linksynergy.com/fs-bin/stat?id=" . $linkshareId . "&offerid=94348&type=3&subid=0&tmpid=2192&RD_PARM1=";
	$iTunesButtonFormat = '<img height="15" width="61" alt="%s" class="iTunesButton" src="http://ax.phobos.apple.com.edgesuite.net/images/badgeitunes61x15dark.gif" />';

	$path_base = dirname( __FILE__ );
	$today = date( 'Ymd' );
	//$countryID = 143462;
	//$countryID = intval( $appreview_countrycode );
	$countryID = strtolower( $appreview_countrycode );
	//$ranking = $path_base . '/result/ranking.' . $today . '.html';
	$ranking = $path_base . '/result/' . $appreview_mediatype . '_' . $countryID . '_' . $appreview_genre . '.' . $today . '.' . $appreview_limit . '.html';
	if ( isset( $_GET['mode'] ) && $_GET['mode'] == 'reload' ) {
	} else if ( file_exists( $ranking ) ) {
		$result = file_get_contents( $ranking );
		if ( strlen( $linkshareId ) == 11 && strpos( $result, $linkshareId ) === false ) {
		} else {
			echo $result;
			exit;
		}
	}
		
	$fp = fopen( $ranking, 'wt' );
	//$requestBeforeUri = "http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/ws/RSS/toppaidapplications/sf=";
	//$requestBeforeUri = "http://ax.itunes.apple.com/WebObjects/MZStoreServices.woa/ws/RSS/" . $appreview_mediatype . "/sf=";
	$requestBeforeUri = "http://itunes.apple.com/" . $countryID . "/rss/" . $appreview_mediatype . "/sf=";
	//$requestAfterUri = "/limit=100/xml";
	//$requestAfterUri = "/limit=" . intval( $appreview_limit ) . "/xml";
	if ( $appreview_genre == '99999' ) {
		$requestAfterUri = "/limit=" . intval( $appreview_limit ) . "/xml";
	} else {
		$requestAfterUri = "/limit=" . intval( $appreview_limit ) . "/genre=" . intval( $appreview_genre ) . "/xml";
	}
	//$request = $requestBeforeUri . $countryID . $requestAfterUri;
	$request = $requestBeforeUri . $requestAfterUri;
	$rss = fetch_rss($request);
	$title = $rss->channel['title'];
	$output = '<ul class="app-ranking">';
	echo $output;
	fputs( $fp, $output . "\n" );
	$nameMaxLength = 18;
	$count = 0;
	foreach ($rss->items as $item ) {
		$appUrl = $item[ 'id' ];
		$dryRead = file_get_contents( $appUrl );
		preg_match("/<h1>(.*?)<\/h1>/i", $dryRead, $match);
		$name = $match[1];
		preg_match('/<li class="genre">(.*?)<\/li>/i', $dryRead, $match);
		$genre = strip_tags( $match[1] );
		preg_match('/<li class="release-date">(.*?)<\/li>/i', $dryRead, $match);
		$release = strip_tags( $match[1] );

		$url   = $item[ 'link' ];
		$url  .= "&partnerId=30";
		$iconArray  = split( "http:", $item[ 'im' ][ 'image' ] );
		$icon = "http:" . $iconArray[ 1 ];
		$price = mb_convert_encoding( $item[ 'im' ][ 'price' ], 'UTF8', 'AUTO' );
		$link_enclosure = $item['link_enclosure'];
		if ( $linkshareId != "" ) {
			$link = $linkshareBase . urlencode( urlencode( $url ) );
		} else {
			$link = $url;
		}

		$count++;
		if ( mb_strlen( $name, 'UTF8' ) > $nameMaxLength ) {
			$trim = ' ...';
		} else {
			$trim = '';
		}
		$output = '<li><span class="rank">' . $count . '</span>';
		$output .= '<a href="' . $link . '" target="_blank">';
		$output .= '<img src="' . $icon . '" class="appicon"></a>';
		$output .= '<a href="' . $link . '" target="_blank">';
		$output .= '<span class="title">' . mb_substr( $name, 0, $nameMaxLength, 'UTF8' ) . $trim . '</span></a><br />';
		$output .= $genre . '<br />';
		$output .= '<span class="price">' . __("Price:", 'appreview') . $price . '</span><br />';
		$output .= $release;
		$output .= '<a href="#" style=\'float: right; margin: auto 0; margin-right: 30px;\' onclick="writingReview(\'' . $icon . '\',\'' . $link . '\',\'' . htmlspecialchars($name) . '\',\'' . $price . '\',\'' . $genre . '\',\'' . $release . '\');">' . __("Review now", 'appreview') . '</a></li>';
		echo $output;
		fputs( $fp, $output . "\n" );
	}
	$output = '</ul>';
	fputs( $fp, $output . "\n" );
	fclose( $fp );
?>
