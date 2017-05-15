<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Article\Backend\Controller;

use Chalk\App as Chalk;
use Chalk\Core\Backend\Controller\Crud;
use Coast\Request;
use Coast\Response;

class Category extends Crud
{
    protected $_entityClass = 'Chalk\Article\Category';
}