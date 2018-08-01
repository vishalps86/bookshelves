<?php

class Viperks
{
  public function load_script($path, $echo = false)
  {
      $path = base_url().$path;
      $js = '<script>';
      $js .= 'if(LoadedScripts.indexOf("'.$path.'") == -1) { ';
      $js .= 'jQuery.getScript("'.$path.'"); ';
      $js .= 'LoadedScripts.push("'.$path.'"); ';
      $js .= '}';
      $js .= '</script>';
      if($echo) echo $js;
      else return $js;
  }
  
  public function load_style($path, $echo = false)
  {
      $path = base_url().$path;
      $js = '<script>';
      $js .= 'if(LoadedStyle.indexOf("'.$path.'") == -1) { ';
      $js .= '$("head").append("<link rel=\"stylesheet\" href=\"'.$path.'\" type=\"text/css\">"); ';
      $js .= 'LoadedStyle.push("'.$path.'"); ';
      $js .= '}';
      $js .= '</script>';
      if($echo) echo $js;
      else return $js;
  }
}