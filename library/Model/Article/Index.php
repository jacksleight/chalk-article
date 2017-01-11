<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Article\Model\Article;

use Toast\Wrapper;
use Chalk\Core\Model\Content\Index as ContentIndex;
use Chalk\App as Chalk;

class Index extends ContentIndex
{
    protected $categories = [];

    protected static function _defineMetadata($class)
    {
        return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
            'fields' => array(
                'categories' => array(
                    'type'      => 'array',
                ),
            ),
        ));
    }
}