<article>
    <header>
        <h2><a href="<?= $url = $this->ck->url($article) ?>"><?= $article->name ?></a></h2>
        <p><small>
            <?= $this->inner('meta') ?>
        </small></p>
    </header>
    <p>
        <?= $article->description($this->ck->module->option('extractLength')) ?>
        <a href="<?= $url ?>" title="<?= $article->name ?>">→</a>
    </p>
</article>