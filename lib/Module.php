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

class Module extends ChalkModule
{   
    const VERSION = '0.5.0';

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
                return $this->frontend->url->route([
                    'year'      => $article->publishDate->format('Y'),
                    'month'     => $article->publishDate->format('m'),
                    'day'       => $article->publishDate->format('d'),
                    'article'   => $article->slug,
                ], $this->name("main_view"), true);
            });
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