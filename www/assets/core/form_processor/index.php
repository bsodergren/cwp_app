<?php
use Nette\Utils\FileSystem;

$job_id = $_REQUEST['job_id'];
$job  = $connection->fetch('SELECT * FROM media_job WHERE job_id = ?', $job_id);
$media = new Media($job);

if(key_exists('update_job',$_REQUEST))
{

    $media->delete_xlsx();
    $media->delete_zip();

    $job_number = $_REQUEST['job_number'];
    $mediaLoc = new MediaFileSystem($media->pdf_file, $job_number );
    $mediaLoc->getDirectory();

    filesystem::rename($media->base_dir, $mediaLoc->directory);

    $media->update_job_number($job_number);

    echo JavaRefresh("/index.php",0);
/*

    $this->mediaLoc = new MediaFileSystem($this->pdf_file, $this->job_number);
    $this->mediaLoc->getDirectory()
*/

exit;

}

foreach ($_REQUEST as $key => $value) {

    switch ($key) {
        case  "process":
            define('REFRESH_URL', '/form.php?job_id=' . $job_id);
            break;
        case  "create_xlsx":
            define('REFRESH_TIMEOUT', 3);

            include __LAYOUT_HEADER__;

            $xlsx_array = build_xlsx_array($_REQUEST['job_id']);
            $keyidx = array_key_first($xlsx_array);

            $job_number = $xlsx_array[$keyidx]["job_number"];
            $pdf_file = $xlsx_array[$keyidx]["pdf_file"];

            write_xlsx_workbook($xlsx_array, $job_number, $pdf_file);
            ob_flush();

            break;
        case  "create_zip":
            $xlsx_dir = $media->xlsx_directory;
            $zip_file =  $media->zip_file;
            new zip_Workbooks($xlsx_dir, $job_id, $zip_file);
            break;

        case  "refresh_import":
            define('REFRESH_TIMEOUT', 3);

            $media->delete_xlsx();
            $media->delete_zip();
            $media->delete_form_data();
            include __LAYOUT_HEADER__;

            new MediaImport($media->pdf_fullname, $media->job_number);
            break;
        case  "delete_zip":
            $media->delete_zip();
            break;
        case  "delete_xlsx":
            $media->delete_xlsx();
            $media->delete_zip();
            break;
        case  "delete_job":
            define('REFRESH_URL', __URL_PATH__ . "/delete_job.php?job_id=" . $job_id);
            break;

        
    }
}

if (!defined("REFRESH_URL")) {
    define('REFRESH_URL', '/index.php');
}
