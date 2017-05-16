<?php if (count($entity->categories)) { ?>
    <?= implode(', ', array_map(function($category) {
        return $category->name;
    }, $entity->categories->toArray())) ?>
<?php } else { ?>
    —
<?php } ?>