<?php

namespace App\Tests\Unit\Controller;

use App\Tests\LogUtils;
use App\Tests\Unit\Controller\DefaultController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class TaskControllerTest extends AbstractTestController
{

    public function testAccessTaskListPageAuthenticated()
    {
        $this->logUtils->login('admin');
        $this->client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();
    }

    public function testAccessTaskListPageUnauthenticated()
    {
        $this->client->request('GET', '/tasks');
        $this->assertResponseStatusCodeSame(302);
    }
}
