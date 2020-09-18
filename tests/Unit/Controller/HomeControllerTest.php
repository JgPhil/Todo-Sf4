<?php

namespace App\Tests\Unit\Controller;

use App\Tests\LogUtils;
use App\Tests\Unit\Controller\DefaultController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class HomeControllerTest extends AbstractTestController
{

    public function testAccessTaskListPageAdmin()
    {
        $this->logUtils->login('admin');
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    public function testAccessTaskListPageUser()
    {
        $this->logUtils->login('user');
        $this->client->request('GET', '/');
        $this->assertResponseIsSuccessful();
    }

    public function testAccessTaskListPageUnauthenticated()
    {
        $this->client->request('GET', '/');
        $this->assertResponseStatusCodeSame(302);
    }
}
