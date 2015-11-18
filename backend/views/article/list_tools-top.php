<li>
    <a href="<?= $this->url([
        'controller' => 'category',
        'action'     => 'index',
    ], $this->module()->name('index'), true) ?>" class="btn btn-light icon-folder" rel="modal">
        Manage Categories
    </a>
</li>