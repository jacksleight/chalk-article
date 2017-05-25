<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Article\Backend\Controller;

use Chalk\Chalk;
use Chalk\Core\Backend\Controller\Content as ChalkCoreContent;
use Chalk\Core\Backend\Model;
use Chalk\Entity;
use Coast\Controller\Action;
use Coast\Request;
use Coast\Response;

class Article extends ChalkCoreContent
{
    protected $_entityClass = 'Chalk\Article\Article';

    protected function _create(Request $req, Response $res, Entity $entity)
    {
        $entity->author = $this->em->ref('core_user', $req->user->id);
    }
}