<?php 
use Nette\Utils\FileSystem;

function get_directory($pdf_file,$job_number, $type='',$create_dir=false)
{
	$output_filename = "/".get_filename($pdf_file,$job_number);
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

	$directory= FileSystem::normalizePath($directory);

	if($create_dir == true) {
		
    	FileSystem::createDir($directory);    
	}
    return $directory;
}


function get_filename($pdf_file,$job_number,$type='',$form_number='',$create_dir=false)
{
	$file = basename($pdf_file,".pdf");
    $filename = $job_number.'_'.$file;
	
	if (strtolower($type) == 'xlsx')
	{
		$directory = get_directory($pdf_file,$job_number,$type,$create_dir);
		$filename = $directory.'/'.$filename."_FM".$form_number.'.xlsx';
	}
	if (strtolower($type) == 'zip')
	{
		
		$directory = get_directory($pdf_file,$job_number,$type,$create_dir);
		if($form_number != '' ) {
			$filename = $directory.'/'.$filename."_FM".$form_number.'.zip';
		} else {
			$filename = $directory.'/'.$filename.".zip";
		}
	}
	
	$filename = FileSystem::normalizePath($filename);
	return $filename;
}

function get_xlsx_directory($pdf_file,$job_number,$create_dir=false)
{
    return get_directory($pdf_file,$job_number, 'xlsx',$create_dir);
}

function get_pdf_directory($pdf_file,$job_number,$create_dir=false)
{
    return get_directory($pdf_file,$job_number, 'pdf',$create_dir);
}

function get_xlsx_filename($pdf_file,$job_number,$form_number,$create_dir=false)
{
	return  get_filename($pdf_file,$job_number,'xlsx',$form_number,$create_dir);
}

function get_zip_filename($pdf_file,$job_number,$form_number='',$create_dir=false)
{
	return  get_filename($pdf_file,$job_number,'zip',$form_number,$create_dir);
}

function get_zip_directory($pdf_file,$job_number,$form_number='',$create_dir=false)
{
	return  get_directory($pdf_file,$job_number,'zip',$form_number,$create_dir);
}