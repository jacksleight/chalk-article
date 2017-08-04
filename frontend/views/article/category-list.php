<?php
$categories = $this->em('article_article')->categories();
?>
<ul>
    <?php foreach ($categories as $category) { ?>
        <li>
            <a href="<?= $this->ck->url->route([
                'category' => $category[0]['slug'],
            ], 'article_main_category', true) ?>">
                <?= $category[0]['name'] ?>
                (<?= $category['contentCount'] ?>)
            </a>
        </li>
    <?php } ?>
</ul>