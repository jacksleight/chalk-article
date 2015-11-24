<?php
$formats = [
    'day'   => 'jS F Y',
    'month' => 'F Y',
    'year'  => 'Y',
];
$this->outer('/layouts/html', [
    'title' => $title = "{$this->ck->date($archive->date)->format($formats[$archive->type])} {$content->name}",
], '__Chalk__core');
?>
<?php $this->block('primary') ?>

<h1><?= $title ?></h1>

<?= $this->inner('article-list') ?>