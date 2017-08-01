<?php
$tags = $this->em('article_article')->tags();
?>
<ul>
    <?php foreach ($tags as $tag) { ?>
        <li>
            <a href="<?= $this->ck->url->route([
                'tag' => $tag[0]['slug'],
            ], 'article_main_tag', true) ?>">
                <?= $tag[0]['name'] ?>
                (<?= $tag['contentCount'] ?>)
            </a>
        </li>
    <?php } ?>
</ul>