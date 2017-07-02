<?php

namespace Documents;

use Doctrine\ODM\MongoDB\Mapping\Annotations as ODM;
use Symfony\Component\Config\Definition\Exception\Exception;

/** @ODM\Document */
class Post
{
    /** @ODM\Id */
    private $id;

    /** @ODM\ReferenceOne(targetDocument="Category", inversedBy="posts") */
    private $category;

    /** @ODM\Field(type="int", strategy="increment") */
    private $views;

    /** @ODM\Field(type="string") */
    private $title;

    /** @ODM\Field(type="string") */
    private $description;

    /** @ODM\Field(type="string") */
    private $body;

    /** @ODM\Field(type="string") */
    private $image;

    /** @ODM\Field(type="date") */
    private $created;

    public function getId() { return $this->id; }
    public function setId($id) { $this->id = $id; }

    public function getViews() { return $this->views; }
    public function setViews($views) { $this->views = $views; }
    public function incrementViews() { $this->views++; }

    public function getTitle() { return $this->title; }
    public function setTitle($title) { $this->title = $title; }

    public function getDescription() { return $this->description; }
    public function setDescription($description) { $this->description = $description; }

    public function getBody() { return $this->body; }
    public function setBody($body) { $this->body = $body; }

    public function getDate() { return $this->created; }
    public function setDate(\DateTime $created) { $this->created = $created; }

    public function getImage() { return $this->image; }
    public function setImage($image) { $this->image = $image; }

    public function getCategory()
    {
        try{
            is_null($this->category);
        }
        catch (Exception $e){ return null; }
        return $this->category;
    }
    public function setCategory(Category $category) { $this->category = $category; }

    public function getAllData() { return ['Title'=>$this->title, 'Description'=>$this->description, 'Image'=>$this->image, 'Body'=>$this->body, 'Views'=>$this->views]; }
}