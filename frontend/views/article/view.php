<?php $this->outer('/layouts/page/default', [
    'title' => $article->name,
]) ?>
<?php $this->block('primary') ?>

<?= $this->render('long', [
    'article' => $article,
]) ?>