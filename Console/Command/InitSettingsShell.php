<?php

class InitSettingsShell extends AppShell 
{
  public $uses = array( 'Setting.Setting');
  
/**
 * Setea la configuración por defecto de las settings, tanto de la app como de todos los plugins
 *
 * @return void
 * @example bin/cake Setting.init_settings set
 */
  public function set()
  {
    $path = APP . 'Config' .DS. 'settings.php';
    
    if( file_exists( $path))
    {
      Configure::load( 'settings', 'default', false);
      $data = Configure::read( 'SettingConfig');
      $this->save( $data, 'App');
    }
    
    $plugins = App::objects( 'plugin');
    
    foreach( $plugins as $plugin)
    {
      if( CakePlugin::loaded( $plugin))
      {
        $path = App::pluginPath( $plugin) . 'Config'. DS. 'settings.php';

        if( file_exists( $path))
        {
          Configure::load( $plugin .'.settings', 'default', false);
          $data = Configure::read( 'SettingConfig');
          $this->save( $data, $plugin);
        }
      }
    }
  }
  
  private function save( $set, $plugin)
  {
    $this->out( $plugin);

    foreach( $set as $model => $values)
    {
      foreach( $values as $key => $value)
      {
        $data = array(
            'plugin' => $plugin,
            'model' => $model,
            'key' => $key
        );
        
        $this->Setting->Behaviors->unload( 'Translate');
        
        if( !$this->Setting->hasAny( $data))
        {
          if( isset( $value ['i18n']))
          {
            $this->Setting->Behaviors->load( 'Translate', array(
                'value' => 'Values'
            ));
          }
          else
          {
            $this->Setting->Behaviors->unload( 'Translate');
          }
          
          $this->Setting->create();
          
          if( isset( $value ['default']))
          {
            if( isset( $value ['i18n']))
            {
              foreach( Configure::read( 'Config.languages') as $locale)
              {
                $data ['value'][$locale] = $value ['default'];
              }
            }
            else
            {
              $data ['value'] = $value ['default'];
            }
            
          }
          
          $this->Setting->create();
          $this->Setting->saveAll( $data);
        }
      }
    }
  }
}
