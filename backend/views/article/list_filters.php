<?php
$this->params([
    'filterFields' => $filterFields = isset($filterFields) ? $filterFields : [
        [
            'class'   => 'flex-2',
            'style'   => null,
            'partial' => 'categories',
        ],
    ],
]);
?>
<?= $this->parent() ?>