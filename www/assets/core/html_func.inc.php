<?php
use Nette\Utils\FileSystem;
use Nette\Utils\DateTime;

function get_caller_info()
{
    $trace = debug_backtrace();

    $s = '';
    $file = $trace[1]['file'];
    foreach($trace as $row) {
        switch($row['function']) {
            case __FUNCTION__:
                break;
            case "logger":
                $lineno = $row["line"];
                break;
            case "require_once":
                break;
            case "include":
                break;
            default:
                $s = $row['function'] . ":" . $s;
                $file = $row['file'];
        }
    }
    $file = pathinfo($file, PATHINFO_BASENAME);
    return $file . ":" . $s . ":";
}


function logger($text,$var='')
{
    $function_list = get_caller_info();

    $colors = new Colors();

    if(defined('ERROR_LOG_FILE'))
    {
        $html_var='';
        $html_string='';
        $html_msg='';

        if(is_array($var) || is_object($var)) {
            $html_var_string = var_export($var, 1);
    
        } else {
            $html_var_string = $var;
        }

        if($html_var_string != '' ) {

          //  $html_var_string = wordwrap($var, 80, "<br>");
            $html_var = $colors->getColoredHTML("<span style=\"text-indent: 40px\">" . $html_var_string . "</span>", "green");
        }
    
        $html_msg = $colors->getColoredHTML($text, "red");
        $html_func = $colors->getColoredHTML($function_list, "blue");

        $html_string = DateTime::from(null).":".$html_func . ":" .$html_msg . " " . $html_var . "<br>\n";

        Log::append(ERROR_LOG_FILE,$html_string);
    }

    bdump($var, $text);
}

function output($var)
{

    echo $var."<br>\n";
    ob_flush();
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
        $timeout = $timeout * 1000;
        $html .= '}, '.$timeout.');';
    }
    $html .= "\n".'</script>';

    return $html;
}

function skipFile($filename)
{
        $replacement  = '<?php';
        $replacement .= ' #skip';
        $__db_string  = FileSystem::read($filename);
        $__db_write   = str_replace('<?php', $replacement, $__db_string);
        FileSystem::write($filename, $__db_write);
}

function check_skipFile($filename)
{
    if(defined('__FIRST_RUN__')) {
        return 0;
    }

    $f    = fopen($filename, 'r');
    $line = fgets($f);
    fclose($f);
    return strpos($line, '#skip');
}