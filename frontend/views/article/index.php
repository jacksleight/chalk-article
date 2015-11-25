<?php
$this->outer('/layouts/html', [
    'title' => $title = "{$content->name}",
    'links' => [
        [
            'rel'   => 'alternate',
            'type'  => 'application/atom+xml',
            'href'  => $this->ck->url->route([], $this->ck->module->name('main_feed'), true),
            'title' => "{$this->ck->home['name']} {$req->content->name}",
        ],
    ],
], '__Chalk__core');
?>
<?php $this->block('primary') ?>

<h1><?= $title ?></h1>

<?= $this->inner('article-list') ?>

<?= $this->render('archive-list') ?>
<?= $this->render('category-list') ?>
<?= $this->render('tag-list') ?>