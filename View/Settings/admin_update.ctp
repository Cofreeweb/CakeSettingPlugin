<? $this->AdminNav->setActionButtons( array( 'index')) ?>
<? $this->AdminNav->setTitle( '<i class="icon-edit"></i> '. __d( 'admin', 'Configuración') .' / '. __d( 'admin', 'Editar')) ?>

<?= $this->Form->localeNav( $translated)?>

<div class="row">
  <?= $this->Form->create( 'Setting', array(
      'class' => 'form-horizontal',
      'role' => 'form'
  )) ?>
  <div class="col-md-8">
    <?= $this->Form->hidden( 'Setting.id') ?>
      
    <?= $this->Form->input( 'Setting.value', array(
        'type' => 'text',
        'label' => __( "Título"),
        'colsInput' => 8,
    )) ?>
    
    <?= $this->Form->submit( __( "Guardar")) ?>
  </div>
  <?= $this->Form->end() ?>
</div>
