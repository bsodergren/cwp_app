<?php

function print_r2($val){
    echo '<pre>';
    print_r($val);
    echo  '</pre>';
}



class Template
{
    public $html = '';

    public $template_file;

    public function callback_replace($matches)
    {
        return "";
    }

    public function clear(){
        $this->html = '';
    }

    public function return()
    {
        $html = $this->html;
        $this->clear();
        return $html;
    }

    public function render()
    {
        $html = $this->html;
        $this->clear();
        echo $html;
    }


    public function template($template, $replacement_array='')
    {
        $template_file=__TEMPLATE_DIR__."/".$template.".html";

        if (!file_exists($template_file)) {
            //use default template directory
            $html_text = "<h1>NO TEMPLATE FOUND<br>";
            $html_text .= "FOR <pre>".$template."</pre></h1> <br>";

            $this->html .= $html_text;
        }

        $html_text = file_get_contents($template_file);

        if (is_array($replacement_array)) {
            foreach ($replacement_array as $key => $value) {
               // $value = "<!-- $key --> \n".$value;
                $key = "%%".strtoupper($key)."%%";
                $html_text = str_replace($key, $value, $html_text);
            }

            $html_text = preg_replace_callback('|(%%\w+%%)|', array($this, "callback_replace"), $html_text);
        }

        $html_text = "<!-- start $template --> \n" . $html_text . "\n";
        $this->html .= $html_text;
        return $this->html;
    }




}