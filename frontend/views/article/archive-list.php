<?php
$years = $this->em($this->ck->module->name('article'))->publishYears();
?>
<ul>
    <?php foreach ($years as $year) { ?>
        <li>
            <a href="<?= $this->ck->url->route([
                'year' => $year['year'],
            ], $this->ck->module->name('main_archive'), true) ?>">
                <?= $year['date']->format('Y') ?>
                (<?= $year['contentCount'] ?>)
            </a>
            <ul>
                <?php foreach ($year['months'] as $month) { ?>
                    <li><a href="<?= $this->ck->url->route([
                        'year'  => $month['year'],
                        'month' => $month['month'],
                    ], $this->ck->module->name('main_archive'), true) ?>">
                        <?= $month['date']->format('F') ?>
                        (<?= $month['contentCount'] ?>)
                    </a></li>
                <?php } ?>
            </ul>
        </li>
    <?php } ?>
</ul>