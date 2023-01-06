<?php
require_once(".config.inc.php");

$deleted_id = 0;

define("REFRESH_URL", "/index.php");
/*
$job_id=$_REQUEST['job_id'];
$form_number=$_REQUEST['form_number'];

if(key_exists("cancel",$_REQUEST))
{
	$form_number--;
	myHeader(__URL_HOME__."/form.php?job_id=".$job_id."&form_number=".$form_number."");  
	exit;
}


if(key_exists("submit",$_REQUEST))
{
	 foreach ($_REQUEST as $key => $value )
	 {
		if ($key == "job_id") { continue; }
		if ($key == "form_number") { continue; }
		if ($key == "submit") { continue; }
 
		list($id,$action) = explode("_",$key);
		
		if($deleted_id == $id) {continue;}	
		
		unset($data);
		
		switch ($action)
        {
			case "delete":
				$db->where('id', $id);
				$db->delete('form_data');
				$deleted_id = $id;
				break;
				
			case "split":
				$db->where("id", $id);
				$form_data = $db->getOne("form_data");
				
				$form_data['count'] = $form_data['count'] / 2;
				$db->where ('id',  $form_data['id']);
				$db->update ('form_data', $form_data);
				unset($form_data['id']);				
				$id = $db->insert ('form_data', $form_data);	
				myHeader(__URL_HOME__."/edit_form.php?job_id=".$job_id."&form_number=".$form_number."");  				
				exit;
				break;
				
			case "formletter":
				$data = Array ('form_letter' => strtoupper($value));
				break;
				
			case "facetrim":
				$data = Array ('face_trim' => $value);
				break;

			case "former":
				$data = Array ('former' => $value);
				break;
				
			case "pcscount":
				if(str_contains($value,"x")) {
					list($x,$n) = explode("x",$value);
					$value = $x * $n;
				}
				if(str_contains($value,"/")) {
					list($x,$n) = explode("/",$value);
					$value = $x / $n;
				}				
				$data = Array ('count' => $value);
				break;
		}

		if(isset($data) ) {
			$db->where ('id',  $id);
			$db->update ('form_data', $data);
		}
	}
	
	$form_number--;
	myHeader(__URL_HOME__."/form.php?job_id=".$job_id."&form_number=".$form_number."");  
	//include __LAYOUT_FOOTER__;
	
	exit;
}

define('TITLE', "Media Job editor");
include __LAYOUT_HEADER__;
//
$form_url = __URL_PATH__."/edit_form.php";

$db->where ("job_id", $job_id);
$db->where ("form_number", $form_number);
$form_row = $db->get("form_data");
    
$check_back = "";
$check_front = "";

?>

<main role="main" class="container">
<form>
<input type="hidden" name="job_id" value="<?php echo $job_id; ?>">
<input type="hidden" name="form_number" value="<?php echo $form_number; ?>">
<table  class="blueTable">
<thead>
<tr>
	<th>Remove</th>
	<th>Split</th>
	<th>Form Number </th>
	<th>Form letter </th>
	<th nowrap>market, pub, shipping</th>
	<th>count</th>
	<th>Former</th>
	<th>Face Trim</th>
</tr>
</thead>
<tbody>

<?
foreach ($form_row as $idx => $row) 
{
	$classFront="Front".$row['form_letter'];
	$classBack="Back".$row['form_letter'];
	
	output("<tr>");

	// remove checkbox
	output("<td align=center valign=middle>". draw_checkbox($row['id']."_delete","delete",""). "</td>");
		output("<td align=center valign=middle>". draw_checkbox($row['id']."_split","split",""). "</td>");

	output("<td align=center valign=middle>".$row['form_number']."</td>");
	
	// adjust Letters
	output("<td width=30>" . draw_textbox($row['id']."_formletter",$row['form_letter'],"class=formbox"). "</td>");
	
	// Show Pub and Market data
	output("<td nowrap>".$row['market']." ".$row['pub']." ".$row['ship']."</td>");
	
	//Counts
	output("<td nowrap>" . draw_textbox($row['id']."_pcscount",$row['count'],"class=formbox"). "</td>");

	
	// What former $row['former']
	
		if ( $row["former"] == "Back" ) {$check_back = "checked"; }
		if ( $row["former"] == "Front" ) {$check_front = "checked"; }

		 $value = array (
			"Front" => array("value"=>"Front","checked" => $check_front, "text" => "Front","class" => $classFront),
			"Back" => array("value"=>"Back","checked" => $check_back, "text" => "Back","class" => $classBack)
			);
    output("<td nowrap>".draw_radio($row["id"]."_former", $value)."</td>");

	// Has face trim former $row['face_trim']
	output("<td nowrap>". draw_checkbox($row["id"]."_facetrim",$row['face_trim'],"Face Trim")."</td>");
	
	output("</tr>");
	
}

?>

<tr>
<td colspan=7> <input type="submit" name="submit" value="submit"> <input type="submit" name="cancel" value="cancel"></td></tr>
</form>
</tbody>
</table>
</main>

<?php
include __LAYOUT_FOOTER__;

*/
?>