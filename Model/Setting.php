<?php

class Setting extends SettingAppModel 
{
  public $name = 'Setting';
  
  public $admin = array(
      'nameSingular' => 'Configuración',
      'namePlural' => 'Configuración',
      'batchProcess' => false,
      'actionButtons' => true,
      'fieldsIndex' => array(
          'plugin',
          'model',
          'value'
      ),
      'fieldsSearch' => array(
          'plugin',
          'model',
          'value'
      )
  );
  
  public $fields = array(
      'value' => array(
          'title' => 'Valor'
      ),
  );
}
?>