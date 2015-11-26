<?php
include 'MysqlClass.php';

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

if ( $page == 'save' ) {
	$out = array('error' => true);

	if ( ! empty( $_POST['video'] ) 
		&& preg_match("/youtube/", $_POST['video'] ) ) {
		$search     = '/\s*[a-zA-Z\/\/:\.]*youtube.com\/watch\?v=([a-zA-Z0-9\-_]+)([a-zA-Z0-9\/\*\-\_\?\&\;\%\=\.]*)/i';
        $replace    = "$1";
		$video = preg_replace($search,$replace,$_POST['video']);

		if ( ! empty( $video ) ) {
			$mysql->query('INSERT videos ( youtube_id, created ) VALUES ("' . $mysql->antiInjection($video) . '", NOW())');
			$out = array('error' => false);
		}
	}

	echo json_encode( $out );
}

