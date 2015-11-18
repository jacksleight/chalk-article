<?php if (count($content->categories)) { ?>
    <?= implode(', ', array_map(function($category) {
        return $category->name;
    }, $content->categories->toArray())) ?>
<?php } else { ?>
    —
<?php } ?>