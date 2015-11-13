<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Article\Backend\Controller;

use Chalk\App as Chalk;
use Chalk\Controller\Basic;
use Coast\Request;
use Coast\Response;

class Category extends Basic
{
    protected $_entityClass = 'Chalk\Article\Category';
}