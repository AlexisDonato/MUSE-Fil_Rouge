<?php

namespace App\Tests;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MySiteTest extends WebTestCase
{
    public function testHomePage()
    {
        $client = static::createClient();

        $client->request('GET', '/');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Checks if the content of the page contains "Bienvenue sur Muse"
        $this->assertStringContainsString(
            'Bienvenue sur Muse',
            $client->getResponse()->getContent()
        );
    }

    public function testLoginPage()
    {
        $client = static::createClient();

        $client->request('GET', '/login');
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

        // Checks if the login form is actually present on the page
        $this->assertSelectorExists('form[action="/login_check"]');
    }

    public function testLogin()
    {
        $client = static::createClient(); // Creates a client 
        $crawler = $client->request('GET', '/login'); // Goes to the connection page
        $form = $crawler->selectButton('Se connecter')->form(); // Selects the connection form

        // Fills the form with valid credentials
        $form['username'] = 'mon_nom_utilisateur';
        $form['password'] = 'mon_mot_de_passe';

        $crawler = $client->submit($form); // Submits the form

        $this->assertResponseRedirects('/'); // Checks if the response redirects to the home page
        $this->assertNotEmpty($client->getCookieJar()->get('PHPSESSID')); // Checks if the user session is open
    }
}
