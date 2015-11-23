<?php
$title  = "{$article->name}";
$config = $this->chalk->config->layoutScripts;
$layout = $config[0] . "/default";
$this->outer($layout, [
    'title' => $title,
    'metas' => [
        'description' => $article->description,
    ],
], $config[1]);
?>
<?php $this->block('primary') ?>

<?= $this->render('article', [
    'article' => $article,
]) ?>