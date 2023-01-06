<?php
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\ConditionalFormatting\Wizard;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Borders;
use PhpOffice\PhpSpreadsheet\Writer\Pdf\Mpdf;

function write_xlsx_workbook($xlsx_array,$job_number,$pdf_file)
{
	global $explorer;
	
	if (__XLSX_EXTRAS__ == true ) {
		$master_sheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
		masterListHeader($master_sheet);
		
		$listsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

		$list_st=$listsheet->getSheet(0);


		setColWidths($list_st,"master sheet");
	}
	$keyidx=array_key_first($xlsx_array);
    $job_id=$xlsx_array[$keyidx]['job_id'];
	$ms_idx=1;
    $list_idx=1;
	
	foreach ($xlsx_array as $form_number => $data)
    {
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $s_idx=0;
		
        $form_configuration=get_form_configuration($data);
		
        foreach($data as $former => $result_array)
        {
            if ( $former == "Front" || $former == "Back" )
            {

                foreach($result_array as $form_letter => $form_details_array)
                {   
				
				
                    foreach($form_details_array as $key => $form_details)
                    {
						$ms_idx=$ms_idx+1;
						
                        $box = calculateBox($form_details, $form_configuration);
						if ($box['packaging'] == "full" || $box['packaging'] == "half" )
						{
							$tmp_box=$box;
							$full_boxes=$box['full_boxes'];
							$max_boxes = $box['full_boxes'];
							if($box['full_boxes'] >= 1 )
							{
									

								$box["layers_last_box"]=0;
								$box["lifts_last_layer"]=0;
								$box['full_boxes']='';
								$sk=0;
								while($full_boxes > 0 ) {
									$sk++;
									$box['layers_last_box']=$box['layers_per_skid'];
									$box['skid_count']="$sk of ". $max_boxes+1;
									$form_details['count'] = ($box['layers_per_skid'] * $box['lifts_per_layer']) * $box["lift_size"];
									createWorksheet($spreadsheet,$s_idx,$form_number,$form_letter,$form_details,$form_configuration,$box );
									$full_boxes = $full_boxes - 1;
									$s_idx=$s_idx+1;
								
								}
								//$s_idx=$s_idx+1;
								$sk++;
								$box['skid_count']="$sk of ".$max_boxes+1;
								$box["layers_last_box"]=$tmp_box["layers_last_box"];
								$box["lifts_last_layer"]=$tmp_box["lifts_last_layer"];	
								$form_details['count'] = (($box['layers_last_box'] * $box['lifts_per_layer']) + $box["lifts_last_layer"]) * $box["lift_size"];
																
							}
							
						}
						
						
                        createWorksheet($spreadsheet,$s_idx,$form_number,$form_letter,$form_details,$form_configuration,$box );
						
						
						if (__XLSX_EXTRAS__ == true ) {
							addFormTag($listsheet,$list_idx,$form_number,$form_letter,$form_details,$form_configuration,$box );
							
							createMasterList($master_sheet,$ms_idx,$form_number,$form_letter,$form_details,$form_configuration,$box );
						}
						$s_idx=$s_idx+1;
						$list_idx++;

						
                    }
                }
            }
			
        }
		
		
		
        $sheetIndex = $spreadsheet->getIndex($spreadsheet->getSheetByName('Worksheet'));

        $spreadsheet->removeSheetByIndex($sheetIndex);
		
		$spreadsheet->setActiveSheetIndex(0);
        $writer = new Xlsx($spreadsheet);
		$mediaDir = new MediaFileSystem($pdf_file,$job_number);
        $new_xlsx_file=$mediaDir->getfilename('xlsx',$form_number,true);

        $writer->save($new_xlsx_file);
		output("Writing " . $new_xlsx_file );
        $spreadsheet->disconnectWorksheets();
        unset($spreadsheet);
		$ms_idx=$ms_idx+1;

   }
	if (__XLSX_EXTRAS__ == true ) {
		$ms_writer = new Xlsx($master_sheet);
		$new_master_file=get_master_xlsx_filename($pdf_file,$job_number);
		$ms_writer->save($new_master_file);    
		$master_sheet->disconnectWorksheets();
		unset($master_sheet);

	/*
		$ls_writer = new Xlsx($listsheet);
		$new_listsheet_file=get_list_xlsx_filename($pdf_file,$job_number);
		$ls_writer->save($new_listsheet_file);    
		*/
		$listsheet->disconnectWorksheets();
		unset($listsheet);
	}
	

 //   $xlsx_directory=get_xlsx_directory($pdf_file,$job_number,true);

	$explorer->table('media_job')->where('job_id', $job_id)->update(['xlsx_exists' => 1]);
    //myHeader();  

}

function createWorksheet(&$worksheet_obj,$sheet_index,$form_number,$form_letter,$form_details,$form_configuration,$box_details )
{
    $write_array=array();
    global $db;
	$bindery_trim = false;
	
    foreach($box_details as $var => $value)
    {
        $$var = $value;
    }
		
	$pub_value = $form_details["pub"];
	$ship_value = $form_details['ship'];
	
    $delivery =  strtolower($form_details['former']);
    
	if ($delivery  == "back" || $form_details['face_trim'] == 1  )
	{
		if ( $form_details['no_bindery'] != 1 ) {
			$form_details['market'] = __lang_bindery;
			$ship_value = __lang_bindery;
			$bindery_trim = true;
		}
		
	}
	
	
	$worksheet_title=$form_number.$form_letter."_".$delivery;
	
    
	$myWorkSheet = new \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet($worksheet_obj,  $worksheet_title);
	
	
	$worksheet_obj->addSheet($myWorkSheet, $sheet_index);
    $sheet=$worksheet_obj->getSheet($sheet_index);
	
	$sheet->getHeaderFooter()->setOddHeader('&36&B Media Load Flag');
	
	setColWidths($sheet);
	setRowHeights($sheet);
	sheetCommon($sheet);
	
	
    $sheet->setCellValue('B6', $form_details['job_number']);
    $sheet->setCellValue('B7', $form_details['market']);
    $sheet->setCellValue('B8', $pub_value);
    $sheet->setCellValue('B9', $ship_value);
    $sheet->getStyle('B7')->getAlignment()->setShrinkToFit(true);

    $sheet->setCellValue('D6', $form_number . "". $form_letter);
	
	cellBorder($sheet,"A6:C10","allBorders");
	cellBorder($sheet,"D6","outline");
	
	$sheet->setCellValue('D7', $form_configuration['configuration']." ".$form_configuration['paper_wieght'] . "#");

	$count= str_replace(",","",$form_details['count']);

	$labels = array();
	
	$sheet_labels = array(
						'13'=>array($lift_size,"Lift Size"),
						'21'=>array($count,"Total Count")
					);

	
	$sheet->getStyle('B21')->getNumberFormat()->setFormatCode('#,##0' );

	if (($packaging == "small cartons" || $packaging == "large cartons") )// $delivery == "front" )
	{
		$sheet->setCellValue('B10', $packaging);

		$labels['14']="Lifts per Carton";
		$sheet_labels['15']=array($layers_last_box,"Pcs Per Carton");
		
		$sheet_labels['17']=array($full_boxes,"Full Cartons");
		$total_boxes=$full_boxes;
		
		if ($lifts_last_layer > 0 ) {
			
			$sheet_labels['18'] = array($lifts_last_layer,"Count in last Carton");
			$total_boxes=$full_boxes+1;
		} else {
			$sheet_labels['18'] = array("0","Count in last Carton");
		}

		$sheet_labels['19'] = array($total_boxes,"Total Cartons");

	} else 	{
		//Skid packaging: half/full
		$sheet->setCellValue('B10', $packaging. " skid");
		$labels['14']="Lifts per Layer";
		$sheet_labels['15']= array($layers_per_skid,"layers per skid");


		// Lifts per Carton
		// Number of Layers
		if ($full_boxes > 0 )
		{			
			$sheet_labels['17']= array($full_boxes,"Number of full boxes");
		}
		
		if (isset($skid_count))
		{			
			$sheet->setCellValue('D8', $skid_count);
		}
		
		$sheet_labels['18'] = array($layers_last_box,"Number of Full Layers"); 
		if ($lifts_last_layer > 0 ) {
			$sheet_labels['19'] = array($lifts_last_layer,"Lifts last layer");
		}
		
	}
	

	$sheet_labels['14'] = array($lifts_per_layer,$labels['14']);

	foreach( $sheet_labels as $key => $val)
	{
		addSheetData($sheet,$val[0],$val[1],"A".$key,"B".$key);
	}
	
	

	cellBorder($sheet,"A10","bottom");
	cellBorder($sheet,"A10","right");
	cellBorder($sheet,"B10","bottom");
/*
	if ($full_boxes == 0 )
	{
		$sheet->setCellValue('B16', $layers_last_box);
		// lifts last layer
		$sheet->setCellValue('B17', $lifts_last_layer);
	} else {
		$sheet->setCellValue('B15', $layers_last_box);
		$sheet->setCellValue('B16', $full_boxes);

	}

	if ($lifts_last_layer > 0 ) {					
		$sheet->setCellValue('B17', $lifts_last_layer);
	}
	*/
	if ($bindery_trim == true)
	{
		$sheet->setCellValue('A24', __lang_bindery);
		$sheet->setCellValue('A25', __lang_bindery);
		$sheet->setCellValue('A26', __lang_bindery);			
	}
	
}

