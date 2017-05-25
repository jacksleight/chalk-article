<?php
$this->params([
    'filterFields' => $filterFields = (isset($filterFields) ? $filterFields : []) + [
        'categories' => [
            'class'   => 'flex-2',
            'partial' => 'categories',
            'sort'    => 70,
        ],
        // 'dateMin' => [
        //     'class'   => 'flex-2',
        //     'partial' => 'date-min',
        //     'params'  => ['property' => 'publish', 'placeholder' => 'Published'],
        //     'sort'    => 80,
        // ],
    ],
]);
?>
<?= $this->parent() ?>