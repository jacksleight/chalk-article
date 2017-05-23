<?php
$this->params([
    'tableCols' => $tableCols = (isset($tableCols) ? $tableCols : []) + [
        'name' => [
            'label'   => 'Name',
            'partial' => 'preview',
            'sort'    => 10,
        ],
    ],
]);
?>
<?= $this->parent() ?>