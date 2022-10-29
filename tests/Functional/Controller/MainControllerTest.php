<?php

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class MainControllerTest extends WebTestCase
{
    /**
     * @group functional
     */
    public function testRender(): void
    {
        $client = static::createClient();
        $client->getContainer();

        $client->request('GET', '/');

        $this->assertResponseIsSuccessful();
        $this->assertSelectorExists('#title');
        $this->assertSelectorExists('#search');
        $this->assertSelectorExists('#history');
        $this->assertSelectorExists('#tags-cloud');
    }
}
