<?php

class SettingsController extends SettingAppController 
{

  public $name = 'Settings';  
  
  
  public function admin_update( $id)
  {
    $this->Setting->id = $id;

    $result = $this->Setting->read();
    
    $config = Settings::getConfig( $result);
    
    $this->set( 'translated', false);
    
    if( $config ['i18n'])
    {
      $this->Setting->Behaviors->load( 'Translate', array(
          'value' => 'Values'
      ));
      
      $result = $this->Setting->find( 'first', array(
          'conditions' => array(
              'Setting.id' => $id
          ),
          'recursive' => 1
      ));
      
      $this->set( 'translated', true);
    }

    if( $this->request->is( 'put'))
    {
      if( $this->Setting->saveAll( $this->request->data, array( 'validate' => 'first', 'atomic' => true, 'deep' => true))) 
      {
        $this->Setting->set( $result);
        $this->Manager->flashSuccess( __d( 'admin', 'El contenido se ha guardado correctamente'));
        $this->redirect( array(
            'admin' => false,
            'plugin' => 'management',
            'controller' => 'crud',
            'action' => 'update',
            'model' => 'Setting.Setting',
            $id
        ));
      } 
      else 
      {
        $this->Manager->flashError( __d( 'admin', 'No ha podido guardarse el contenido'));
      }
    }
    else 
    {
      $this->request->data = $result;
    }
  }
  
}
?>