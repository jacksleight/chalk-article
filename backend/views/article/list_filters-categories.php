<?= $this->render('/element/form-input', array(
    'type'          => 'dropdown_multiple',
    'entity'        => $index,
    'name'          => 'categories',
    'icon'          => 'icon-folder',
    'placeholder'   => 'Categories',
    'values'        => $this->em($this->module->name('category'))->all(),
), 'core') ?>