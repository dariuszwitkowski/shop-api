<?php

namespace App\Tests\Controller;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class GuestControllerTest extends WebTestCase
{
    public function testCreateGuest()
    {
        $client = static::createClient();
        $client->request('GET',
            '/api/guest/get-hash',
            ['ip' => '123.21.12.4'],
        );
        $code = $client->getResponse()->getStatusCode();

        $this->assertNotEquals(400, $client->getResponse()->getStatusCode());
    }

}
