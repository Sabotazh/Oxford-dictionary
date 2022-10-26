<?php

namespace App\Tests\Application\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class FavoriteControllerTest extends WebTestCase
{
    /** @test */
    public function testIndexSuccess(): void
    {
        $client = static::createClient();
        $client->request(
            'GET',
            '/favorites'
        );
        self::assertEquals(200, $client->getResponse()->getStatusCode());
    }
}