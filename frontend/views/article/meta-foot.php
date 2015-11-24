<dl>
    <?php if (isset($article->author)) { ?>
        <dt>Author</dt>
        <dd><?= $article->author->name ?></dd>
    <?php } ?>
    <?php if (count($article->categories)) { ?>
        <dt>Categories</dt>
        <dd>
            <?php foreach ($article->categories as $i => $category) { ?>
                <a href="<?= $this->ck->url->route([
                    'category' => $category->slug,
                ], $this->ck->module->name('main_category'), true) ?>"><?= $category->name ?></a><?= $i < count($article->categories) - 1 ? ', ' : null ?>
            <?php } ?>
        </dd>
    <?php } ?>
    <?php if (count($article->tags)) { ?>
        <dt>Tags</dt>
        <dd>
            <?php foreach ($article->tags as $i => $tag) { ?>
                <a href="<?= $this->ck->url->route([
                    'tag' => $tag->slug,
                ], $this->ck->module->name('main_tag'), true) ?>"><?= $tag->name ?></a><?= $i < count($article->tags) - 1 ? ', ' : null ?>
            <?php } ?>
        </dd>
    <?php } ?>
    <dt>Published</dt>
    <dd><?= $this->ck->date($article->publishDate, 'jS F Y') ?></dd>
</dl>