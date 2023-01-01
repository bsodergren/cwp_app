<?php

use Nette\Utils\FileSystem;

require_once '.config.inc.php';
define('TITLE', 'Media Import');
$template = new Template();

require_once __LAYOUT_HEADER__;

$errors = [];
// Store errors here
$fileExtensionsAllowed = ['pdf'];
// These will be the only file extensions allowed
if (isset($_FILES['the_file'])) {
  $fileName      = $_FILES['the_file']['name'];
  $fileSize      = $_FILES['the_file']['size'];
  $fileTmpName   = $_FILES['the_file']['tmp_name'];
  $fileType      = $_FILES['the_file']['type'];
  $f             = explode('.', $fileName);
  $f             = end($f);
  $fileExtension = strtolower($f);
  $media_closing = '/' . basename($fileName, '.pdf');
  $pdf_directory = get_directory($fileName, $_POST['job_number'], 'pdf', true);
  $pdf_file      = $pdf_directory . '/' . basename($fileName);
}

if (isset($_POST['submit'])) {
  if (!in_array($fileExtension, $fileExtensionsAllowed)) {
    $errors[] = 'This file extension is not allowed. Please upload a JPEG or PNG file';
  }

  if ($fileSize > 400000000) {
    $errors[] = 'File exceeds maximum size (4MB)';
  }

  if (empty($errors)) {
    if (file_exists($pdf_file)) {
      FileSystem::delete($pdf_file);
    }

    if (!file_exists($pdf_file)) {
      $didUpload = move_uploaded_file($fileTmpName, $pdf_file);


      if ($didUpload) {
        $descriptorspec = [
          0 => ['pipe', 'r',],
          // stdin is a pipe that the child will read from
          1 => ['pipe', 'w',],
          // stdout is a pipe that the child will write to
          // 2 => array("file", "error-output.txt", "a") // stderr is a file to write to
        ];

        $qdf_cmd = FileSystem::normalizePath('"' . __ROOT_BIN_DIR__ . '/qpdf" ');
        $pdf_file = FileSystem::normalizePath($pdf_file);
        $cmd = $qdf_cmd . '"' . $pdf_file . '" ' . ' --pages . 1-z -- --replace-input ';

        logger('QDPF Command', $cmd);
        $process = proc_open($cmd, $descriptorspec, $pipes);

        $output_text  = 'The file ' . basename($fileName) . ' has been uploaded <br>';
        $output_text .= 'Job number ' . $_POST['job_number'] . '<br>';
      } else {
        $output_text = 'An error occurred. Please contact the administrator.';
      } //end if
    } else {
      $output_text = "File already was uploaded<br>\n";
    } //end if

    $val = $explorer->table('media_job')->where('pdf_file', $pdf_file)->select('job_id');
    foreach ($val as $u) {
      $job_id = $u->job_id;
    }

    $job_id = '';
  if ($job_id == '') {
    logger('pdf_file', $pdf_file);

    $return = add_new_media_drop($pdf_file, $_POST['job_number']);
    
    if($return < 1) {
      $output_text = "<span class='p-3 text-danger'>File failed to process</span> <br>";
      $output_text .= "<span class='p-3 text-danger'>Will have to run Refresh Import </span><br>";
      $replace_array['HOMEURL'] = ' Click on <a href="' . __URL_PATH__ . '/index.php">Home</a> to Continue <br>';
      } else {
        $replace_array['JAVAREFRESH'] = JavaRefresh(__URL_PATH__."/home.php",5000);
      }
    }

    $replace_array['PDFFILE']   = $fileName;
    $replace_array['STATUS']    = $output_text;
    $replace_array['JOBNUMBER'] = $_POST['job_number'];
  

    $template->template('import_pdf_finish', $replace_array);
  } else {
    foreach ($errors as $error) {
      echo $error . 'These are the errors' . "\n";
    }
  } //end if
} else {
  $template->template('import_pdf_form');
}

$template_html['FORM_BODY']  = $template->return();

$template->template('import/main',$template_html);

$template->render();//end if
require_once __LAYOUT_FOOTER__;
