<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Article;

use Chalk\Core;
use Chalk\Core\Entity;
use Chalk\Core\Behaviour\Trackable;
use Chalk\Core\Behaviour\Searchable;
use Doctrine\Common\Collections\ArrayCollection;
use Coast\Validator;

/**
 * @Entity
 * @Table(
 *     uniqueConstraints={@UniqueConstraint(columns={"slug"})}
 * )
*/
class Category extends Entity implements Trackable, Searchable
{
    public static $chalkSingular = 'Category';
    public static $chalkPlural   = 'Categories';
    public static $chalkIcon     = 'folder';
    
    use Trackable\Entity;

    /**
     * @Id
     * @Column(type="integer")
     * @GeneratedValue
     */
    protected $id;

    /**
     * @Column(type="string")
     */
    protected $name;

    /**
     * @Column(type="string")
     */
    protected $slug;

    /**
     * @ManyToMany(targetEntity="\Chalk\Article\Article", mappedBy="categories")
     */
    protected $articles;

    public function __construct()
    {   
        parent::__construct();

        $this->articles = new ArrayCollection();
    }

    public function name($name = null)
    {
        if (func_num_args() > 0) {
            $this->name = $name;
            $this->slug($this->name);
            return $this;
        }
        return $this->name;
    }

    public function slug($slug = null)
    {
        if (func_num_args() > 0) {
            $this->slug = isset($slug)
                ? \Chalk\str_slugify($slug)
                : $slug;
            return $this;
        }
        return $this->slug;
    }

    public function searchContent(array $content = [])
    {
        return [
            $this->name,
        ];
    }
}