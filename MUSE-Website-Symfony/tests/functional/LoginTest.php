<?php

namespace App\Tests;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class MuseTest extends WebTestCase
{
    public function testHomePage()
    {
        $client = static::createClient();

        $client->request('GET', '/');
        $this->assertResponseStatusCodeSame(Response::HTTP_OK);

        // Checks if the content of the page contains "Bienvenue"
        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextSame('h1', 'Meilleures ventes');
    }

    public function testLoginPage()
    {
        $client = static::createClient();

        $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Checks if the login form is actually present on the page
        $this->assertSelectorExists('form[action="/login"]');
    }

    public function testLogin()
    {
        $client = static::createClient(); // Creates a client 
        $crawler = $client->request('GET', '/login'); // Goes to the connection page
        $form = $crawler->selectButton('Se connecter')->form(); // Selects the connection form

        // Fills the form with valid credentials
        $form['_username'] = 'client@muse.com';
        $form['_password'] = 'Client12';

        $crawler = $client->submit($form); // Submits the form

        // follow redirect

        $client->followRedirect();

        $this->assertRouteSame('app_home'); // Checks if the response redirects to the home page
        // $this->assertNotEmpty($client->getCookieJar()->get('PHPSESSID')); // Checks if the user session is open
    }
}
