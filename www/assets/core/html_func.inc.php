<?php

function output($var)
{
    if (is_array($var)) {
        print_r2($var);
        return 0;
    }

    echo $var."\n";
   // return 0;
    
}

function create_form($url,$method,$input)
{
	
	$html='';
	$html.='<form action="'.$url.'" method="'.$method.'">'."\n";
	$html.=$input;
	$html.="</form>\n";
	output($html);
}


function add_submit_button($name,$value,$attributes='')
{
	$html='';
	$html.='<input '.$attributes.' type="submit" name="'.$name.'"  value="'.$value.'">';
	return $html. "\n";
	
}

function add_hidden($name,$value,$attributes='')
{
	$html='';
	$html.='<input '.$attributes.' type="hidden" name="'.$name.'"  value="'.$value.'">';
	return $html. "\n";
}

function draw_link($url,$text,$attributes='',$return=true)
{
	
	$html='';
	$html.='<a '.$attributes.'  href="'.$url.'">'.$text.'</a>' ;
	if ($return == true ) {
		return $html. "\n";
	}else{
		output( $html);
	}
}


function draw_textbox($name,$value,$attributes='')
{
	$html='';
	$html.='<input '.$attributes.' type="text" name="'.$name.'" placeholder="'.$value.'" value="'.$value.'">';
	return $html;
}

function draw_checkbox($name,$value,$text='Face Trim')
{
    global $pub_keywords;
    
    $checked="";
	
   
	$current_value = $value;
    
    
    if ($current_value == 1 ) { $checked = "checked"; }
    
    $html = '';
    $html .= '<input type="checkbox" name="'.$name.'" value="1" '.$checked.'>'.$text;
    return $html;
}

function draw_radio($name,$value)
{
    $html='';
    
    foreach($value as $option )
    {
        $html .= '<input type="radio" class="'.$option["class"].'" name="'.$name.'" value="'.$option["value"].'" '.$option['checked'].'>'.$option['text'] . '&nbsp;';
    }
   // $html = $html . "<br>"."\n";
    return $html;
}


function display_log($string)
{
    echo "<pre>".$string."</pre>\n";
}


function myHeader($redirect = __URL_PATH__."/home.php")
{
    
    
    if (  str_contains($redirect, "excel") ||
          str_contains($redirect, "zip")) {
            $redirect = __URL_PATH__."/home.php";
    }
    
    header( "refresh:0;url=".$redirect);
    
}



function JavaRefresh($url,$timeout=0)
{
    global $_REQUEST;
    
    $html = '<script>' . "\n";
    
    
    if($timeout > 0)
    {
        $html .= 'setTimeout(function(){ ';
    }
    
    $html .= "window.location.href = '".$url."';";
    
    if($timeout > 0)
    {
        $html .= '}, '.$timeout.');';
    }
    $html .= "\n".'</script>';

    return $html;
}