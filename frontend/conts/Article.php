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
use Chalk\Repository;

class Article extends Delegate
{
    public function index(Request $req, Response $res)
    {
        $limit = $this->module->option('indexLimit');
        $page  = $req->page ?: 1;
        $articles = $this->em($this->module->name('article'))->all([
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
        $articles = $this->em($this->module->name('article'))->all([
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
        $category = $this->em($this->module->name('category'))->slug($req->category);

        $limit = $this->module->option('categoryLimit');
        $page  = $req->page ?: 1;
        $articles = $this->em($this->module->name('article'))->all([
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
        $articles = $this->em($this->module->name('article'))->all([
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
        $article = $this->em($this->module->name('article'))->one([
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
        $articles = $this->em($this->module->name('article'))->all([
            'limit' => $this->module->option('feedLimit'),
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
                $this->app->date($article->publishDate),
                $article->description($this->module->option('extractLength')),
                $this->parser->parse($article->body));
        }

        return $res->xml($feed->toXml(), 'atom');
    }
}