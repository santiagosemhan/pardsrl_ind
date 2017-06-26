<?php

namespace UsuarioBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LogControllerTest extends WebTestCase
{
    public function testAuthenticationlog()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'authentication_log_action');
    }

}
