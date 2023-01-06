<?php

use Nette\Utils\FileSystem;

/**
 * @property mixed $job_id
 * @property mixed $pdf_file
 * @property mixed $xlsx
 * @property mixed $zip
 * @property mixed $location
 * @property mixed $job_number
 */
class Media
{


    private $exp;
    private $mediaLoc;


    public function __construct($explorer, $MediaDB)
    {
        if (is_object($MediaDB)) {
            $array = get_object_vars($MediaDB);
            unset($MediaDB);
            $MediaDB = $array;
        }

        $this->exp = $explorer;


        $this->job_id = $MediaDB['job_id'];
        $this->pdf_file = $MediaDB['pdf_file'];
        $this->job_number = $MediaDB['job_number'];
        $this->xlsx = $MediaDB['xlsx_exists'];
        $this->zip = $MediaDB['zip_exists'];
        $this->location = $MediaDB['base_dir'];

        $this->mediaLoc = new MediaFileSystem($this->pdf_file, $this->job_number);

        $this->base_dir = $this->mediaLoc->getDirectory();

        $this->pdf_fullname = $this->mediaLoc->getFilename('pdf');
        $this->pdf_tmp_file = $this->pdf_fullname . '.~qpdf-orig';

        $this->xlsx_directory = $this->mediaLoc->getDirectory('xlsx');
        $this->zip_directory = $this->mediaLoc->getDirectory('zip');
        $this->zip_file = $this->mediaLoc->getFilename('zip');
    }

    public static function set_exists($value,$field, $job_id)
    {
        global $explorer;
        if($value == 0 ){
            $value = '';

        }
        $result = $explorer->table('media_job')->where('job_id', $job_id)->update([$field.'_exists' => $value]);
    }

    public static function get_exists( $field, $job_id)
    {
        global $explorer;
        $result = $explorer->table('media_job')->select($field.'_exists')->where('job_id', $job_id);
        $exists = $result->fetch();
        $var_name = $field.'_exists';
        return toint($exists->$var_name);

    }


    public function number_of_forms()
    {

        return $this->exp->table("media_forms")->where("job_id", $this->job_id)->count('*');
    }











































    public function delete_job()
    {
        $this->delete_form_data();
        $this->deleteFromDatabase('media_job');

        if (file_exists($this->pdf_file)) {
            FileSystem::delete($this->pdf_file);
        }

        if (file_exists($this->pdf_tmp_file)) {
            FileSystem::delete($this->pdf_tmp_file);
        }

     //   if (is_dir($this->base_dir)) {
     //       FileSystem::delete($this->base_dir);
     //   }
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

     
        if ($this->xlsx == true) {
            if (is_dir($this->xlsx_directory)) {
                FileSystem::delete($this->xlsx_directory);
            }
        }
        Media::set_exists(0,"xlsx",$this->job_id);
        $this->xlsx = false;
    }

    public function delete_zip()
    {
   
        if ($this->zip == true) {
            if (is_dir($this->zip_directory)) {
                FileSystem::delete($this->zip_directory);
            }
        }
        Media::set_exists(0,"zip",$this->job_id);
        $this->zip = false;
    }


    public function update_job_number($job_number)
    {
        $data = array('job_number' => $job_number);
        $this->mediaLoc->exp->table("media_job")->where('job_id', $this->job_id)->update($data);
    }
}
