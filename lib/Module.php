<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Article;

use Chalk\Backend;
use Chalk\Chalk;
use Chalk\Event;
use Chalk\InfoList;
use Chalk\Module as ChalkModule;
use Closure;
use Coast\Request;
use Coast\Response;
use Coast\Router;
use Coast\Sitemap;

class Module extends ChalkModule
{   
    const VERSION = '0.5.0';

    protected $_options = [
        'indexLimit'    => 5,
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
            ->frontendUrlResolver($this->name('article'), function($article, $info) {
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
                    $sitemap->add(
                        $this->frontend->url->route([], $this->name('main'), true),
                        $articles[0]['modifyDate']
                    );
                    foreach ($articles as $article) {
                        $sitemap->add(
                            $this->frontend->url($article),
                            $article['modifyDate']
                        );
                    }
                    $categories = $this->em($this->name('article'))->categories();
                    foreach ($categories as $category) {
                        $sitemap->add(
                            $this->frontend->url->route([
                                'category' => $category[0]['slug'],
                            ], $this->name('main_category'), true),
                            $articles[0]['modifyDate']
                        );
                    }
                    $tags = $this->em($this->name('article'))->tags();
                    foreach ($tags as $tag) {
                        $sitemap->add(
                            $this->frontend->url->route([
                                'tag' => $tag[0]['slug'],
                            ], $this->name('main_tag'), true),
                            $articles[0]['modifyDate']
                        );
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
                    'group'  => $this->name(),
                    'action' => 'index',
                    'id'     => null,
                ]);

        $this
            ->backendHookListen('core_contentList', function(InfoList $list) {
                if ($list->filter() == 'core_main') {
                    $list
                        ->item($this->name('article'), []);
                } else if ($list->filter() == 'core_link') {
                    $list
                        ->item($this->name('article'), []);
                }
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