<?php


function get_master_xlsx_filename($pdf_file,$job_number)
{
    $filename = get_filename($pdf_file,$job_number);
    $xlsx_directory = get_xlsx_directory($pdf_file,$job_number);
    return $xlsx_directory."master_".$filename.'.xlsx';
    
}



function masterListHeader(&$sheet_obj){
	$index=1;
	$sheet_idx = $sheet_obj->getSheet(0);
	$sheet_idx->setCellValue('A'.$index,"Form Number");
		
	$sheet_idx->setCellValue('C'.$index,'market');
	$sheet_idx->setCellValue('D'.$index,'pub');
	$sheet_idx->setCellValue('E'.$index,'ship');
	$sheet_idx->setCellValue('F'.$index,'count');
	
	$sheet_idx->setCellValue('H'.$index,'configuration');
	$sheet_idx->setCellValue('I'.$index,'paper_wieght');
	$sheet_idx->setCellValue('J'.$index,'packaging');
	$sheet_idx->setCellValue('K'.$index,'Full,layers,lifts');
	
	$letters = range('A', 'M');
	foreach($letters as $col)
	{
		$sheet_idx->getStyle($col)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		//$sheet_idx->getColumnDimension($col)->setWidth(90);
		$sheet_idx->getColumnDimension($col)->setAutoSize(true);
	}
}

function createMasterList($sheet_obj,$index,$fnumber,$fletter,$fdetails,$fconfig,$box_details)
{
	$bindery_destination ="";
	$sheet_idx = $sheet_obj->getSheet(0);
	
	
	if($index % 2 == 0)
	{
	$sheet_idx->getStyle('A'.$index.':M'.$index)->getFill()
    ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
    ->getStartColor()->setARGB('00C1FFF3');
	}
	
	$sheet_idx->setCellValue('A'.$index,$fnumber.$fletter . " - ".$fdetails['former']);
		$sheet_idx->getStyle('F'.$index)->getNumberFormat()->setFormatCode('#,##0' );


	$sheet_idx->setCellValue('C'.$index,$fdetails['market']);
	$sheet_idx->setCellValue('D'.$index,$fdetails['pub']);
	$sheet_idx->setCellValue('E'.$index,$fdetails['ship']);
	$sheet_idx->setCellValue('F'.$index,$fdetails['count']);
	
	
	$sheet_idx->setCellValue('H'.$index,$fconfig['configuration'] .$bindery_destination);
	$sheet_idx->setCellValue('I'.$index,$fconfig['paper_wieght']);
	
	$sheet_idx->setCellValue('J'.$index,$box_details['packaging']);
	$sheet_idx->setCellValue('K'.$index,$box_details['full_boxes'].",".$box_details['layers_last_box'].",".$box_details['lifts_last_layer']);

	//$sheet_idx->setCellValue('P'.$index,var_export($box_details,1));
}

?>