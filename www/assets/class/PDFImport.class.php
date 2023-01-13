<?php

use Nette\Utils\Strings;



class PDFImport extends MediaImport
{

    public $form;

    public function __construct($pdf_file, $media_job_id)
    {
    
        $type = '';
        $form =  array();
        $parser = new \Smalot\PdfParser\Parser();
    
        $pdf    = $parser->parseFile($pdf_file);
    
        $pages  = $pdf->getPages();
    
        foreach ($pages as $page) {
              unset($page_text);
    
            $text = $page->getDataTm();
    
            foreach ($text as $n => $row) {
                $page_text[$n] = $row[1];
            }
    
            //	dump($page_text);
            $page_count = count($page_text);
    
            unset($letter);
            unset($peices);
            unset($form_number_array);
            unset($form_number);
            unset($form_rows);
    
            $next_form = false;
    
            for ($idx = 0; $idx <= $page_count; $idx++) {
    
                $line = trim($page_text[$idx]);
    
    
                if ($next_form == false) {
                    if (str_contains($line, "PRODUCTION CLOSE")) {
                        $printer_peices = explode(":", $line);
                    }
                    if (str_contains($line, "Run#")) {
                        $form_peices = explode("Run#", $line);
                        $form_number = trim($form_peices[1]);
                    }
    
                    if (str_contains($line, "Count")) {
                        $peices = explode(":", $line);
                        $form[$form_number]["details"]["count"] = toint(trim($peices[1]));
                    }
    
                    if (str_contains($line, "Config")) {
                        $peices = explode(":", $line);
                        $type = str_replace(" ", "", $peices[1]);
                        $type = $this->getPageCount($type);
                        $form[$form_number]["details"]["config"] = trim($type);
                    }
    
                    if (str_contains($line, "Bind Type")) {
                        $peices = explode(":", $line);
                        $type = str_replace(" ", "", $peices[1]);
                        $form[$form_number]["details"]["bind"] = trim($type);
                    }
    
    
                    if (isset($form_number)) {
    
                        $form[$form_number]["details"]["product"] = trim(str_replace("PRINTER", "", $printer_peices[1]));
                        $form[$form_number]["details"]["job_id"] = $media_job_id;
                        $form[$form_number]["details"]["form_number"] = $form_number;
                    }
                    if (isset($type) && $type == "sheeter") {
                        if (isset($form_number)) {
                            unset($form[$form_number]);
                            unset($form_number);
                            unset($type);
                            $next_form = true;
                        }
    
                        continue;
                    }
                }
            }
    
    
    
    
            if ($next_form == false && isset($form_number)) {
    
                for ($idx = 0; $idx <= $page_count; $idx++) {
    
                    $line = trim($page_text[$idx]);
                    $res = $this->find_first($form_number, $idx, $page_text);
                    $res2 = $this->find_end($form_number, $res, $page_text);
                    $form_number_array[] = $res2;
                    //$letter_idx = key($res2);
                    $r_letter = key($res2);
                    $idx = $res2[$r_letter]['stop'] + 1;
                }
    
    
                foreach ($form_number_array as $l_idx => $letter_array) {
    
                    $letter = key($letter_array);
    
                    $start = $letter_array[$letter]['start'];
                    $stop = $letter_array[$letter]['stop'];
    
                    $form_rows[$letter] = $this->row_data($start, $stop, $page_text);
                }
                $form[$form_number]['forms'] = $form_rows;
            //	dump($page_text);
    
                unset($letter);
                unset($peices);
                unset($form_number_array);
                unset($form_number);
                unset($form_rows);
            }
        }
    
         $this->form = $form;
    }
    
    
public function find_first($form_number, $start, $array)
{
	$result = [];
	$row_count = count($array);

	for ($idx = $start; $idx <= $row_count; $idx++) {
		$line = trim($array[$idx]);
		if (Strings::contains($line, '#' . $form_number)) {
			$peices = explode("#" . $form_number, $line);
			$letter = str_replace(',', '', $peices[1]);
			$result = [$letter => ['start' => $idx + 1]];
			break;
		}
	}
	return $result;
}

public function find_end($form_number, $start_array, $array)
{
	$result = [];

	$row_count = count($array);
	$letter = key($start_array);

	$start_row = $start_array[$letter]['start'] + 1;
	$start_array[$letter]['stop'] = $row_count - 1;

	for ($idx = $start_row; $idx <= $row_count; $idx++) {
		$line = trim($array[$idx]);
		if (Strings::contains($line, '#' . $form_number)) {
			$peices = explode("#" . $form_number, $line);
			$t_letter = str_replace(',', '', $peices[1]);

			for ($tdx = $idx; $tdx > $start_row; $tdx--) {
				$t_line = trim($array[$tdx]);
				$t_line = str_replace(',', '', $t_line);
				if ($t_letter == $t_line) {
					$start_array[$letter]['stop'] = $tdx - 1;
					return $start_array;
				}
			}
		}
	}
	return $start_array;
}


public function row_data($start, $stop, $page_text)
{
	$tip = ' ';
	if (((($stop + 1) - $start)  % 4) == 0) {
		$break = 3;
	} else if (((($stop + 1) - $start)  % 5) == 0) {
		$break = 4;
	}

	$r = 0;
	$i = 0;
	for ($idx = $start; $idx <= $stop; $idx++) {
		//		  HTMLDisplay::echo ($r. ":".$page_text[$idx]);
		switch ($r) {
			case 0:
				$market = $page_text[$idx];
				break;
			case 1:
				$pub = $page_text[$idx];
				break;
			case 2:
				$count = str_replace(',', '',$page_text[$idx]);
				break;
			case 3:
				$ship = $page_text[$idx];
				break;
			case 4:
				$tip = $page_text[$idx];
				break;
		}

		if ($r < $break) {
			$r++;
		} else {

			$row_array = [
					"original" => $market . " " . $pub . " " . $count . " " . $ship,
				"market" => $market,
				"pub" => $pub,
				"count" => $count,
				"ship" => $ship,
				"tip" => $tip,
			];
			$r = 0;
			$rows[$i] = $row_array;
			$i++;
		}
	}
	return $rows;
}

public function getPageCount($config_type)
{

	switch ($config_type) {
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






}