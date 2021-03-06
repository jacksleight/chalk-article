<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Article\Repository;

use Chalk\Repository;
use Chalk\Core\Behaviour\Searchable;

class Category extends Repository
{
    use Searchable\Repository;
    
    protected $_sort = ['name', 'ASC'];

    public function build(array $params = array(), $extra = false)
    {
        $query = parent::build($params, $extra);

        $this->_searchable_modify($query, $params, $extra);

        return $query;
    }
}