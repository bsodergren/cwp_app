<?php

use Nette\Utils\FileSystem;

class JobEditor
{
    public $job;
    public $pdf_file;
    public $jobNumber;
    public $job_id;
    private $exp;
    private $mediaLoc;

    public function __construct($explorer, $job_array)
    {
        $this->exp = $explorer;
        $this->job = $job_array;
        $this->job_id = $job_array['id'];
        $this->pdf_file = $job_array['pdf_file'];
        $this->jobNumber = $job_array['job_number'];

        logger("Media Job Info", $job_array, "mediaJobData.log");

        $this->mediaLoc = new Location($this->pdf_file, $this->jobNumber);
    }

    public function delete_job()
    {
        $this->delete_form_data();
        $this->deleteFromDatabase('media_job');

        $job_dir = $this->mediaLoc->getDirectory();

        $pdf_file = $this->mediaLoc->getFilename('pdf');
        $pdf_tmp_file = $pdf_file . '.~qpdf-orig';

        if (file_exists($pdf_file)) {
            FileSystem::delete($pdf_file);
        }

        if (file_exists($pdf_tmp_file)) {
            FileSystem::delete($pdf_tmp_file);
        }

        if (is_dir($job_dir)) {
            FileSystem::delete($job_dir);
        }
    }

    public function delete_form_data()
    {
        $this->deleteFromDatabase('form_data');
        $this->deleteFromDatabase('media_forms');
    }

    private function deleteFromDatabase($table)
    {
        $count = $this->exp->table($table)->where('job_id', $this->job_id)->delete();
    }

    public function delete_xlsx()
    {

        $data = array('xlsx_exists' => '');
        $this->exp->table("media_job")->where('job_id', $this->job_id)->update($data);


        if ($this->job['xlsx_exists']) {
            $xlsx_directory = $this->mediaLoc->getDirectory('xlsx');

            if (is_dir($xlsx_directory)) {
                FileSystem::delete($xlsx_directory);
            }
        }
    }

    public function delete_zip()
    {
        $data = array('zip_exists' => '');
        $this->exp->table("media_job")->where('job_id', $this->job_id)->update($data);

        if ($this->job['zip_exists']) {
            $zip_directory = $this->mediaLoc->getDirectory('zip');

            if (is_dir($zip_directory)) {
                FileSystem::delete($zip_directory);
            }
        }
    }


    public function update_job_number($job_number)
    {

        $data = array('job_number' => $job_number);
        $this->mediaLoc->exp->table("media_job")->where('job_id', $this->job_id)->update($data);
    }
}
