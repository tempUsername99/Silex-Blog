<?php

namespace Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;

/** @ODM\Document */
class Category
{
    /** @ODM\Id */
    private $id;

    /** @ODM\ReferenceMany(targetDocument="Post", mappedBy="category", orphanRemoval=true) */
    private $posts;

    /** @ODM\Field(type="string") @ODM\UniqueIndex */
    private $name;

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getName() { return $this->name; }
    public function setName($name) { $this->name = $name; }

    public function getPosts() { return $this->posts; }
    public function getPostsCount() {return count($this->posts);}


}