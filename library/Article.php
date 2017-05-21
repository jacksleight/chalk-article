<?php
/*
 * Copyright 2017 Jack Sleight <http://jacksleight.com/>
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
    public static $chalkIs       = [
        'tagable' => true,
    ];

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
        
    public function searchContent(array $content = [])
    {
        return parent::searchContent(array_merge([
            $this->summary,
            $this->body,
        ], $content));
    }
    
    public function extract($length)
    {
        return \Coast\str_trim_words($this->body, $length, '…');
    }
    
    public function description($length)
    {
        return isset($this->summary)
            ? strip_tags($this->summary)
            : $this->extract($length);
    }
}