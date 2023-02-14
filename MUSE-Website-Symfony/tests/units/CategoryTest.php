<?php

namespace App\Tests;

use App\Entity\Category;
use PHPUnit\Framework\TestCase;

class CategoryTest extends TestCase
{
    public function testSetName()
    {
        // Creates a new category
        $category = new Category();
        
        // Defines the category name
        $category->setName('Test Category');
        
        // Checks if the name of the category is correct
        $this->assertEquals('Test Category', $category->getName());
    }
    
    public function testGetPath()
    {
        // create the root category
        $rootCategory = new Category();
        $rootCategory->setName('root');

        // create a child category
        $childCategory = new Category();
        $childCategory->setName('child');
        $childCategory->setParentCategory($rootCategory);

        // test the path for the child category
        $this->assertEquals(['root', 'child'], $childCategory->getPath());
    }
    
    public function testToString()
    {
        // Creates a new category
        $category = new Category();
        
        // Defines the category name
        $category->setName('Test Category');
        
        // Checks if the method __toString() of the entity gives back its name
        $this->assertEquals('Test Category', (string) $category);
    }
}