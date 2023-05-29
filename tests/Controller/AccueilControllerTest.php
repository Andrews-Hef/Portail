<?php

namespace App\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AccueilControllerTest extends WebTestCase
{

    // Check s'il arrive a se connecter et à détecter le titre de la page
    public function testAccueil()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorTextContains('h1', 'PROFITEZ DES MEILLEURS FILMS/SÉRIES/ANIMÉS AVEC PORTAIL PRIME.');
    }
}
