<article>
    <header>
        <h2><?= $article->name ?></h2>
        <p><small>
            <?= $article->publishDate->setTimezone(new DateTimezone($this->chalk->config->timezone))->format('jS F Y') ?> —
            <?= $article->author->name ?>
        </small></p>
    </header>
    <?= $article->body ?>
</article>