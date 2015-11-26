<?php
include 'MysqlClass.php';

// Chave para acesso à API do youtube
define( 'YOUTUBE_API_KEY', 'AIzaSyCjhhWs4SVYKWEbrUm2IcWj9_8YMWud_xI', true);

$mysql = new MysqlClass('phocus-server.local','root','ph0cu5x3r3t@','random_fail');

if ( ! empty( $_GET['page'] ) ) {
	$page = $_GET['page'];
}

if ( ! empty( $_GET['id'] ) ) {
	$id = $_GET['id'];
}

// pego o proximo random
if ( $page == 'get_video' ) {
	$data = $mysql->query('SELECT * FROM videos ORDER BY rand() limit 1');
	if ( ! empty( $data ) ) {
		echo json_encode( $data[0] );
	}
}

// salvo um like
if ( $page == 'like' && ! empty( $id ) ) {
	$mysql->query('UPDATE videos SET likes = likes + 1 WHERE ID = ' . $mysql->antiInjection($id));
}

// salvo um deslike
if ( $page == 'deslike' && ! empty( $id ) ) {
	$mysql->query('UPDATE videos SET deslikes = deslikes + 1 WHERE ID = ' . $mysql->antiInjection($id));
}

//salvo
if ( $page == 'save' ) {
	$out = array('error' => true);

	if ( ! empty( $_POST['video'] ) 
		&& preg_match("/youtube/", $_POST['video'] ) ) {
		// pego o id do youtube
		$search     = '/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i';
        $replace    = "$1";
		$video = preg_replace($search,$replace,$_POST['video']);

		// verifico a api para ver se o video existe mesmo
		if ( ! empty( $video ) ) {
			$url = 'https://www.googleapis.com/youtube/v3/videos?part=snippet&id=' . $video . '&key=' . YOUTUBE_API_KEY;
			$results = json_decode(file_get_contents( $url ));
			if ( ! empty( $results->items[0] ) ) {

				// salvo no banco
				$mysql->query('INSERT videos ( youtube_id, created ) VALUES ("' . $mysql->antiInjection($video) . '", NOW())');
				$out = array('error' => false);

			}
		}
	}

	echo json_encode( $out );
}

