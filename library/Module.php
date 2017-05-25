<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Article;

use Chalk\Backend;
use Chalk\Chalk;
use Chalk\Event;
use Chalk\Info;
use Chalk\Nav;
use Chalk\Module as ChalkModule;
use Closure;
use Coast\Request;
use Coast\Response;
use Coast\Router;
use Coast\Sitemap;

class Module extends ChalkModule
{   
    const VERSION = '0.6.1';

    protected $_options = [
        'indexLimit'    => 5,
        'searchLimit'   => 10,
        'archiveLimit'  => 10,
        'categoryLimit' => 10,
        'tagLimit'      => 10,
        'feedLimit'     => 10,
        'extractLength' => 60,
    ];

    protected $_nodes = [];

    public function init()
    {
        $this
            ->entityDir($this->name());
    }
    
    public function frontendInit()
    {
        $this
            ->frontendControllerNspace($this->name())
            ->frontendViewDir($this->name());

        $this
            ->frontendResolver($this->name('article'), function($article, $info) {
                if (!count($this->_nodes)) {
                    return false;
                }
                $date = $this->frontend->date(isset($article->publishDate) ? $article->publishDate : new \DateTime('today'));
                return $this->frontend->url->route([
                    'year'      => $date->format('Y'),
                    'month'     => $date->format('m'),
                    'day'       => $date->format('d'),
                    'article'   => $article->slug,
                ], $this->name("main_view"), true);
            });

        if ($sitemap = $this->app->module('Chalk\Sitemap')) {
            $this
                ->frontendHookListen($sitemap->name('xml'), function(Sitemap $sitemap) {
                    if (!count($this->_nodes)) {
                        return $sitemap;
                    }
                    $articles = $this->em($this->name('article'))->all();
                    if (!count($articles)) {
                        return $sitemap;
                    }
                    $sitemap->urls[] = (new Sitemap\Url())->fromArray([
                        'url'        => $this->frontend->url->route([], $this->name('main'), true),
                        'modifyDate' => $articles[0]['modifyDate'],
                    ]);
                    foreach ($articles as $article) {
                        $sitemap->urls[] = (new Sitemap\Url())->fromArray([
                            'url'        => $this->frontend->url($article),
                            'modifyDate' => $article['modifyDate'],
                        ]);
                    }
                    $categories = $this->em($this->name('article'))->categories();
                    foreach ($categories as $category) {

                        $sitemap->urls[] = (new Sitemap\Url())->fromArray([
                            'url'        => $this->frontend->url->route(['category' => $category[0]['slug']], $this->name('main_category'), true),
                            'modifyDate' => $articles[0]['modifyDate'],
                        ]);
                    }
                    $tags = $this->em($this->name('article'))->tags();
                    foreach ($tags as $tag) {
                        $sitemap->urls[] = (new Sitemap\Url())->fromArray([
                            'url'        => $this->frontend->url->route(['tag' => $tag[0]['slug']], $this->name('main_tag'), true),
                            'modifyDate' => $articles[0]['modifyDate'],
                        ]);
                    }
                    return $sitemap;
                });
        }
    }
    
    public function backendInit()
    {
        $this
            ->backendControllerNspace($this->name())
            ->backendViewDir($this->name());

        $this
            ->backendRoute(
                $this->name('index'),
                Router::METHOD_ALL,
                $this->path("{controller}?/{action}?/{id}?"), [
                    'group'      => $this->name(),
                    'controller' => 'index',
                    'action'     => 'index',
                    'id'         => null,
                ]);

        $this
            ->backendHookListen('core_contentList', function(Info $list) {
                if ($list->filter() == 'core_link') {
                    $list
                        ->item($this->name('article'), []);
                }
                return $list;
            })
            ->backendHookListen('core_nav', function(Nav $list) {
                $list
                    ->entity($this->name('article'), [], 'core_site')
                    ->entity($this->name('category'), [], $this->name('article'));
                return $list;
            });
    }

    public function core_frontendInitNode($name, $node, $content, $params)
    {
        $this->_nodes[] = $node;
        switch ($name) {
            case 'main':
                $primary = $this->name("main");
                $this
                    ->frontendRoute(
                        "{$primary}",
                        Router::METHOD_ALL,
                        "{$node['path']}",
                        $params + [
                            'controller' => "article",
                            'action'     => 'index',
                        ])
                    ->frontendRoute(
                        "{$primary}_search",
                        Router::METHOD_ALL,
                        "{$node['path']}/search",
                        $params + [
                            'controller' => "article",
                            'action'     => 'search',
                        ])
                    ->frontendRoute(
                        "{$primary}_feed",
                        Router::METHOD_ALL,
                        "{$node['path']}/feed",
                        $params + [
                            'controller' => "article",
                            'action'     => 'feed',
                        ])
                    ->frontendRoute(
                        "{$primary}_category",
                        Router::METHOD_ALL,
                        "{$node['path']}/categories/{category}",
                        $params + [
                            'controller' => "article",
                            'action'     => 'category',
                        ])
                    ->frontendRoute(
                        "{$primary}_tag",
                        Router::METHOD_ALL,
                        "{$node['path']}/tags/{tag}",
                        $params + [
                            'controller' => "article",
                            'action'     => 'tag',
                        ])
                    ->frontendRoute(
                        "{$primary}_archive",
                        Router::METHOD_ALL,
                        "{$node['path']}/{year:\d+}/{month:\d+}?/{day:\d+}?",
                        $params + [
                            'controller' => "article",
                            'action'     => 'archive',
                        ])
                    ->frontendRoute(
                        "{$primary}_view",
                        Router::METHOD_ALL,
                        "{$node['path']}/{year:\d+}/{month:\d+}/{day:\d+}/{article}",
                        $params + [
                            'controller' => "article",
                            'action'     => 'view',
                        ]);
                return $primary;
            break;
        } 
    }
}