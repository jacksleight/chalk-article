<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Article\Frontend\Controller;

use Chalk\Chalk;
use Chalk\Core\Controller\Delegate;
use Coast\Request;
use Coast\Response;
use Coast\Feed;

class Article extends Delegate
{
    public function preDispatch(Request $req, Response $res)
    {
        parent::preDispatch($req, $res);

        $req->view->head .= '<link rel="alternate" type="application/atom+xml" href="' . $this->url->route([], "article_main_feed", true) . '" title="' . "{$this->home['name']} {$req->content->name}" . '">';
    }

    public function index(Request $req, Response $res)
    {
        $articles = $this->em('article_article')->all([
            'limit' => 5,
        ]);

        $req->view->articles = $articles;
    }

    public function archive(Request $req, Response $res)
    {
        $day   = $req->day   ?: 1;
        $month = $req->month ?: 1;
        $year  = $req->year;
        $min   = new \DateTime("{$year}-{$month}-{$day}");
        $max   = clone $min;
        if (isset($req->day)) {
            $type = 'day';
        } else if (isset($req->month)) {
            $type = 'month';
        } else {
            $type = 'year';
        }
        $max->modify("+1 {$type} -1 second");
        $archive = (object) [
            'type' => $type,
            'date' => $min,
            'min'  => $min,
            'max'  => $max,
        ];

        $articles = $this->em('article_article')->all([
            'publishDateMin' => $min,
            'publishDateMax' => $max,
        ]);

        $req->view->archive  = $archive;
        $req->view->articles = $articles;
    }

    public function category(Request $req, Response $res)
    {
        $category = $this->em('article_category')->slug($req->category);

        $articles = $this->em('article_article')->all([
            'categories' => [$category],
        ]);

        $req->view->category = $category;
        $req->view->articles = $articles;
    }

    public function tag(Request $req, Response $res)
    {
        $tag = $this->em('core_tag')->slug($req->tag);

        $articles = $this->em('article_article')->all([
            'tags' => [$tag],
        ]);

        $req->view->tag      = $tag;
        $req->view->articles = $articles;
    }

    public function view(Request $req, Response $res)
    {
        $min = new \DateTime("{$req->year}-{$req->month}-{$req->day}");
        $max = clone $min;
        $max->modify('+1 day -1 second');

        $article = $this->em('article_article')->one([
            'slugs'          => [$req->article],
            'publishDateMin' => $min,
            'publishDateMax' => $max,
        ]);
        if (!$article) {
            return false;
        }

        $req->view->article = $article;
    }

    public function feed(Request $req, Response $res)
    {
        $articles = $this->em('article_article')->all([
            'limit' => 10,
        ]);

        $feed = new Feed(
            "{$this->home['name']} {$req->content->name}",
            $this->url->route([], "article_main", true),
            "{$this->chalk->config->name}",
            $articles[0]->publishDate);

        foreach ($articles as $article) {
            $feed->add(
                $article->name,
                $this->url($article),
                $article->publishDate,
                $article->description,
                $this->parser->parse($article->body));
        }

        return $res->xml($feed->toXml(), 'atom');
    }
}