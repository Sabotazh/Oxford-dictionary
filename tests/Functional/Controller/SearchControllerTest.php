<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class SearchControllerTest extends WebTestCase
{
    /**
     * @group functional
     */
    public function testGetRequiredData(): void
    {
        $client = static::createClient();

        $desired = 'test';
        $provider = 'oxford';

        $client->request('GET', sprintf('/dictionary/%s/entries?search=%s', $provider, $desired));

        $this->assertResponseIsSuccessful();
    }

    /**
     * @group functional
     */
    public function testNotFindRequiredData(): void
    {
        $client = static::createClient();

        $desired = 'blablabla';
        $provider = 'oxford';

        $client->request('GET', sprintf('/dictionary/%s/entries?search=%s', $provider, $desired));

        $this->assertResponseIsSuccessful();
    }
}
