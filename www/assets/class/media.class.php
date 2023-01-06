<?php

/**
 * @property mixed $job_id
 * @property mixed $pdf_file
 * @property mixed $xlsx
 * @property mixed $zip
 * @property mixed $location
 * @property mixed $jobNumber
 */
class Media
{

    public function __construct(array $MediaDBRow = [])
    {
        $this->job_id = $MediaDBRow['id'];
        $this->pdf_file = $MediaDBRow['pdf_file'];
        $this->jobNumber = $MediaDBRow['jobNumber'];
        $this->xlsx = $MediaDBRow['xlsx_exists'];
        $this->zip = $MediaDBRow['zip_exists'];
        $this->location = $MediaDBRow['base_dir'];

    }




}