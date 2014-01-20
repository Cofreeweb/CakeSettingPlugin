<?php

class SettingsStartupComponent extends Component 
{

  public function initialize( Controller $controller, $settings = array()) 
  {
    $value = Settings::read( 'App.Web.number');
  }

  public function startup( Controller $controller) 
  {
    
  }

  
}
?>