<?php

function get_list_xlsx_filename($pdf_file,$job_number)
{
    $filename = get_filename($pdf_file,$job_number);
    $xlsx_directory = get_xlsx_directory($pdf_file,$job_number);
    return $xlsx_directory."quantity_cards_".$filename.'.xlsx';
    
}

global $cell_row;

$cell_row=2;



function addFormTag(&$sheet_obj,$index,$fnumber,$fletter,$fdetails,$fconfig,$box_details)
{
	global $cell_row;
	
	$sheet_idx = $sheet_obj->getSheet(0);
	
	$page_details=array();
	$pos=array();
	
	$cols=array(
		"left" => array("B","C"),
		"right" => array("E","F")
	);
	
	
	
	
	$pos["Form"] = $fnumber.$fletter;
	if ($box_details['packaging'] == "half" ||
		$box_details['packaging'] == "full" ) 
	{
		$packaging = $box_details['packaging']." skid";
		$skid=true;
	} else {
		$packaging = $box_details['packaging'];
		$skid=false;
	}
	$pos["Packaging"] = Ucwords($packaging);
	$pos["Lift Size"] = $box_details['lift_size'];

	if ($skid == true )
	{
		$pos["Lifts per Layer"] = $box_details['lifts_per_layer'];
		$pos["_2"] = "";
		$pos["Layers"] = $box_details['layers_last_box'];
		$pos["Lifts"] = $box_details['lifts_last_layer'];
	} else {
		$pos["__2"] = "";
		$pos["Full Cartons"] = $box_details['full_boxes'];
		$pos["Last Carton"] = $box_details['lifts_last_layer'];
		$pos["Total Cartons"] = $box_details['full_boxes']+1;
	}
	
	$pos["__3"] = "";
	$pos["Total"] = $fdetails['count'];
	
	
	if($index % 4 == 0 || $index % 2 == 0) {
		$pos_side="right";
	} else {
		$pos_side="left";
		
	}

	$cell_label_col=$cols[$pos_side][0];
	$cell_value_col=$cols[$pos_side][1];
	
	
	
	
	$cell_row_label=array();
	$cell_row_value=array();
	
	$old_row = $cell_row;
	
	
		
	foreach($pos as $row_label => $row_data )
	{
		logger("Cell data ",$cell_label_col.$cell_row . " - " . $row_label . " - " .$row_data);
		if(!str_starts_with($row_label,"_")) { 
			if($row_label == "Form")  
			{
				$cell_row_value[$cell_label_col.$cell_row]=$row_data;
				goto cont;
			}

			if($row_label == "Total" ) {
				$sheet_idx->getStyle($cell_value_col.$cell_row)->getNumberFormat()->setFormatCode('#,##0' );
				$total_cell_row = $cell_row;
			}
			
			$cell_row_label[$cell_label_col.$cell_row]=$row_label;
			$cell_row_value[$cell_value_col.$cell_row]=$row_data;
		}
					
		cont:
		$cell_row++;
		
	}
	

	$page_details = array ($cell_row_label,$cell_row_value);
	$range=$cell_label_col.$old_row.':'.$cell_value_col.$cell_row;
	
	$cell_row=$old_row;		
	$type = ((($index%6)==0)?1:0);

	foreach($page_details as $key_idxb => $value_array )
	{
		foreach($value_array as $label => $cell_value )
		{
			$sheet_idx->setCellValue($label ,$cell_value);
			$sheet_idx->getStyle($label)->getFont()->setSize("18");
			
			if(str_starts_with($label,"B")) {
				$sheet_idx->getStyle($label)->getFont()->setBold(false);
				$sheet_idx->getStyle($label)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);					
				continue;
			}
			if(str_starts_with($label,"E")) {
				$sheet_idx->getStyle($label)->getFont()->setBold(false);	
				$sheet_idx->getStyle($label)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_LEFT);
				continue;
			}
				
			$sheet_idx->getStyle($label)->getFont()->setBold("true");					
			$sheet_idx->getStyle($label)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);

		}			
	}
	
	$sheet_idx->mergeCells($cell_label_col.$old_row.":".$cell_value_col.$old_row);
	$sheet_idx->getStyle($cell_label_col.$old_row.":".$cell_value_col.$old_row)->getFont()->setBold("true");					
	$sheet_idx->getStyle($cell_label_col.$old_row.":".$cell_value_col.$old_row)->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
	
	$last_row=$total_cell_row ;
	if($index % 2 == 0)
	{
		cellBorder($sheet_idx,'A'.$last_row . ':F'.$last_row,"bottom");
		cellBorder($sheet_idx,'D1:D'.$last_row,"LEFT");
		$cell_row=$cell_row+10;
	}
	
	if ($type==1)
	{
		$sheet_idx->setBreak('A'.$last_row, \PhpOffice\PhpSpreadsheet\Worksheet\Worksheet::BREAK_ROW);
		
	}
	$type=0;
	#}	
	//$sheet_idx->setCellValue('P'.$index,var_export($box_details,1));
	
	
}

?>