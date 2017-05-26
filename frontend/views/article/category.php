<?php
$this->outer('/layouts/html', [
    'jump'  => 'article_article',
    'title' => $title = "{$category->name} {$content->name}",
], '__Chalk__core');
?>
<?php $this->block('primary') ?>

<h1><?= $title ?></h1>

<?= $this->inner('article-list') ?>