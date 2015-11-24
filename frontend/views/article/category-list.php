<?php
$categories = $this->em($this->ck->module->name('article'))->categories();
?>
<ul>
    <?php foreach ($categories as $category) { ?>
        <li>
            <a href="<?= $this->ck->url->route([
                'category' => $category[0]['slug'],
            ], $this->ck->module->name('main_category'), true) ?>">
                <?= $category[0]['name'] ?>
                (<?= $category['contentCount'] ?>)
            </a>
        </li>
    <?php } ?>
</ul>