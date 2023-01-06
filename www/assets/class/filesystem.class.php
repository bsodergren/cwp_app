<?php

use Nette\Utils\FileSystem;

class Location extends Media
{
	public $directory;

	public function __construct($pdf_file,$job_number)
	{
		$this->job_number = $job_number;
		$this->pdf_file = $pdf_file;
		
	}

	private function __directory($type='',$create_dir=false)
	{
		$output_filename = "/".$this->__filename();
		$directory = '';
	
		if (strtolower($type) == 'xlsx'){
			$directory=__XLSX_DIRECTORY__ ;	
		}
		if (strtolower($type) == 'pdf'){
			$directory=__PDF_UPLOAD_DIR__ ;	
		}
		if (strtolower($type) == 'zip'){
			$directory=__ZIP_FILE_DIR__ ;
		}
	
		$directory = __FILES_DIR__ . $output_filename . $directory;
	
		$this->directory = FileSystem::normalizePath($directory);
	
		logger("Media ".$type." directory", $directory,'filesystem.log');
		if($create_dir == true) {
			FileSystem::createDir($this->directory);    
		}

		return $this->directory;
	}
	
	
	private function __filename($type='',$form_number='',$create_dir=false)
	{
		
		$file = basename($this->pdf_file,".pdf");
		$filename = $this->job_number.'_'.$file;
		
		if (strtolower($type) == 'xlsx')
		{
			$directory = $this->__directory($type,$create_dir);
			$filename = $directory.'/'.$filename."_FM".$form_number.'.xlsx';
		}

		if (strtolower($type) == 'zip')
		{
			$directory = $this->__directory($type,$create_dir);
			if($form_number != '' ) {
				$filename = $directory.'/'.$filename."_FM".$form_number.'.zip';
			} else {
				$filename = $directory.'/'.$filename.".zip";
			}
		}
		
		$filename = FileSystem::normalizePath($filename);
		return $filename;
	}

	
	public function getFilename($type='',$form_number='',$create_dir='')
	{
		return  $this->__filename($type,$form_number,$create_dir);
	}

	public function getDirectory($type='',$create_dir='')
	{
		return $this->__directory($type,$create_dir);
	}
	
	


}

class log 
{

    public static function append(string $file, string $content, ?int $mode = 0666): void
	{

		FileSystem::createDir(dirname($file));
		if (@file_put_contents($file, $content,FILE_APPEND) === false) { // @ is escalated to exception
			throw new Nette\IOException(sprintf(
				"Unable to write file '%s'. %s",
				FileSystem::normalizePath($file),
				Helpers::getLastError()
			));
		}

		if ($mode !== null && !@chmod($file, $mode)) { // @ is escalated to exception
			throw new Nette\IOException(sprintf(
				"Unable to chmod file '%s' to mode %s. %s",
				FileSystem::normalizePath($file),
				decoct($mode),
				Helpers::getLastError()
			));
		}
	}
}
