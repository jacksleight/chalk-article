<?= $this->render('/element/form-item', array(
	'type'		=> 'textarea',
	'entity'	=> $entity,
	'name'		=> 'body',
	'label'		=> 'Content',
	'class'		=> 'monospaced editor-content',
	'rows'		=> 20,
), 'core') ?>
<?= $this->render('/element/form-item', array(
	'type'		=> 'input_content',
	'entity'	=> $entity,
	'name'		=> 'image',
	'label'		=> 'Image',
	'filters'	=> 'core_image',
), 'core') ?>
<?= $this->render('/element/form-item', array(
	'type'		=> 'textarea',
	'entity'	=> $entity,
	'name'		=> 'summary',
	'label'		=> 'Summary',
	'class'		=> 'monospaced editor-content',
	'rows'		=> 5,
), 'core') ?>