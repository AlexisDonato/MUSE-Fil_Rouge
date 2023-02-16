<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ProductsTest extends WebTestCase
{
    public function testShowProducts()
    {
        // Creates a client
        $client = static::createClient();

        // Goes to the page "/product"
        $crawler = $client->request('GET', '/product');

        // Checks if the page is loaded with success
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Checks if the page contains at least one product with a div="card"
        $this->assertGreaterThan(
            0,
            $crawler->filter('div.card')->count()
        );
    }

    // public function testAddProduct()
    // {
    //     // Creates a client
    //     $client = static::createClient();

    //     // Goes to the page "/admin/product/new"
    //     $crawler = $client->request('GET', '/admin/product/new');

    //     // Fills the form with the test data
    //     $form = $crawler->selectButton('Enregistrer')->form();
    //     $form['product[name]'] = 'Nouveau produit';
    //     $form['product[price]'] = 19;

    //     // Submits the form
    //     $crawler = $client->submit($form);

    //     // Checks if the user is redirected towards the products list
    //     $this->assertTrue(
    //         $client->getResponse()->isRedirect('/admin/product/')
    //     );

    //     // Goes to the products list
    //     $crawler = $client->followRedirect();

    //     // Checks if the new product has been added
    //     $this->assertGreaterThan(
    //         0,
    //         $crawler->filter('td:contains("Nouveau produit - 19 €")')->count()
    //     );
    // }
}
