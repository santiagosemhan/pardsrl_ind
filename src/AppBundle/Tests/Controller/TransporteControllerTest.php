<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class TransporteControllerTest extends WebTestCase
{
    public function testReportedtm()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', 'reporte_dtm');
    }

}
