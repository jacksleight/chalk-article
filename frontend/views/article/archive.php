<?php
$formats = [
    'day'   => 'jS F Y',
    'month' => 'F Y',
    'year'  => 'Y',
];
$title  = "{$this->ck->date($archive->date, $formats[$archive->type])} {$content->name}";
$config = $this->chalk->config->layoutScripts;
$layout = $config[0] . "/default";
$this->outer($layout, [
    'title' => $title,
], $config[1]);
?>
<?php $this->block('primary') ?>

<h1><?= $title ?></h1>

<?= $this->inner('article-list') ?>