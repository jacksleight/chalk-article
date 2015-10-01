<?php $this->outer('/layouts/page/default', [
    'title' => $page->name,
]) ?>
<?php $this->block('primary') ?>

<h1><?= $page->name ?></h1>
