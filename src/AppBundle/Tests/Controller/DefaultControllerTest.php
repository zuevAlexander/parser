<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class DefaultControllerTest extends WebTestCase
{
    public function testAdmin()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/admin');
    }

}
