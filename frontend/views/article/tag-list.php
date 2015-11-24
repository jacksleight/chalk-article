<?php
$tags = $this->em($this->ck->module->name('article'))->tags();
?>
<ul>
    <?php foreach ($tags as $tag) { ?>
        <li>
            <a href="<?= $this->ck->url->route([
                'tag' => $tag[0]['slug'],
            ], $this->ck->module->name('main_tag'), true) ?>">
                <?= $tag[0]['name'] ?>
                (<?= $tag['contentCount'] ?>)
            </a>
        </li>
    <?php } ?>
</ul>