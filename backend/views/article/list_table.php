<?php
$this->params([
    'tableCols' => $tableCols = (isset($tableCols) ? $tableCols : []) + [
        [
            'label'    => 'Categories',
            'class'    => 'col-right col-contract',
            'partial'  => 'categories',
            'sort'     => 70,
        ],
        'date' => [
            'label'   => 'Published',
            'class'   => 'col-right col-contract',
            'partial' => 'date',
            'params'  => ['name' => 'publish'],
            'sort'    => 80,
        ],
    ],
]);
?>
<?= $this->parent() ?>