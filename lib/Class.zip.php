<?php
if( !defined('DS') ) 
	define('DS', DIRECTORY_SEPARATOR);

Class zipfiles extends ZipArchive {

	public $zip;

	public $files = [];

	protected $info	= [
				'filename' => '',
				'backupfolder' => 'backup',
				'extractfolder' => 'extract',
				'last_file_name' => 'last_file_generated.txt'
			];

	function __construct() {

		/* verify if has support to php libraryand exist files and folders */
		$this->verify_support();
		/* load library zip */
		$this->zip = new ZipArchive;
	}
	
	public function get($i) {
		return $this->info[$i];
	}

	public function set($i, $v) {
		return $this->info[$i] = $v;
	}

	public function verify_support() {

		/* verify is has support the lib */
		if(!class_exists('ZipArchive'))
			die(json_encode(["error" => " verify the PHP zip support"]));
	}

	public function put_last_file() {
		/* verify is backup folder exist */
		if( !file_exists( $this->info['backupfolder'] ) ) 
			mkdir($this->info['backupfolder'], 0777);

		/* verify if file to set the last file generated exist */
		$last_file = $this->info['backupfolder']. DS . $this->info['last_file_name'];
		if(!file_exists($last_file)) 
			touch($last_file);
		/* insert the last tar created in file txt */
		$this->info['filename'] = microtime(true) . '-data.zip';
		$last_file = $this->info['backupfolder'] . DS .  $this->info['last_file_name'];
		file_put_contents("$last_file", $this->info['filename']);
	}
	
	public function get_last_file() {

		/* load last file generated from file txt */
		$last_file = @file_get_contents( $this->info['backupfolder'] . DS . $this->info['last_file_name'] );
		if(empty($last_file)) die(json_encode(["error" => " Backup dont exist! "]));
		$this->info['filename'] = $last_file;
	}
	public function compress() {
		
		/* generate new file to backup */
		$this->put_last_file();

		$file = $this->info['backupfolder'] . DS . $this->info['filename'];
		$this->zip->open($file, ZipArchive::CREATE);

		foreach ( $this->files as $f ) {
			if(file_exists($f['name'])) 
				if ( is_file($f["name"]) ) {
					$rename = ((isset($f['rename']) && !empty($f['rename'])) ? $f['rename'] : $f['name']);
					$this->zip->addFile($f['name'], $rename);
				} else {
					$opt = ['add_path' => "{$f['name']}/", 'remove_all_path' => TRUE];
					$this->zip->addGlob("{$f['name']}/*", GLOB_BRACE, $opt);
				}
		}

		$this->zip->addFromString('readme.txt', ' this file was generated to backup... ');

		$this->zip->close();
		return ['result' => true ];
	}

	public function decompress() {

		$this->get_last_file();

		$file = $this->info['backupfolder'] . DS . $this->info['filename'];
		$of = $this->zip->open($file);

		if ($of) {
			$this->zip->extractTo("{$this->info['extractfolder']}/");
			$this->zip->close();
		} 
		return ['result' => $of ];
	}

}

