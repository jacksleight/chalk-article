<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Article\Repository;

use Chalk\Repository;
use Chalk\Core\Repository\Content;

class Article extends Content
{
    protected $_sort = [
        ['IF(a.publishDate IS NULL, 1, 0)', 'DESC'],
        ['publishDate', 'DESC'],
    ];

    public function build(array $params = array())
    {
        $query = parent::build($params);

        $params = $params + [
            'categories' => null,
        ];

        if (isset($params['categories']) && count($params['categories'])) {
            $query
                ->andWhere(":categories MEMBER OF {$this->alias()}.categories")
                ->setParameter('categories', $params['categories']);
        }

        $query
            ->addSelect("ac")
            ->leftJoin("a.categories", "ac");

        return $query;
    }
}