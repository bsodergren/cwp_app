<?php
    


function build_xlsx_array($job_id, $f_number='')
{
    
    $form_config = get_drop_details($job_id,$f_number);
    
    foreach ($form_config as $form_number => $vars)
    {
     

        $total_back_peices=0;

        $prev_form_letter="";
        
        $sort=array("SORT_FORMER"=>1,"SORT_LETTER"=>1,"SORT_PUB"=>1);
        
        $result = get_drop_form_data($job_id,$form_number,$sort);
        
        $new_forms = array();

       
        foreach($result as $row_id => $form_row )
        {
        
            $current_form_letter = $form_row["form_letter"];
            
            if ($prev_form_letter != $current_form_letter) {
                $total_back_peices=0;
                $prev_form_letter = $current_form_letter;
            }
            
            if ($form_row["former"] == "" ) {$form_row["former"] = "Front";}
            

            
            if(!isset($new_array[$form_number]["bind"]) )
            {
                $new_array[$form_number] = array(
                    "bind" => $vars["bind"],
                    "config" => $vars["config"],
                    "job_number"=>$form_row["job_number"],
                    "pdf_file"=>$form_row["pdf_file"],
                    "job_id"=>$job_id);
            }
            
            
           if ( $form_row["former"] == "Back" ) 
            {
                $total_back_peices = $total_back_peices + $form_row["count"];
                $new_array[$form_number][$form_row["former"]][$form_row["form_letter"]][99] = array("market" => $form_row["market"],
                                                                                                "pub" => $form_row["pub"],
                                                                                                "count" => $total_back_peices,
                                                                                                "ship" => $form_row["ship"],
                                                                                                "job_number" => $form_row["job_number"],
                                                                                                "former" => $form_row["former"],
																								"face_trim" => $form_row["face_trim"],
																								"no_bindery" => $form_row["no_bindery"]);
            } else {
                $new_array[$form_number][$form_row["former"]][$form_row["form_letter"]][] = array("market" => $form_row["market"],
                                                                                                "pub" => $form_row["pub"],
                                                                                                "count" => $form_row["count"],
                                                                                                "ship" => $form_row["ship"],
                                                                                                "job_number" => $form_row["job_number"],
                                                                                                "former" => $form_row["former"],
																								"face_trim" => $form_row["face_trim"],
																								"no_bindery" => $form_row["no_bindery"]);
            }
        }
    }

    return $new_array;
}
    



