<?= $this->render('/element/form-item', array(
	'type'		=> 'textarea',
	'entity'	=> $content,
	'name'		=> 'summary',
	'label'		=> 'Summary',
	'class'		=> 'monospaced editor-content',
	'rows'		=> 7,
), 'core') ?>
<?= $this->render('/element/form-item', array(
	'type'		=> 'textarea',
	'entity'	=> $content,
	'name'		=> 'body',
	'label'		=> 'Body',
	'class'		=> 'monospaced editor-content',
	'rows'		=> 20,
), 'core') ?>