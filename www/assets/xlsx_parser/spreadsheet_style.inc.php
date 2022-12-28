<?php

function sheetCommon(&$sheet_obj)
{
	global $db;

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
	 $sql = "SELECT CONCAT_WS('',ecol, erow) as location, text,bold,font_size,h_align,v_align FROM flag_style WHERE erow IS NOT NULL;";

        logger("SQL Query ",$sql);

        $result = $db->query($sql);
        logger("SQL Query ",$result);
    
    	foreach($result as $val )
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
	global $db;

	$result = $db->query("SELECT ecol,width FROM flag_style WHERE erow IS NULL and style_name = '".$style."' ORDER BY ecol ASC");

	foreach($result as $k => $v)
	{
		$sheet_obj->getColumnDimension($v['ecol'])->setWidth($v['width']);
	}
}    
