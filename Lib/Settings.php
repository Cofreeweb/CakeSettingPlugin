<?php
/**
* Settings
*
* Se encarga de manejar las configuraciones de la Aplicación y de los Plugins
*/

class Settings
{
  protected static $_data = array();
  
  protected static $_config = array();
  
  protected static $_defaults = array(
      'i18n' => false,
      'default' => false
  );
  
/**
 * Lee una configuración, dado un path tipo Plugin.Object.key
 * Devuelve la configuración que ha sido registrada desde la base de datos
 * Si no la encuentra, la tomará de la configuración por defecto
 *
 * @param string $var 
 * @return string
 * @example Settings::read( 'Acl.Users.forgotPasswordTitle')
 */
  public function read( $var)
  {
    if( empty( self::$_config))
    {
      self::readConfig();
    }
    
    if( empty( self::$_data))
    {
      self::readData();
    }
    
    $return = Hash::get( self::$_data, $var);

    if( empty( $return))
    {
      $return = Hash::get( self::$_config, $var .'.default');
    }
    
    return $return;
  }
  
  public function getConfig( $var)
  {
    if( is_array( $var))
    {
      $path = $var ['Setting']['plugin'] .'.'. $var ['Setting']['model'] .'.'. $var ['Setting']['key'];
      return Hash::get( self::$_config, $path);
    }
  }
  
  public function readConfig()
  {
    $path = APP . 'Config' .DS. 'settings.php';

    if( file_exists( $path))
    {
      Configure::load( 'settings', 'default', false);
      $data = Configure::read( 'SettingConfig');
      self::$_config ['App'] = self::setDefaults( $data);
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
          self::$_config [$plugin] = self::setDefaults( $data);
        }
      }
    }
  }
  
  public function setDefaults( $data)
  {
    foreach( $data as $model => $values)
    {
      foreach( $values as $key => $value)
      {
        $data [$model][$key] = array_merge( self::$_defaults, $value);
      }
    }
    
    return $data;
  }
  
  public function readData()
  {    
    $SettingModel = ClassRegistry::init( 'Setting.Setting');
    
    $results = $SettingModel->find( 'all', array(
        'order' => array(
            'Setting.plugin',
            'Setting.model',
            'Setting.key'
        )
    ));
    
    foreach( $results as $result)
    {
      $data [$result ['Setting']['plugin']][$result ['Setting']['model']][$result ['Setting']['key']] = $result ['Setting']['value'];
    }
    
    $SettingModel->Behaviors->load( 'Translate', array(
        'value' => 'Values'
    ));
    
    $results = $SettingModel->find( 'all', array(
        'order' => array(
            'Setting.plugin',
            'Setting.model',
            'Setting.key'
        )
    ));
    
    foreach( $results as $result)
    {
      $data [$result ['Setting']['plugin']][$result ['Setting']['model']][$result ['Setting']['key']] = $result ['Setting']['value'];
    }
    
    $SettingModel->Behaviors->unload( 'Translate');
    
    self::$_data = $data;
  }
}
