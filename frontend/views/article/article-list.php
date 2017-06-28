<?php foreach ($articles as $article) { ?>
    <?= $this->render('article-summary', [
        'article' => $article,
    ]) ?>
<?php } ?>
<?= $this->partial('pagination') ?>