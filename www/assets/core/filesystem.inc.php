<?php 
use Nette\Utils\FileSystem;
function get_directory($pdf_file,$job_number, $type='')
{
	$output_filename = get_filename($pdf_file,$job_number);
	if (strtolower($type) == 'xlsx'){
		$directory=__XLSX_DIRECTORY__  .'/'.$output_filename;
	}
	
	if (strtolower($type) == 'zip'){
		$directory=__ZIP_FILE_DIR__  .'/'.$output_filename;
	}
	
    FileSystem::createDir($directory);    
    return $directory;
}


function get_filename($pdf_file,$job_number,$type='',$form_number='')
{
	$file = basename($pdf_file,".pdf");
    $filename = $file."_".$job_number;
	if (strtolower($type) == 'xlsx')
	{
		$directory = get_directory($pdf_file,$job_number,$type);
		$filename = $directory.'/'.$filename."_FM".$form_number.'.xlsx';
	}
	if (strtolower($type) == 'zip')
	{
		
		$directory = get_directory($pdf_file,$job_number,$type);
		if($form_number != '' ) {
			$filename = $directory.'/'.$filename."_FM".$form_number.'.zip';
		} else {
			$filename = $directory.'/'.$filename.".zip";
		}
	}
	
	return $filename;
}

function get_xlsx_directory($pdf_file,$job_number)
{
    return get_directory($pdf_file,$job_number, 'xlsx');
}

function get_xlsx_filename($pdf_file,$job_number,$form_number)
{
	return  get_filename($pdf_file,$job_number,'xlsx',$form_number);
}



function get_zip_filename($pdf_file,$job_number,$form_number='')
{
	return  get_filename($pdf_file,$job_number,'zip',$form_number);
}
