<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Article\Backend\Model\Article;

use Chalk\Chalk;
use Chalk\Core\Backend\Model\Content\Index as ContentIndex;

class Index extends ContentIndex
{
    protected $categories = [];

    protected static function _defineMetadata($class)
    {
        return \Coast\array_merge_smart(parent::_defineMetadata($class), array(
            'fields' => array(
                'categories' => array(
                    'type'      => 'array',
                    'nullable'  => true,
                ),
            )
        ));
    }

    public function rememberFields(array $fields = [])
    {
        return parent::rememberFields(array_merge([
            'categories',
        ], $fields));
    }
}