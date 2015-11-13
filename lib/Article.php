<?php
/*
 * Copyright 2015 Jack Sleight <http://jacksleight.com/>
 * This source file is subject to the MIT license that is bundled with this package in the file LICENCE.md. 
 */

namespace Chalk\Article;

use Chalk\Core,
    Chalk\Core\Content,
    Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity
*/
class Article extends Content
{
    public static $chalkSingular = 'Article';
    public static $chalkPlural   = 'Articles';
    public static $chalkIcon     = 'newspaper';

    /**
     * @Column(type="text", nullable=true)
     */
    protected $summary;

    /**
     * @Column(type="text")
     */
    protected $body;

    /**
     * @ManyToOne(targetEntity="\Chalk\Core\User")
     * @JoinColumn(nullable=true)
     */
    protected $author;

    /**
     * @ManyToOne(targetEntity="\Chalk\Core\File")
     * @JoinColumn(nullable=true)
     */
    protected $image;

    /**
     * @ManyToMany(targetEntity="\Chalk\Article\Category", inversedBy="articles")
     */
    protected $categories;

    public function __construct()
    {   
        parent::__construct();

        $this->categories = new ArrayCollection();
    }
        
    public function searchableContent()
    {
        return array_merge(parent::searchableContent(), [
            $this->summary,
            $this->body,
        ]);
    }
}