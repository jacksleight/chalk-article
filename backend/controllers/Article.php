<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Article\Backend\Controller;

use Chalk\Core\Backend\Controller\Content as ChalkCoreContent;
use Coast\Request;
use Coast\Response;

class Article extends ChalkCoreContent
{
    protected $_entityClass = 'Chalk\Article\Article';

    protected function _action_create(\Toast\Entity $entity)
    {
        $entity->author = $this->session->data('__Chalk\Backend')->user;
    }
}