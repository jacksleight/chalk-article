<?php
$years = $this->em('article_article')->publishYears();
?>
<ul>
    <?php foreach ($years as $year) { ?>
        <li>
            <a href="<?= $this->ck->url->route([
                'year' => $year['date']->format('Y'),
            ], 'article_main_archive', true) ?>">
                <?= $year['date']->format('Y') ?>
                (<?= $year['contentCount'] ?>)
            </a>
            <ul>
                <?php foreach ($year['months'] as $month) { ?>
                    <li><a href="<?= $this->ck->url->route([
                        'year'  => $year['date']->format('Y'),
                        'month' => $year['date']->format('m'),
                    ], 'article_main_archive', true) ?>">
                        <?= $month['date']->format('F') ?>
                        (<?= $month['contentCount'] ?>)
                    </a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
</ul>