<p><small>
    <?= $this->ck->date(isset($article->publishDate) ? $article->publishDate : new \DateTime('today'))->format('jS F Y') ?>
</small></p>