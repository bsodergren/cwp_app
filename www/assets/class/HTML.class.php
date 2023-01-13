<?php

use Nette\Utils\FileSystem;
use Nette\Utils\DateTime;

class HTMLDisplay
{


    public static function javaRefresh($url, $timeout = 0)
    {

        if ($timeout > 0) {
            $timeout = $timeout * 1000;
            $update_inv =  $timeout / 100;
            echo Template::echo("progress_bar", ['SPEED' => $update_inv]);
        }

        echo template::echo('js_refresh_window', ['REFRESH_URL' => $url, 'MS_SECONDS' => $timeout]);
    }

    public static function echo($value, $exit = 0)
    {

        echo '<pre>' . var_export($value, 1) . '</pre>';

        if ($exit == 1) {
            exit;
        }
    }
    
    public static function output($var,$nl="")
    {
        echo $var . $nl."\n";
        ob_flush();
    }





    public function create_form($url, $method, $input)
    {

        $html = '';
        $html .= '<form action="' . $url . '" method="' . $method . '">' . "\n";
        $html .= $input;
        $html .= "</form>\n";
        self::output($html);
    }


    public function add_submit_button($name, $value, $attributes = '')
    {
        $html = '';
        $html .= '<input ' . $attributes . ' type="submit" name="' . $name . '"  value="' . $value . '">';
        return $html . "\n";
    }

    public function add_hidden($name, $value, $attributes = '')
    {
        $html = '';
        $html .= '<input ' . $attributes . ' type="hidden" name="' . $name . '"  value="' . $value . '">';
        return $html . "\n";
    }

    public function draw_link($url, $text, $attributes = '', $return = true)
    {

        $html = '';
        $html .= '<a ' . $attributes . '  href="' . $url . '">' . $text . '</a>';
        if ($return == true) {
            return $html . "\n";
        } else {
            self::output($html);
        }
    }


    public function draw_textbox($name, $value, $attributes = '')
    {
        $html = '';
        $html .= '<input ' . $attributes . ' type="text" name="' . $name . '" placeholder="' . $value . '" value="' . $value . '">';
        return $html;
    }

    public function draw_checkbox($name, $value, $text = 'Face Trim')
    {
        global $pub_keywords;

        $checked = "";


        $current_value = $value;


        if ($current_value == 1) {
            $checked = "checked";
        }

        $html = '';
        $html .= '<input type="checkbox" name="' . $name . '" value="1" ' . $checked . '>' . $text;
        return $html;
    }

    public function draw_radio($name, $value)
    {
        $html = '';

        foreach ($value as $option) {
            $html .= '<input type="radio" class="' . $option["class"] . '" name="' . $name . '" value="' . $option["value"] . '" ' . $option['checked'] . '>' . $option['text'] . '&nbsp;';
        }
        // $html = $html . "<br>"."\n";
        return $html;
    }
}


function JavaRefresh($url, $timeout = 0)
{
    HTMLDisplay::JavaRefresh($url, $timeout);
}
