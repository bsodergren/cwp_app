<?php
require_once(".config.inc.php");
define('TITLE', "Test Page");

require __LAYOUT_HEADER__;
$settings_html='';
	//$form->messages(); 
	# $form->create_form('Name, Email, Comments|textarea');
	if (defined('__SETTINGS__')) {
		foreach (__SETTINGS__ as $definedName => $array) {

			$type =  $array['type'];
			$value =  $array['value'];
			$description  = $array['description'] ;
			$description = $definedName ." " . $description;

			$tooltip = 'data-bs-toggle="tooltip" data-bs-placement="top" data-bs-custom-class="custom-tooltip"	data-bs-title="'.$description.'" ';

			if ($type == "bool") {
				$checked = '';
				$notchecked = '';

				if ($value == 1) {
					$checked = "checked";
				} else {
					$notchecked = "checked";;
				}

				$params = [
					'DEFINED_NAME' => $definedName,
					'TOOLTIP' => $tooltip,
					'CHECKED' => $checked,
					'NAME' => $array['name'],
					'DESCRIPTION' => $array['description'],
				];
				$template->template("settings/checkbox",$params);
				
			}

			if ($type == "text") {

				$params = [
					'DEFINED_NAME' => $definedName,
					'TOOLTIP' => $tooltip,
					'VALUE' => $value,
					'NAME' => $array['name'],
					'DESCRIPTION' => $array['description'],

				];
				$template->template("settings/text",$params);
				
			}
		}
	}

	$template->template("settings/new_setting",'');

	$settings_html .= $template->return();
	$template->clear();


	$template->template("settings/main",[
		'SETTINGS_HTML' => $settings_html	]);

		$template->render();
include __LAYOUT_FOOTER__;  ?>