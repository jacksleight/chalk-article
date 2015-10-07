<?php $this->outer('/layouts/page/default', [
    'title' => $content->name,
]) ?>
<?php $this->block('primary') ?>

<h1><?= $content->name ?></h1>
