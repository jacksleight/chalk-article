<article>
    <header>
        <h2><a href="<?= $url = $this->ck->url($article) ?>"><?= $article->name ?></a></h2>
        <p><small>
            <?= $article->publishDate->setTimezone(new DateTimezone($this->chalk->config->timezone))->format('jS F Y') ?> —
            <?= $article->author->name ?>
        </small></p>
    </header>
    <p>
        <?= $article->description ?>
        <a href="<?= $url ?>" title="<?= $article->name ?>">Read More</a>
    </p>
</article>