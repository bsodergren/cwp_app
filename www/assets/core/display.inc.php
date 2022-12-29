<?php
function display_paper_as_cols($array)
{
     $html=process_template("form_letter_header",array("NUMBER" => "Paper Editor"));
    foreach($array as $name => $v)
    {
        if($name == "id" ){ continue;}
        if($name == "paper_id" )
        { 
            $html .= '<input type="hidden" name="'.$name.'" value="'.$v.'">';
            continue;
        }
        
        if($name == "paper_wieght" || $name == "paper_size"  || $name == "pages" )
        {
            $html .=   "<tr><td>".$name."</td><td>$v</td></tr>"."\n";
            continue;
        }
            $html .=   "<tr>
            <td>".$name."</td>\n
            <td><input type=\"text\" name=\"".$name."\"  placeholder=\"".$v."\" value=\"".$v."\"></td>
            </tr>\n";        
    }
    
    return $html;
}

function display_array_as_cols($array)
{
     $html=process_template("form_letter_header",array("NUMBER" => "Paper Editor"));
    foreach($array as $name => $v)
    {
        if($name == "id" )
        { 
            $html .= '<input type="hidden" name="'.$name.'" value="'.$v.'">';
            continue;
        }        
      
            $html .=   "<tr>
            <td>".$name."</td>\n
            <td><input type=\"text\" name=\"".$name."\"  placeholder=\"".$v."\" value=\"".$v."\"></td>
            </tr>\n";        
    }
    
    return $html;
}




function display_array_as_row($array)
{
    $html=process_template("form_letter_header",array("NUMBER" => "Paper Editor"))."\n";
  
    foreach($array as $row => $values)
    {
        $html .= "\t<tr>"."\n";
        foreach($values as $k => $v)
        {
           
            if($k == "id") {
                $html .= "\t\t<td><a href=\"".__FORM_URL__."?edit&id=".$v."\"> edit </a></td>"."\n";
                if(TITLE != "Paper Editor"){  $html .= "\t\t".'<td><a href="'.__FORM_URL__.'?delete&id='.$v.'">Delete</a></td>'."\n";}
			} elseif($k != "trim" ) {
                $html .= "\t\t<td>".$v."</td>\n";
            }
        }
        $html .=  "\t</tr>"."\n";
    }
        
  $html .= "\t".'<tr><td colspan=2><a href="'.__FORM_URL__.'?add"> Add new data </a></td></tr>'."\n";
    
    return $html;
}


function display_paper($paper_id)
{
    global $db;
    
     $db->where("id", $paper_id);
    $paper_type = $db->getone("paper_type");
    
    $db->where("paper_id", $paper_id);
    $res = $db->getone("paper_count");
    
    output( $paper_type['paper_wieght'] . "## - " . 
    $paper_type['paper_size'] . " - " .
    $paper_type['pages'] ."pgs <br>");
    
    foreach($res as $key => $value)
    {
        if($key == "id" || $key == "paper_id" ){
        output('<input type="hidden" name="paper_'.$paper_id.'_'.$key.'" value="'.$value.'"><br>');
        } else {
        output($key.'<input type="text" name="paper_'.$paper_id.'_'.$key.'" placeholder="'.$value.'"><br>');
        }
    }

    
}   



function display_page()
{

    return $html;
}

function display_table_header($name,$prev,$next,$edit='')
{
        
    $array = array(
        "NAME" => $name,
        "EDIT" =>$edit,
        "PREVIOUS" =>  $prev,
        "NEXT" =>  $next );

    return process_template("table_header",$array);

}


function display_table_LetterHeader($number,$letter,$array)
{       
    $html =  process_template("form_letter_header",array("NUMBER" => $number,"LETTER" => $letter));
    $html .= display_table_rows($array,$letter);
    return $html;
}


function display_table_rows($array,$letter)
{
    $html='';
    $start = '';
    $end = '';

    foreach ($array as $part)
    {
        if($start == '') { 
            $start = $part["id"];
        }
        
        $end = $part["id"];
    
        $check_front = "";
        $check_back = "";
        
        $classFront="Front".$letter;
        $classBack="Back".$letter;
        

         if ( $part["former"] == "Back" ) {$check_back = "checked"; }
         if ( $part["former"] == "Front" ) {$check_front = "checked"; }
        $radio_check = '';

        if($part['config'] == "4pg"  ) {
            $value = array (
                "Front" => array("value"=>"Front","checked" => $check_front, "text" => "Front","class" => $classFront),
                "Back" => array("value"=>"Back","checked" => $check_back, "text" => "Back","class" => $classBack)
            );
            $radio_check = draw_radio("former_".$part["id"], $value);
        } 
        
        
                
        $array = array(
            "MARKET"=>$part["market"],
            "PUBLICATION"=>$part["pub"],
            "COUNT"=>$part["count"],
            "RADIO_BTNS"=>$radio_check,
            "FACE_TRIM"=>draw_checkbox("facetrim_".$part["id"],$part["facetrim"],'Face Trim'),
            "NO_TRIM"=>draw_checkbox("nobindery_".$part["id"],$part["nobindery"],'No Bindery Trim'));
        
        $html .=  process_template("form_row",$array);
    }
    
    $AllCheckBoxFront="all".$classFront;
    $AllCheckBoxBack="all".$classBack;
    if ($end > $start+1 && $radio_check != '' )
    {
        $html .= "
        <tr>
            <td colspan=3 align=right>All Parts&nbsp;&nbsp;&nbsp;</td>
        <td align=left nowrap><input type='radio' onclick=\"checkAll".$letter."(\$(this));\" name=\"All".$letter."\"  class=\"".$AllCheckBoxFront."\" > Front 
        <input type='radio' onclick=\"checkAll".$letter."(\$(this));\" name=\"All".$letter."\"  class=\"".$AllCheckBoxBack."\"> Back  </td>
        </tr>";
        
        $html .= "
        <script type=\"text/javascript\">
                function checkAll".$letter."(e) {
                    if(e.hasClass('".$AllCheckBoxFront."')) {
                        $('.".$classFront."').attr('checked', 'checked');
                    } else {
                        $('.".$classBack."').attr('checked', 'checked');
                    }
                }
                </script>";
    }
    
    return $html;
    
}


function display_navbar_links()
{
  
    global $navigation_link_array;
    global $_SERVER;
    $html='';
    $dropdown_html='';
	
    foreach($navigation_link_array as $name => $link_array)
    {
		if($name == "dropdown" )
		{
			$dropdown_html='';
			
			foreach($link_array as $dropdown_name => $dropdown_array)
			{
				$dropdown_link_html='';
				
				foreach ($dropdown_array as $d_name => $d_values)
				{
					$array = array("DROPDOWN_URL_TEXT" => $d_name,
						"DROPDOWN_URL" => $d_values);
					$dropdown_link_html .= process_template("menu_dropdown_link",$array);
				}
					
					
				$array = array("DROPDOWN_TEXT" => $dropdown_name,
				"DROPDOWN_LINKS" => $dropdown_link_html);
				
				$dropdown_html .= process_template("menu_dropdown",$array);
			}
            
		} else {
			
			$array = array(
				"MENULINK_URL" => $link_array["url"],
				"MENULINK_JS" => $link_array["js"],
				"MENULINK_TEXT" => $link_array["text"]);
			$url_text = process_template("menu_link",$array);
			
			if ($link_array["secure"] == true && $_SERVER['REMOTE_USER'] != "bjorn") {
				$html=$html.$url_text."\n";
			} else {
				$html=$html.$url_text."\n";
			}				
		}
    }
    
    return $html . $dropdown_html ;
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