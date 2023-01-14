<?php
require_once(".config.inc.php");
define('TITLE', "Test page");

include __LAYOUT_HEADER__;
$form_number = 1;
$job_id = 7;

$job  = $connection->fetch('SELECT * FROM media_job WHERE job_id = ?', $job_id);
$media = new Media($job);



$media->delete_form($form_number);
$pdfObj = new PDFImport($media->pdf_fullname, $media->job_id,$form_number);
dump( $pdfObj->form[$form_number]);

$media->add_form_data($form_number, $pdfObj->form[$form_number]);

include __LAYOUT_FOOTER__;
