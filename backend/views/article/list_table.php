<?php
$this->params([
    'tableCols' => $tableCols = isset($tableCols) ? $tableCols : [
        // [
        //     'label'    => 'Categories',
        //     'class'    => 'col-contract',
        //     'partial'  => 'categories',
        //     'style'    => 'max-width: 300px;',
        // ],
        [
            'label'   => 'Published',
            'class'   => 'col-right col-contract',
            'partial' => 'date',
            'params'  => ['property' => 'publish'],
        ],
    ],
]);
?>
<?= $this->parent() ?>