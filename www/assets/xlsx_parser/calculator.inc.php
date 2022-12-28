<?php
    
    
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
    global $db;
    $face_trim = $form_details["face_trim"];
  
    $pcs=str_replace(',', '', trim($form_details['count']));
    $config = $form_configuration['configuration'];
    $paper_wieght = $form_configuration['paper_wieght'];
    $carton_size = $form_configuration['carton_size'];
    
    $delivery =  strtolower($form_details['former']);

    $paper_size = $form_configuration['paper_size'];

    logger("form_number pcs",$pcs);
    logger("delivery delivery",$delivery);

	logger("face_trim ",$face_trim);
	
	
    $ids = $db->subQuery ();
    $ids->where ('paper_wieght', $paper_wieght);
    $ids->where ('paper_size', $paper_size);
    $ids->where ('pages', $config);
    $ids->getone('paper_type','id');

    $db->where ("paper_id", $ids);
    $res = $db->getone("paper_count");


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

