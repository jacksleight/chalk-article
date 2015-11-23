<?= $article->publishDate->setTimezone(new DateTimezone($this->chalk->config->timezone))->format('jS F Y') ?> –
<?= $article->author->name ?>