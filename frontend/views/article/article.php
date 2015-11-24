<article>
    <header>
        <h1><?= $article->name ?></h1>
        <?= $this->inner('meta-head') ?>
    </header>
    <?= $article->body ?>
    <footer>
        <?= $this->inner('meta-foot') ?>
    </footer>
    <?= $this->inner('json-ld') ?>
</article>