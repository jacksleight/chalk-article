<?php
if ($content->isNew() && !isset($content->author)) {
	$content->author = $this->session->data('__Chalk\Backend')->user;
}
?>
<?= $this->render('/element/form-item', array(
	'type'		=> 'textarea',
	'entity'	=> $content,
	'name'		=> 'body',
	'label'		=> 'Content',
	'class'		=> 'monospaced editor-content',
	'rows'		=> 20,
), 'core') ?>
<?= $this->render('/element/form-item', array(
	'type'		=> 'input_content',
	'entity'	=> $content,
	'name'		=> 'image',
	'label'		=> 'Image',
	'filters'	=> 'core_image',
), 'core') ?>
<?= $this->render('/element/form-item', array(
	'type'		=> 'textarea',
	'entity'	=> $content,
	'name'		=> 'summary',
	'label'		=> 'Summary',
	'class'		=> 'monospaced editor-content',
	'rows'		=> 5,
), 'core') ?>