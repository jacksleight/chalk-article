<fieldset class="form-block">
	<div class="form-legend">
		<h2>Organise</h2>
	</div>
	<div class="form-items">
		<?= $this->render('/element/form-item', array(
			'entity'	=> $content,
			'name'		=> 'categories',
			'label'		=> 'Categories',
		    'values'    => $this->em('article_category')->all(),
		), 'core') ?>
		<?= $this->render('/element/form-item', array(
		    'type'      => 'input_tag',
			'entity'	=> $content,
			'name'		=> 'tagsText',
			'label'		=> 'Tags',
		    'values'    => $this->em('core_tag')->all(),
		), 'core') ?>
		<?= $this->render('/element/form-item', array(
			'entity'	=> $content,
			'name'		=> 'author',
			'label'		=> 'Author',
			'type'		=> 'select',
			'null'		=> 'None',
			'values'	=> $this->em('core_user')->all(),
		), 'core') ?>
	</div>
</fieldset>
