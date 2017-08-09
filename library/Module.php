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
    const NAME    = 'article';
    const VERSION = '0.7.1';

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
            ->entityDir('article');
    }
    
    public function frontendInit()
    {
        $this
            ->frontendControllerNspace('article')
            ->frontendViewDir('article');

        $this
            ->frontendResolver('article_article', function($article, $info) {
                if (!count($this->_nodes)) {
                    return false;
                }
                $date = $this->frontend->date(isset($article->publishDate) ? $article->publishDate : new \DateTime('today'));
                return $this->frontend->url->route([
                    'year'      => $date->format('Y'),
                    'month'     => $date->format('m'),
                    'day'       => $date->format('d'),
                    'article'   => $article->slug,
                ], 'article_main_view', true);
            });

        if ($this->app->module('sitemap')) {
            $this
                ->frontendHookListen('sitemap_xml', function(Sitemap $sitemap) {
                    if (!count($this->_nodes)) {
                        return $sitemap;
                    }
                    $articles = $this->em('article_article')->all();
                    if (!count($articles)) {
                        return $sitemap;
                    }
                    $sitemap->urls[] = (new Sitemap\Url())->fromArray([
                        'url'        => $this->frontend->url->route([], 'article_main', true),
                        'updateDate' => $articles[0]['updateDate'],
                    ]);
                    foreach ($articles as $article) {
                        $sitemap->urls[] = (new Sitemap\Url())->fromArray([
                            'url'        => $this->frontend->url($article),
                            'updateDate' => $article['updateDate'],
                        ]);
                    }
                    $categories = $this->em('article_article')->categories();
                    foreach ($categories as $category) {

                        $sitemap->urls[] = (new Sitemap\Url())->fromArray([
                            'url'        => $this->frontend->url->route(['category' => $category[0]['slug']], 'article_main_category', true),
                            'updateDate' => $articles[0]['updateDate'],
                        ]);
                    }
                    $tags = $this->em('article_article')->tags();
                    foreach ($tags as $tag) {
                        $sitemap->urls[] = (new Sitemap\Url())->fromArray([
                            'url'        => $this->frontend->url->route(['tag' => $tag[0]['slug']], 'article_main_tag', true),
                            'updateDate' => $articles[0]['updateDate'],
                        ]);
                    }
                    return $sitemap;
                });
        }
    }
    
    public function backendInit()
    {
        $this
            ->backendControllerNspace('article')
            ->backendViewDir('article');

        $this
            ->backendRoute(
                'article_index',
                Router::METHOD_ALL,
                $this->path("{controller}?/{action}?/{id}?"), [
                    'group'      => 'article',
                    'controller' => 'index',
                    'action'     => 'index',
                    'id'         => null,
                ]);

        $this
            ->backendHookListen('core_contentList', function(Info $list) {
                if ($list->filter() == 'core_info_link') {
                    $list
                        ->item('article_article', []);
                }
                return $list;
            })
            ->backendHookListen('core_nav', function(Nav $list) {
                $list
                    ->entity('article_article', [], 'core_site')
                    ->entity('article_category', [], 'article_article');
                return $list;
            });
    }

    public function core_frontendInitNode($name, $node, $content, $params)
    {
        $this->_nodes[] = $node;
        switch ($name) {
            case 'main':
                $primary = 'article_main';
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