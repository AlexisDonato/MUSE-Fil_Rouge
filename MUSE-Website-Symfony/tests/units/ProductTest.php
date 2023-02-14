<?php

namespace App\Tests;

use PHPUnit\Framework\TestCase;
use App\Entity\Product;
use App\Entity\Supplier;
use App\Entity\Category;

class ProductTest extends TestCase
{
    public function testProductProperties(): void
    {
        $supplier = new Supplier();
        $category = new Category();
        $product = new Product();
        $product->setName('Product Name')
            ->setPrice(10)
            ->setDescription('Product Description')
            ->setContent('Product Content')
            ->setImage('image.png')
            ->setDiscount(true)
            ->setDiscountRate('0.1')
            ->setQuantity(100)
            ->setImage1('image1.png')
            ->setImage2('image2.png')
            ->setSupplier($supplier)
            ->setCategory($category);

        $this->assertEquals('Product Name', $product->getName());
        $this->assertEquals(10, $product->getPrice());
        $this->assertEquals('Product Description', $product->getDescription());
        $this->assertEquals('Product Content', $product->getContent());
        $this->assertEquals('image.png', $product->getImage());
        $this->assertTrue($product->isDiscount());
        $this->assertEquals('0.1', $product->getDiscountRate());
        $this->assertEquals(100, $product->getQuantity());
        $this->assertEquals('image1.png', $product->getImage1());
        $this->assertEquals('image2.png', $product->getImage2());
        $this->assertEquals($supplier, $product->getSupplier());
        $this->assertEquals($category, $product->getCategory());
    }
}