<?php $this->outer('/layouts/page/default', [
    'title' => $content->name,
]) ?>
<?php $this->block('primary') ?>

<h1><?= $tag->name ?></h1>

<?php foreach ($articles as $article) { ?>
    <?= $this->render('short', [
        'article' => $article,
    ]) ?>
<?php } ?>