<?php

namespace App\Tests\Controller;

use App\Repository\ProductRepository;
use Doctrine\ORM\EntityManager;
use Doctrine\Persistence\ObjectRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\BrowserKit\AbstractBrowser;

class ProductControllerTest extends WebTestCase
{
    public function testAddProduct()
    {
        $client = static::createClient();
        $name = md5(rand());
        $client->request('POST',
            '/api/product/add',
            ['name' => '' . $name . '', 'price' => '123'],
        );
        $this->assertEquals(201, $client->getResponse()->getStatusCode());
    }
    public function testUpdateProduct()
    {

        $client = static::createClient();

        $client->request('POST',
            '/api/product/add',
            ['name' => ''.md5(rand()).'', 'price' => '123'],
        );

        $id = $client->getResponse()->getContent();

        $client->request('PUT',
            '/api/product/update',
            ['id' =>$id, 'name' => ''.md5(rand()).'', 'price' => '123'],
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    public function testRemoveProduct()
    {

        $client = static::createClient();

        $client->request('POST',
            '/api/product/add',
            ['name' => ''.md5(rand()).'', 'price' => '123'],
        );

        $id = $client->getResponse()->getContent();

        $client->request('DELETE',
            '/api/product/remove',
            ['id' =>$id],
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
    public function testProductList()
    {

        $client = static::createClient();

        $client->request('GET',
            '/api/product/list',
            [
                'page' => '1',
                'order' => 'DESC',
                'limit' => 3,
                'pagination' => '0',
                'orderField' => 'name'
            ]
        );

        $this->assertEquals(200, $client->getResponse()->getStatusCode());
    }
}
