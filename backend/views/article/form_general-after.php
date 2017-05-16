<fieldset class="form-block">
	<div class="form-legend">
		<h2>Organise</h2>
	</div>
	<div class="form-items">
		<?= $this->render('/element/form-item', array(
			'entity'	=> $entity,
			'name'		=> 'categories',
			'label'		=> 'Categories',
		    'values'    => $this->em($this->module->name('category'))->all(),
		), 'core') ?>
		<?= $this->render('/element/form-item', array(
		    'type'      => 'input_tag',
			'entity'	=> $entity,
			'name'		=> 'tagsText',
			'label'		=> 'Tags',
		    'values'    => $this->em('core_tag')->all(),
		), 'core') ?>
		<?= $this->render('/element/form-item', array(
			'entity'	=> $entity,
			'name'		=> 'author',
			'label'		=> 'Author',
			'type'		=> 'select',
			'null'		=> 'None',
			'values'	=> $this->em('core_user')->all(),
		), 'core') ?>
	</div>
</fieldset>
