<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Article\Frontend\Controller;

use Chalk\Chalk;
use Chalk\Core\Controller\Delegate;
use Coast\Request;
use Coast\Response;
use Coast\Feed;
use Chalk\Repository;

class Article extends Delegate
{
    public function index(Request $req, Response $res)
    {
        $limit = $this->module->option('indexLimit');
        $page  = $req->page ?: 1;
        $articles = $this->em('article_article')->all([
            'limit' => $limit,
            'page'  => $page,
        ], [], Repository::FETCH_ALL_PAGED);

        $req->view->articles   = $articles;
        $req->view->pagination = (object) [
            'limit' => $limit,
            'page'  => (int) $page,
            'pages' => (int) ceil($articles->count() / $limit),
        ];
    }

    public function search(Request $req, Response $res)
    {
        $limit = $this->module->option('searchLimit');
        $page  = $req->page ?: 1;
        $articles = $this->em('article_article')->all([
            'limit'  => $limit,
            'page'   => $page,
            'search' => $req->query,
        ], [], Repository::FETCH_ALL_PAGED);

        $req->view->articles   = $articles;
        $req->view->pagination = (object) [
            'limit' => $limit,
            'page'  => (int) $page,
            'pages' => (int) ceil($articles->count() / $limit),
        ];
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

        $limit = $this->module->option('archiveLimit');
        $page  = $req->page ?: 1;
        $articles = $this->em('article_article')->all([
            'publishDateMin' => $min,
            'publishDateMax' => $max,
            'limit'          => $limit,
            'page'           => $page,
        ], [], Repository::FETCH_ALL_PAGED);

        $req->view->articles   = $articles;
        $req->view->archive    = (object) [
            'type' => $type,
            'date' => $min,
            'min'  => $min,
            'max'  => $max,
        ];
        $req->view->pagination = (object) [
            'limit' => $limit,
            'page'  => (int) $page,
            'pages' => (int) ceil($articles->count() / $limit),
        ];
    }

    public function category(Request $req, Response $res)
    {
        $category = $this->em('article_category')->slug($req->category);

        $limit = $this->module->option('categoryLimit');
        $page  = $req->page ?: 1;
        $articles = $this->em('article_article')->all([
            'categories' => [$category],
            'limit'      => $limit,
            'page'       => $page,
        ], [], Repository::FETCH_ALL_PAGED);

        $req->view->articles   = $articles;
        $req->view->category   = $category;
        $req->view->pagination = (object) [
            'limit' => $limit,
            'page'  => (int) $page,
            'pages' => (int) ceil($articles->count() / $limit),
        ];
    }

    public function tag(Request $req, Response $res)
    {
        $tag = $this->em('core_tag')->slug($req->tag);

        $limit = $this->module->option('tagLimit');
        $page  = $req->page ?: 1;
        $articles = $this->em('article_article')->all([
            'tags'  => [$tag],
            'limit' => $limit,
            'page'  => $page,
        ], [], Repository::FETCH_ALL_PAGED);

        $req->view->articles   = $articles;
        $req->view->tag        = $tag;
        $req->view->pagination = (object) [
            'limit' => $limit,
            'page'  => (int) $page,
            'pages' => (int) ceil($articles->count() / $limit),
        ];
    }

    public function view(Request $req, Response $res)
    {
        $article = $this->em('article_article')->one([
            'slugs'       => [$req->article],
            'isPublished' => isset($this->session->data('__Chalk\Backend')->user) ? null : true,
        ]);
        if (!$article) {
            return false;
        }

        $req->view->article = $article;
    }

    public function feed(Request $req, Response $res)
    {
        $articles = $this->em('article_article')->all([
            'limit' => $this->module->option('feedLimit'),
        ]);

        $feed = new Feed();
        $feed->fromArray([
            'id'         => $this->url->route([], 'article_main', true),
            'url'        => $this->url->route([], 'article_main', true),
            'urlFeed'    => $this->url->route([], 'article_main_feed', true),
            'title'      => "{$this->home['name']} {$req->content->name}",
            'updateDate' => count($articles) ? $articles[0]->publishDate : null,
            'author'     => ['name' => $this->domain['label']],
        ]);
        foreach ($articles as $article) {
            $feed->items[] = (new Feed\Item())->fromArray([
                'id'         => $this->url($article),
                'url'        => $this->url($article),
                'title'      => $article->name,
                'author'     => isset($article->author) ? ['name' => $article->author->name] : null,
                'updateDate' => $this->app->date($article->publishDate),
                'summary'    => ['content' => $article->description($this->module->option('extractLength'))],
                'body'       => ['content' => $this->parser->parse($article->body)],
            ]);
        }
        return $res->xml($feed->toXml(), 'atom');
    }
}