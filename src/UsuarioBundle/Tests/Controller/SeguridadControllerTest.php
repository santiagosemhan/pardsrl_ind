<?php

namespace UsuarioBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class SeguridadControllerTest extends WebTestCase
{
    public function testEditoravanzado()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'editor_avanzado');
    }

}
