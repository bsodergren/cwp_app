<?php
require_once(".config.inc.php");
define('TITLE', "Test Page");

require __LAYOUT_HEADER__;

$settings_html='';
$checkbox_html='';

$textbox_html='';
//$form->messages(); 
	# $form->create_form('Name, Email, Comments|textarea');
	if (defined('__SETTINGS__')) {
		foreach (__SETTINGS__ as $definedName => $array) {

			$type =  $array['type'];
			$value =  $array['value'];
			$description  = $array['description'] ;
			$tooltip_desc = $definedName ." " . $description;
			$name = $array['name'];
			
			$name_label = '';
			$tooltip = 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"	data-bs-title="'.$tooltip_desc.'" ';


			if ($type == "bool") {
				$checked = '';
				$notchecked = '';

				if ( $name == null )
				{
					$name = $definedName;
					$name_label = template::echo("settings/checkbox/textbox",['DESCRIPTION' => $definedName,'DEFINED_NAME'=>$definedName]);
				} else {
					$name_label = template::echo("settings/checkbox/label",['NAME' => $name,'DEFINED_NAME'=>$definedName]);
	
				}
				
				if($description == null )
				{
					$description_label = template::echo("settings/checkbox/description_textbox",['DESCRIPTION' => $definedName,'DEFINED_NAME'=>$definedName]);
				} else {
					$description_label = template::echo("settings/checkbox/description_label",['DESCRIPTION' => $description,'DEFINED_NAME'=>$definedName]);
				}

				if ($value == 1) {
					$checked = "checked";
				} else {
					$notchecked = "checked";;
				}

				$params = [
					'DEFINED_NAME' => $definedName,
					'TOOLTIP' => $tooltip,
					'CHECKED' => $checked,
					'NAME' => $name,
					'NAME_LABEL' => $name_label,
					'DESCRIPTION_LABEL' => $description_label,
				];
				$checkbox_html .= template::echo("settings/checkbox/checkbox",$params);
				
			}

			if ($type == "text") {

				$place_holder = $value;
				if($value == '' ){
					$place_holder = "no value set";
				}

				if ( $name == null )
				{
					$name = $definedName;
					$name_label = template::echo("settings/text/textbox",['DESCRIPTION' => $definedName,'DEFINED_NAME'=>$definedName]);
				} else {
					$name_label = template::echo("settings/text/label",['NAME' => $name,'DEFINED_NAME'=>$definedName]);
	
				}

				if($description == null )
				{
					$description_label = template::echo("settings/checkbox/description_textbox",['DESCRIPTION' => $definedName,'DEFINED_NAME'=>$definedName]);
				} else {
					$description_label = template::echo("settings/checkbox/description_label",['DESCRIPTION' => $description,'DEFINED_NAME'=>$definedName]);
				}
				$params = [
					'DEFINED_NAME' => $definedName,
					'PLACEHOLDER' => $place_holder,
					'TOOLTIP' => $tooltip,
					'VALUE' => $value,
					'NAME' => $name,
					'NAME_LABEL' => $name_label,
					'DESCRIPTION_LABEL' => $description_label,

				];
				$textbox_html .= template::echo("settings/text/text",$params);
				
			}
		}
	}

	$delete_log = template::echo("settings/delete_log");

	$template->template("settings/new_setting",'');

	$settings_html = $template->return();
	$template->clear();


	$template->template("settings/main",[
		'CHECKBOX_HTML' => $checkbox_html,
		'TEXTBOX_HTML' => $textbox_html,
		'SETTINGS_HTML' => $settings_html,
		'DELETE_LOG' => $delete_log	]);

		$template->render();
include __LAYOUT_FOOTER__;  ?>