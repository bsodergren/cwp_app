<?php

#namespace Smalot\PdfParser\Parser;

function getPageCount($config_type )
{

        switch ($config_type)
        {
            case "2+2pgs4out":
                return "4pg";
                break;
            case "2+4pgs2out":
                return "6pg";
                break;
            case "4pgs4out":
                return "4pg";
                break;
                
            case "4+2pgs2out":
                return "6pg";
                break;
            case "6pgs2out":
                return "6pg";
                break;

            case "4+4pgs2out":
                return "8pg";
                break;
            case "8pgs2out":
                return "8pg";
                break;
                   
            default:
                return "sheeter";

        }	
}




function process_pdf($pdf_file,$media_job_id)
{



	$type = '';
	$form =  array();
	$parser = new \Smalot\PdfParser\Parser();
	$pdf    = $parser->parseFile($pdf_file);

	$pages  = $pdf->getPages(); 
	$i=0;
	$next_form = 0;
	foreach ($pages as $page)
	{
		$text = $page->getDataTm();
		$p=0;
		$next_form = 0;


		$rows = count($text);
		$i++;
		$idx=0;
		unset($letter);
		unset($peices);

		$skip_letter=false;
		
		for($idx=0;$idx < $rows;$idx++)
		{

			if ($next_form == 0) {
				//unset($printer_peices);
				if (str_contains($text[$idx][1], "PRODUCTION CLOSE")) {
					$printer_peices = explode(":", $text[$idx][1]);
				}

				if (str_contains($text[$idx][1], "Run#")) {
					$form_peices = explode("Run#", $text[$idx][1]);
					$form_number = trim($form_peices[1]);

				}

				if (isset($form_number)) {

					$form[$form_number]["details"]["product"] = trim(str_replace("PRINTER", "", $printer_peices[1]));
					$form[$form_number]["details"]["job_id"] = $media_job_id;
					$form[$form_number]["details"]["form_number"] = $form_number;
				}


				if (str_contains($text[$idx][1], "Count")) {
					$peices = explode(":", $text[$idx][1]);
					$form[$form_number]["details"]["count"] = toint(trim($peices[1]));
				}

				

				if (str_contains($text[$idx][1], "Config")) {
					$peices = explode(":", $text[$idx][1]);
					$type = str_replace(" ", "", $peices[1]);
					$type = getPageCount($type);

					$form[$form_number]["details"]["config"] = trim($type);
				}
				
				if (str_contains($text[$idx][1], "Bind Type")) {
					$peices = explode(":", $text[$idx][1]);
					$type = str_replace(" ", "", $peices[1]);
					$form[$form_number]["details"]["bind"] = trim($type);
				}
				if (isset($type) && $type == "sheeter") {
					if (isset($form_number)) {
						unset($form[$form_number]);
						unset($form_number);
						unset($type);
						$next_form = 1;
					}
					continue;
				}
			

			if (isset($form_number) )
			{
			
				
				if(preg_match('/[ABCD,]+([ ]{5,})/',$text[$idx][1],$m))
				{
				//	$ar=var_export($m,true);
				//	echo $text[$idx][1] . "<br>\n".$ar."<br><br>\n\n";
					$skip_letter=true;
					//$idx++;
				}
				if(str_contains($text[$idx][1],'#'.$form_number))
				{
					$skip_text=$text[$idx][1];
					$skip_letter=false;
					$peices=explode("#".$form_number,$text[$idx][1]);
					$letter=str_replace(',','',$peices[1]);
					$form[$form_number]["forms"][$letter] = array();
					//$idx++;
				}
				
				if(isset($letter) && $skip_text != $text[$idx][1] && $skip_letter == false )
				{
					if(strlen(trim($text[$idx][1])) > 4 ) {
						
						$market=$text[$idx][1];
						$pub=$text[$idx+1][1];
						$count=str_replace(',','',$text[$idx+2][1]);
						$ship=$text[$idx+3][1];
						$form[$form_number]["forms"][$letter][$p] = array(
						"original" => $market." ".$pub." ".$count." ".$ship,
						"market"=> $market,
						"pub" => $pub,
						"count" => $count,
						"ship" => $ship);
						
						
						$p++;
						$idx=$idx+3;
						$skip_text ="";
						$skip_letter=false;
					} else {
						$p=0;
						unset($letter);
					}
				}
				
			}
			unset($peices);
			}
		}

	}
	return $form;
}
