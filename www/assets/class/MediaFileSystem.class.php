<?php

use Nette\Utils\FileSystem;

class MediaFileSystem
{
    public $directory;
    use Nette\StaticClass;

    public function __construct($pdf_file, $job_number)
    {
        $this->job_number = $job_number;
        $this->pdf_file = $pdf_file;
    }

    public function getFilename($type = '', $form_number = '', $create_dir = '')
    {
        return $this->__filename($type, $form_number, $create_dir);
    }

    private function __filename($type = '', $form_number = '', $create_dir = false)
    {
        $file = basename($this->pdf_file, ".pdf");
        $filename = $this->job_number . '_' . $file;


        if (strtolower($type) == 'xlsx') {

            $filename = $filename . "_FM" . $form_number . '.xlsx';
        }

        if (strtolower($type) == 'zip') {

            if ($form_number != '') {
                $filename =  $filename . "_FM" . $form_number . '.zip';
            } else {
                $filename =  $filename . ".zip";
            }
       }
        if (strtolower($type) == 'pdf') {
            $filename =  $this->pdf_file;

        }

        if($type != '')
        {
             $directory = $this->__directory($type, $create_dir);
        }


        $filename = $directory . '/' . $filename;
        $filename = FileSystem::normalizePath($filename);
        return $filename;
    }

    private function __directory($type = '', $create_dir = false)
    {
        $output_filename = "/" . $this->__filename();
        $directory = '';

        if (strtolower($type) == 'xlsx') {
            $directory = __XLSX_DIRECTORY__;
        }
        if (strtolower($type) == 'pdf') {
            $directory = __PDF_UPLOAD_DIR__;
        }
        if (strtolower($type) == 'zip') {
            $directory = __ZIP_FILE_DIR__;
        }

        $directory = __FILES_DIR__ . $output_filename . $directory;

        $this->directory = FileSystem::normalizePath($directory);

        if ($create_dir == true) {
            FileSystem::createDir($this->directory);
        }

        return $this->directory;
    }

    public function getDirectory($type = '', $create_dir = '')
    {
        return $this->__directory($type, $create_dir);
    }
}

class log
{

    public static function append(string $file, string $content, ?int $mode = 0666): void
    {

        FileSystem::createDir(dirname($file));
        if (@file_put_contents($file, $content, FILE_APPEND) === false) { // @ is escalated to exception
            throw new Nette\IOException(sprintf("Unable to write file '%s'. %s", FileSystem::normalizePath($file), Helpers::getLastError()));
        }

        if ($mode !== null && !@chmod($file, $mode)) { // @ is escalated to exception
            throw new Nette\IOException(sprintf("Unable to chmod file '%s' to mode %s. %s", FileSystem::normalizePath($file), decoct($mode), Helpers::getLastError()));
        }
    }
}
