<?php

namespace App\Tests\Controller;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class CartControllerTest extends WebTestCase
{
//    public function testCreateCart()
//    {
//        $client = static::createClient();
//
//        $client->request('GET',
//            '/api/guest/get-hash',
//            ['ip' => rand(2000, 10000)],
//        );
//        $hash = str_replace('"', '', $client->getResponse()->getContent());
//        $client->request('POST',
//            '/api/cart/create',
//            ['guestHash' => $hash],
//        );
//        $this->assertNotEquals(400, $client->getResponse()->getStatusCode());
//
//    }
//
//    public function testAddProduct()
//    {
//        $client = static::createClient();
//
//        $client->request('GET',
//            '/api/guest/get-hash',
//            ['ip' => rand(2000, 10000)],
//        );
//        $hash = str_replace('"', '', $client->getResponse()->getContent());
//
//        $client->request('POST',
//            '/api/product/add',
//            ['name' => '' . md5(rand()) . '', 'price' => '123'],
//        );
//
//        $prodId = str_replace('"', '', $client->getResponse()->getContent());
//
//        $client->request('PUT',
//            '/api/cart/add_item',
//            ['guestHash' => $hash, 'productId' => $prodId],
//        );
//
//        $this->assertEquals(200, $client->getResponse()->getStatusCode());
//
//    }


    public function testRemoveProduct()
    {
        $client = static::createClient();

        $client->request('GET',
            '/api/guest/get-hash',
            ['ip' => rand(2000, 10000)],
        );
        $hash = str_replace('"', '', $client->getResponse()->getContent());

        $client->request('POST',
            '/api/product/add',
            ['name' => '' . md5(rand()) . '', 'price' => '123'],
        );
        $prodId = str_replace('"', '', $client->getResponse()->getContent());

        $client->request('PUT',
            '/api/cart/add_item',
            ['guestHash' => $hash, 'productId' => $prodId],
        );


        $client->request('DELETE',
            '/api/cart/remove_item',
            ['guestHash' => $hash, 'productId' => $prodId],
        );
        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

    public function testGetCart()
    {
        $client = static::createClient();

        $client->request('GET',
            '/api/guest/get-hash',
            ['ip' => rand(2000, 10000)],
        );
        $hash = str_replace('"', '', $client->getResponse()->getContent());

        $client->request('POST',
            '/api/product/add',
            ['name' => '' . md5($hash) . '', 'price' => '123'],
        );
        $prodId = $client->getResponse()->getContent();

        $client->request('PUT',
            '/api/cart/add_item',
            ['guestHash' => $hash, 'productId' => $prodId],
        );

        $client->request('GET',
            '/api/cart/get_products',
            ['guestHash' => $hash],
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());

    }

}
