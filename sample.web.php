<?php
require_once 'lib/Class.zip.php';

$z = new zipfiles;

/* case use to web upload */

$files = $_FILES;

foreach($files as $k => $f) {
	$name = $f['name'];
	$tmp_name = $f['tmp_name'];

	if(is_array($name) &&
		 is_array($tmp_name)) {
		foreach($tmp_name as $k => $n) 
			$z->files[] = [ 
					'name' => $n,
					'rename' => $name[$k]
				];
	} else {
		$z->files[] = [
				'name' => $tmp_name,
				'rename' => $name 
				];
	}
		
}
/* compress function */
$x = $z->compress();
if($x['result']) echo "Compressed : " . $z->get('filename') . "\n";

/* decompress function */
$x = $z->decompress();
if($x['result']) echo "Uncompressed to folder: " . $z->get('extractfolder');

