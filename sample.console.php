<?php
require_once 'lib/Class.zip.php';

$z = new zipfiles;
/*
case use to local backup define files or folder manually 
this sample compress the files and folders and descompress 
below

config new backup and extract folder
verify the folder permissions(www-data|root|local user) 
or empty will be created with right permissions
*/
$z->set('backupfolder', 'backup.console');
$z->set('extractfolder', 'extract.console');

/* add file data.sample.csv and folder samples */
$z->files[] = ['name' => 'data.sample.csv'];
$z->files[] = ['name' => 'sample-files'];

/* compress function and output result if true => success */
$x = $z->compress();
if($x['result']) echo "\n\t\tCompress with sucess!\n\t\t File: {$z->get('filename')}\n" ;

/* decompress function, output result if success */
$x = $z->decompress();
if($x['result']) echo "\t\tUncompressed to folder: '{$z->get('extractfolder')}/'\n\n" ;
