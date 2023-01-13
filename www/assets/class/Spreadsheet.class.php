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
	$media = new Media();
	foreach ($xlsx_array as $form_number => $data)
    {
		$spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $s_idx=0;
		
        $form_configuration=$media->get_form_configuration($data);
		
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
		HTMLDisplay::output("Writing " . $new_xlsx_file,"<br>" );
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


function sheetCommon(&$sheet_obj)
{
    global $connection;
    global $explorer;

	$styles=array(
		"A6" => array("Job Number",true,22),
		"A7" => array("Market",true,22),
		"A8" => array("Magazine",true,22),
		"A9" => array("Destination",true,22),
		"A10" => array("Packaging",true,22),
		"B6" => array("",true,32,1),
		"B7" => array("",0,20,1),
		"B8" => array("",0,20,1),
		"B9" => array("",0,20,1),
		"B10" => array("",true,24,1),

		"D6" => array("",true,32),
		"D7" => array("",false,20,1),

		"D8" => array("",true,20,1),

		"A13" => array("",0,14),
		"A14" => array("",0,14),
		"A15" => array("",0,14),
		"A16" => array("",0,14),
		"A17" => array("",0,14),
		"A18" => array("",0,14),
		"A19" => array("",0,14),
		"A20" => array("",0,14),
		"A21" => array("",0,14),
		"A22" => array("",0,14),
		
		"A24" => array("",true,48,1,1),
		"A25" => array("",true,48,1,1),
		"A26" => array("",true,48,1,1),
		
		"B13" => array("",true,14,1),
		"B14" => array("",true,14,1),
		"B15" => array("",true,14,1),
		"B16" => array("",true,14,1),
		"B17" => array("",true,14,1),
		"B18" => array("",true,14,1),
		"B19" => array("",true,14,1),
		"B20" => array("",true,14,1),
		"B21" => array("",true,14,1),
		"B22" => array("",true,14,1),
				
		
	);
	
	$sheet_obj->mergeCells('B6:C6');
    $sheet_obj->mergeCells('B7:C7');
    $sheet_obj->mergeCells('B8:C8');
    $sheet_obj->mergeCells('B9:C9');
	$sheet_obj->mergeCells('B10:C10');
	
	$sheet_obj->mergeCells('A24:D24');
	$sheet_obj->mergeCells('A25:D25');
	$sheet_obj->mergeCells('A26:D26');
	/*
		
	foreach($styles as $col => $val )
	{
		if(isset($val[0])) {
			$sheet_obj->setCellValue($col,$val[0]);	
		}
		
		$sheet_obj->getStyle($col)->getFont()->setBold($val[1]);
		$sheet_obj->getStyle($col)->getFont()->setSize($val[2]);
		
		if(isset($val[3]))
		{
			$sheet_obj->getStyle($col)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
		}
		
		if(isset($val[4]))
		{
			$sheet_obj->getStyle($col)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
		}
	}
	
	*/
		 $sql = "SELECT ecol ||  erow as location, text,bold,font_size,h_align,v_align FROM flag_style WHERE erow IS NOT NULL;";

                $result = $connection->fetchAll($sql);
    	foreach($result as $k => $val )
        {
            
             $col = $val['location'];
             $text = $val['text'];
             $bold = $val['bold'];
             $font_size = $val['font_size'];
             $h_align = $val['h_align'];
             $v_align = $val['v_align'];

           
           if(isset($text)) {
                $sheet_obj->setCellValue($col,$text);	
            }
            
            $sheet_obj->getStyle($col)->getFont()->setBold($bold);
            $sheet_obj->getStyle($col)->getFont()->setSize($font_size);
            
            if(isset($h_align))
            {
                $sheet_obj->getStyle($col)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
            
            if(isset($v_align))
            {
                $sheet_obj->getStyle($col)->getAlignment()->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            }
        }
		
	#$sheet_obj->getStyle('A24')->getAlignment()->setShrinkToFit(true);
	$sheet_obj->getStyle('B7')->getAlignment()->setShrinkToFit(true);


//	$sheet_obj->getStyle("B6:B10")->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);


}




function cellBorder(&$sheet_obj,$cell,$border="outline")
{
		$styleArray = [
		'borders' => [
			$border => [
				'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
				'color' => ['argb' => '00000000'],
			],
		],
	];
	
	$sheet_obj->getStyle($cell)->applyFromArray($styleArray);

}
	

function addSheetData(&$sheet_obj,$cell_value,$cell_text,$text_col,$val_col)
{
	

	$sheet_obj->setCellValue($text_col, $cell_text);
	$sheet_obj->setCellValue($val_col, $cell_value);
	cellBorder($sheet_obj,$text_col);
	cellBorder($sheet_obj,$val_col);

}


function setRowHeights(&$sheet_obj)
{
				
	$row_height=array(14.5,14.5,14.5,14.5,14.5,62,30,30,30,30,10,15,20,20,20,20,20,20,20,20,20,20,20,50,50,50,10,10,10,10,10);
	$hn=count($row_height);
	for ($i=0;$i<$hn;$i++)
	{
		$sheet_obj->getRowDimension($i+1)->setRowHeight($row_height[$i]);
	}	

}

function setColWidths(&$sheet_obj,$style="Load Flag")
{
	global $connection;
    global $explorer;
	$result = $connection->fetchAll("SELECT ecol,width FROM flag_style WHERE erow IS NULL and style_name = '".$style."' ORDER BY ecol ASC");

	
	foreach($result as $k => $v)
	{
		$sheet_obj->getColumnDimension($v['ecol'])->setWidth($v['width']);
	}
}    



    
function getFormTotals($data)
{
    $total_count=0;
    $pcs_count  = 0;
    
    foreach($data as $key => $array)
    {
        $pcs_count = toint($array['count']);

        $total_count = $total_count + $pcs_count;
        
    }   

    return $total_count;
    
}



function calculateBox( $form_details, $form_configuration)
{
    global $connection;
    global $explorer;
    $face_trim = $form_details["face_trim"];
  
    $pcs=str_replace(',', '', trim($form_details['count']));
    $config = $form_configuration['configuration'];
    $paper_wieght = $form_configuration['paper_wieght'];
    $carton_size = $form_configuration['carton_size'];
    
    $delivery =  strtolower($form_details['former']);

    $paper_size = $form_configuration['paper_size'];
    $config = str_replace("pg", "", $config);

    $res = $explorer->table("paper_type")->select("id")->where("paper_wieght = ?  AND paper_size = ?  AND pages = ?", $paper_wieght, $paper_size, $config)->fetch();
    $res = $explorer->table("paper_count")->where('paper_id',$res['id'])->fetch();
    
    foreach ($res as $var => $value) {
        $$var=$value;
    }

	
    if ($pcs <= $max_carton && $face_trim != 1) {
		logger("max_carton pcs",$max_carton);
        $package = "carton";
    } elseif (($pcs > $max_carton || $face_trim == 1)  && $pcs <= $max_half_skid ) {
        $package = "half";
    } else {
        $package = "full";
    }

    if ($delivery == "back") {
        if ($pcs <= $max_half_skid) {
            $package = "half";
        } else {
            $package = "full";
        }
    }    
    
    $lift_size = $delivery."_lift";

    if ($package == "carton") {        

        // lifts per carton
        $lifts_per_layer = $pcs_carton/ $$lift_size;

        $full_boxes = floor($pcs / $pcs_carton);
        
        $lifts_last_layer =  $pcs - ($pcs_carton * $full_boxes);

        $package = $carton_size . " ".$package ."s";

        $layers_last_box=$pcs_carton;
        
    } else {
        $lifts_per_layer = $package."_skid_lifts_layer";
		
        $layers_per_skid = $delivery."_".$package."_skid_layers";
        // number of lifts in full count.

        $number_of_lifts = ceil($pcs / $$lift_size);

        $lifts_in_box= $$lifts_per_layer * $$layers_per_skid;

        $full_boxes=floor($number_of_lifts / $lifts_in_box);

        $lifts_last_box = $number_of_lifts - ($full_boxes * $lifts_in_box);

        $layers_last_box= floor($lifts_last_box / $$lifts_per_layer);

        $lifts_last_layer = ceil($lifts_last_box - ($layers_last_box * $$lifts_per_layer));

        $lifts_per_layer=$$lifts_per_layer;
    }
    
    $result = array(
    "packaging" => $package,
    "full_boxes" => $full_boxes,
    "layers_last_box" => $layers_last_box,
    "lifts_last_layer" => $lifts_last_layer,
    "lift_size" => $$lift_size,
    "lifts_per_layer" => $lifts_per_layer
);

if(isset($$layers_per_skid)) { $result["layers_per_skid"] = $$layers_per_skid; }
    
    #return [ $package,$full_boxes,$layers_last_box,$lifts_last_layer,$$lift_size,$lifts_per_layer];
    return $result;
}
