<?php

$job_id = $_REQUEST['job_id'];
$job  = $connection->fetch('SELECT * FROM media_job WHERE job_id = ?', $job_id);
$media = new Media($explorer,$job);

if (key_exists("process", $_REQUEST)) {
    define('REFRESH_URL', '/form.php?job_id=' . $job_id);
}

if (key_exists("create_xlsx", $_REQUEST)) {
    include __LAYOUT_HEADER__;

    // Need output here
    $xlsx_array = build_xlsx_array($_REQUEST['job_id']);
    $keyidx = array_key_first($xlsx_array);

    $job_number = $xlsx_array[$keyidx]["job_number"];
    $pdf_file = $xlsx_array[$keyidx]["pdf_file"];

    write_xlsx_workbook($xlsx_array, $job_number, $pdf_file);
    ob_flush();
   // $job['xlsx_exists'] = 1;
    define('REFRESH_TIMEOUT', 15);
}

if (key_exists("delete_xlsx", $_REQUEST)) {
    $media->delete_xlsx();
    $media->delete_zip();
}
if (key_exists("create_zip", $_REQUEST)) {
    $xlsx_dir = $media->xlsx_directory;
    $zip_file =  $media->zip_file;

    zip_Workbooks($xlsx_dir, $job_id, $zip_file);
}
if (key_exists("delete_zip", $_REQUEST)) {
    $media->delete_zip();
}
if (key_exists("delete_job", $_REQUEST)) {
    define('REFRESH_URL', __URL_PATH__ . "/delete_job.php?job_id=" . $job_id);
}

if (key_exists("refresh_import", $_REQUEST)) {

    $media->delete_xlsx();
    $media->delete_zip();
    $media->delete_form_data();
    add_new_media_drop($media->pdf_fullname, $media->job_number);
}

if (!defined("REFRESH_URL")) {
    define('REFRESH_URL', '/index.php');
}
