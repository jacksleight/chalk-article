<?php
$this->outer('/layouts/html', [
    'title' => $title = "{$article->name}",
    'metas' => [
        'description' => $article->description($this->ck->module->option('extractLength')),
    ],
], '__Chalk__core');
?>
<?php $this->block('primary') ?>

<?= $this->render('article', [
    'article' => $article,
]) ?>