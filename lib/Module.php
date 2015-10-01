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
            ->entityDir($this->name(), 'lib');
    }
    
    public function frontendInit()
    {
        $this
            ->frontendControllerNspace($this->name())
            ->frontendViewDir($this->name());
    }
    
    public function backendInit()
    {
        $this
            ->backendControllerNspace($this->name())
            ->backendViewDir($this->name());

        $this
            ->backendHookListen('core_contentList', function(InfoList $list) {
                if ($list->filter() == 'core_main') {
                    return $list
                        ->item($this->name('article'), []);
                } else if ($list->filter() == 'core_link') {
                    return $list
                        ->item($this->name('article'), []);
                }
            });
    }

    public function core_frontendInitNode($name, $node, $content, $params)
    {
        switch ($name) {
            case 'list':
                $primary = $this->name("list_{$content['id']}");
                $this
                    ->frontendRoute(
                        $primary,
                        Router::METHOD_ALL,
                        "{$node['path']}",
                        $params + [
                            'controller' => "article",
                            'action'     => 'index',
                        ]);
                return $primary;
            break;
        } 
    }
}