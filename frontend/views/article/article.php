<article>
    <header>
        <h1><?= $article->name ?></h1>
        <p><small>
            <?= $this->inner('meta') ?>
        </small></p>
    </header>
    <?= $article->body ?>
    <?= $this->inner('json-ld') ?>
</article>