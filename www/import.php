<?php

use Nette\Utils\FileSystem;

require_once(".config.inc.php");
define('TITLE', "Media Import");

include __LAYOUT_HEADER__;
?>
<main role="main" class="container">
<?

$errors = []; // Store errors here

$fileExtensionsAllowed = ['pdf']; // These will be the only file extensions allowed 
if(isset($_FILES['the_file']) ) {
    $fileName = $_FILES['the_file']['name'];
    $fileSize = $_FILES['the_file']['size'];
    $fileTmpName  = $_FILES['the_file']['tmp_name'];
    $fileType = $_FILES['the_file']['type'];
    $f=explode('.',$fileName);
    $f=end($f);    
    $fileExtension = strtolower($f);

    $pdf_file = __PDF_UPLOAD_DIR__ .'/'. basename($fileName); 

}

if (isset($_POST['submit'])) {

  if (! in_array($fileExtension,$fileExtensionsAllowed)) {
    $errors[] = "This file extension is not allowed. Please upload a JPEG or PNG file";
  }

  if ($fileSize > 400000000) {
    $errors[] = "File exceeds maximum size (4MB)";
  }

  if (empty($errors))
  {
    if(file_exists($pdf_file))
    {
      FileSystem::delete($pdf_file);
    }

    if(!file_exists($pdf_file))
    {
        $didUpload = move_uploaded_file($fileTmpName, $pdf_file);
        

        if ($didUpload) {


            $descriptorspec = array(
                0 => array("pipe", "r"),  // stdin is a pipe that the child will read from
                1 => array("pipe", "w"),  // stdout is a pipe that the child will write to
              //  2 => array("file", "error-output.txt", "a") // stderr is a file to write to
             );
             
             $cmd = ''.__PROJECT_ROOT__.'/../bin/qpdf "'.$pdf_file.'" --pages . 1-z -- --replace-input ';

             $process = proc_open($cmd,$descriptorspec, $pipes); 
          
            
            
            $output_text = "The file " . basename($fileName) . " has been uploaded <br>";
            $output_text .= "Job number " . $_POST['job_number'] . "<br>";

        } else {
          $output_text = "An error occurred. Please contact the administrator.";
        }
    } else {
        $output_text = "File already was uploaded<br>\n";
    }
    
    $val = $explorer->table("media_job")->where('pdf_file',$pdf_file)->select('job_id');
    foreach ($val as $u) {
      $job_id = $u->job_id;
    }
    $job_id = '';
    if($job_id == '') {
        $output_text .= "Importing new media job<br>".$_POST['job_number']." \n";
        add_new_media_drop($pdf_file,$_POST['job_number']);

    }
    $replace_array = array("PDFFILE"=> $fileName,
        "STATUS" => $output_text,
        "JOBNUMBER"=> $_POST['job_number'],
        "HOMEURL"=> " Click on <a href=\"".__URL_PATH__."/index.php\">Home</a> to Continue <br>",
        //"JAVAREFRESH" => JavaRefresh(__URL_PATH__."/home.php",5000)
      );
    
     echo process_template('import_pdf_finish',$replace_array);
    
    
  } else {
    foreach ($errors as $error)
    {
      echo $error . "These are the errors" . "\n";
    }
  }

} else {

    echo process_template('import_pdf_form');  
    
}
?>

</main>

<? include __LAYOUT_FOOTER__; ?>