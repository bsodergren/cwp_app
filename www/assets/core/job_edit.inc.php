<?php

use Nette\Utils\FileSystem;

function deleteFromDatabase($table,$job_id)
{
    global $explorer;
    $count = $explorer->table($table)
        ->where('job_id', $job_id)
        ->delete();
}



function delete_form_data($job_id)
{
    global $job;
    deleteFromDatabase('form_data', $job_id);
    deleteFromDatabase('media_forms', $job_id);
    
}

function delete_job($job_id)
{
    global $job;
    deleteFromDatabase('form_data', $job_id);
    deleteFromDatabase('media_forms', $job_id);
    deleteFromDatabase('media_job', $job_id);    


    $pdf_file = get_pdf_directory($job['pdf_file'],$job['job_number'])."/".$job['pdf_file'];
    $pdf_tmp_file = $pdf_file.'.~qpdf-orig';
    
    if (file_exists($pdf_file)) {
        FileSystem::delete($pdf_file);
    }

    if (file_exists($pdf_tmp_file)) {
        FileSystem::delete($pdf_tmp_file);
    }

    header( "refresh:2;url=".__URL_HOME__."/index.php");   
}

function delete_zip($job_id)
{
    global $explorer;
    global $job;
    
    $data = Array ('zip_file' => '' );
    $explorer->table("media_job")->where ('job_id',  $job_id)->update($data);
    if (file_exists($job['zip_file'])) {
        FileSystem::delete($job['zip_file']);
    }   
}

function delete_xlsx($job_id)
{
    global $explorer;
    global $job;

     $data = Array ('xlsx_dir' => '' );
     $explorer->table("media_job")->where ('job_id',  $job_id)->update($data);
    if (file_exists($job['xlsx_dir'])) {
        FileSystem::delete($job['xlsx_dir']);
    }
}

function update_job_number($job_id,$job_number)
{
    global $explorer;
    global $job;
    
    $data = Array ('job_number' => $job_number );
    $explorer->table("media_job")->where ('job_id',  $job_id)->update($data);

        $job['job_number']=$job_number;
}


?>