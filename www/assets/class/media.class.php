<?php

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