<script type="application/ld+json"><?= json_encode([
    '@context'      => 'http://schema.org/',
    '@type'         => 'Article',
    'headline'      => $article->name,
    'datePublished' => $article->publishDate->format(DATE_ISO8601),
    'description'   => $article->description($this->ck->module->option('extractLength')),
    'image'         => isset($article->image) ? $this->url->file($article->image->file)->toString() : null,
]) ?></script>