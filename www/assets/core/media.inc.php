<?php 


function add_new_media_drop($pdf_uploaded_file="",$job_number=110011)
{
	global $explorer;
	$job_id = '';
	$media = $explorer->table("media_job");

	$pdf_filename = basename($pdf_uploaded_file);
		
	$val = $explorer->table("media_job")->where('pdf_file',$pdf_filename)->select('job_id');
		foreach ($val as $u) {
			$job_id = $u->job_id;
		}
	if($job_id == '') {
		$explorer->table("media_job")->insert([
			'job_number' => $job_number,
			'pdf_file' => $pdf_filename,
		]);
		$job_id = $explorer->getInsertId();
	}
	

    $pdf = process_pdf($pdf_uploaded_file,$job_id);

    if(count($pdf) < 1){
        return 0;
    }

	$keyidx=array_key_first($pdf);

    
	$explorer->table('media_job')->where('job_id', $job_id)->update(['close' => $pdf[$keyidx]['details']['product'] ]);

    foreach ($pdf as $form_number => $form_info )
    {
		add_form_details($form_info['details']);
		add_form_data($form_number, $job_id, $form_info);        
    }  

    return 1;
}

function add_form_details($form_array)
{
	global $explorer;
    $explorer->table("media_forms")->insert($form_array);
     
}

function add_form_data($form_number,$job_id, $form_array)
{
    global $explorer;
    
    $config = $form_array['details']['config'];
    $forms = $form_array['forms'];
	
    foreach ($forms as $letter => $row)
    {
        foreach ($row as $individual_part ) 
        {
            $individual_part['job_id'] = $job_id;
            $individual_part['form_letter'] = $letter;
            $individual_part['form_number'] = $form_number;
            $explorer->table("form_data")->insert($individual_part);
        }
    }
    
}


function get_max_drop_forms($job_id)
{
	global $connection;

	$sql = "SELECT DISTINCT(`form_number`) as max FROM `media_forms` WHERE `job_id` = ".$job_id."  ORDER BY `max` DESC limit 1";
	logger("SQL Query ",$sql);
	$result = $connection->fetch($sql);
	return $result["max"];
}

function get_first_form($job_id)
{
	global $connection;
	$sql = "SELECT `form_number` as max FROM `media_forms` WHERE `job_id` = ".$job_id." ORDER BY `max` ASC limit 1";
	logger("SQL Query ",$sql);
	$result = $connection->fetch($sql);

	return $result["max"];
}



function get_drop_details($job_id, $form_number='')
{
    global $connection;
    
    $form='';
    
    if($form_number == true)
    {
        $form =" and `form_number`= ". $form_number;
    }
    $sql = "SELECT `bind`,`config`,`form_number` FROM `media_forms` WHERE `job_id` = ".$job_id . $form;
     logger("SQL Query ",$sql);
    $result = $connection->query($sql);
  
    $form_config=array();

    foreach($result as $idx => $data)
    {
        $form_config[$data["form_number"]] = array("bind"=>$data["bind"],"config"=>$data["config"]);
    }
    
    logger("SQL Query ",$form_config);
    return $form_config;
    
}

function get_drop_form_data($job_id,$form_number='',$sort=array())
{
	global $connection;
	$add='';

    if ($form_number == true) {
		$FORM_SEQ = " and `f`.`form_number` = ".$form_number;
	}

	if(array_key_exists("SORT_LETTER",$sort))
	{ 
		if(isset($sort_query) ) {
			$add = $sort_query .", ";
		}
		$sort_query = " `f`.`form_letter` ASC ";
	}

	if(array_key_exists("SORT_NUMBER",$sort)) { 
		if(isset($sort_query) ) {
			$add = $sort_query .", ";
		}
		$sort_query =  $add ." `f`.`form_number` ASC ";
	}

	if(array_key_exists("SORT_PUB",$sort)) { 
		if(isset($sort_query) ) {
			$add = $sort_query .", ";
		}
		$sort_query = $add ." `f`.`pub` ASC ";
	}

	if(array_key_exists("SORT_FORMER",$sort)) { 
		if(isset($sort_query) ) {
			$add = $sort_query .", ";
		}
		$sort_query = $add ." `f`.`former` DESC ";
	}

	if(isset($sort_query) ) {
			$sort_query = " ORDER BY ". $sort_query;
	} else {
        $sort_query = '';
    }

	$sql = "SELECT `f`.`id`,`f`.`job_id`,`f`.`form_number`,`f`.`form_letter`,`f`.`market`,`f`.`pub`,`f`.`count`,`f`.`ship`,`f`.`former`,`f`.`face_trim`,`f`.`no_bindery`,`m`.`job_number`, `m`.`pdf_file` FROM `form_data` f, `media_job` m WHERE ( `f`.`job_id` = ".$job_id ." and `m`.`job_id` = ".$job_id . $FORM_SEQ ." ) " . $sort_query ;

	bdump($sql,"SQL Query ");

	$result = $connection->fetchAll($sql);
	bdump($result,"SQL result ");

    return $result;
}


function get_Job($job_id)
{
    global $explorer;

    $table = $explorer->table("media_job");
    return $table->get($job_id);
}

function get_form_configuration($data)
{
    $config=$data["config"];
    list($bind_type, $jog ,$carton_code) = str_split($data['bind']);
    
    
    switch ($bind_type)
    {
        case "S":
            $paper_wieght="38";
            break;
        case "P":
            $paper_wieght="50";
            break;
    }
    
    switch ($jog)
    {
        case "H":
            $jog_to="head";
            break;
        case "F":
            $jog_to="foot";
            break;
    }
    
    switch ($carton_code)
    {
        case "S":
            $carton_size="small";                
            $paper_size="small";
            break;
        case "L":
            $carton_size="large";
            $paper_size="large";
            break;
        case "M":
            $carton_size="large";
            $paper_size="small";
            break;
    }
    
   $form_configuration=array(
        "configuration"=>$config,
        "paper_wieght"=>$paper_wieght,
        "jog_to"=>$jog_to,
        "carton_size"=>$carton_size,
        "paper_size"=>$paper_size,
		"bind_type"=>$bind_type
		);
        
        return $form_configuration;
}

?>