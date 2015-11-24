<?php foreach ($articles as $article) { ?>
    <?= $this->render('article-summary', [
        'article' => $article,
    ]) ?>
<?php } ?>
<ul>
    <?php if ($pagination->page > 1) { ?>
        <li><a href="<?= $this->url->query(['page' => $pagination->page - 1]) ?>">Previous</a></li>
    <?php } ?>
    <?php if ($pagination->page < $pagination->pages) { ?>
        <li><a href="<?= $this->url->query(['page' => $pagination->page + 1]) ?>">Next</a></li>
    <?php } ?>
</ul>