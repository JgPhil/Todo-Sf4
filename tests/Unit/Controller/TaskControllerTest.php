<?php

namespace App\Tests\Unit\Controller;

use App\Tests\LogUtils;
use App\Tests\Unit\Controller\DefaultController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class TaskControllerTest extends AbstractTestController
{

    public function testAccessTaskListPageLogged()
    {
        foreach (self::USERS  as $type) {
            $this->logUtils->login($type);
            $this->client->request('GET', '/tasks');
            $this->assertResponseIsSuccessful();
        }
    }

    public function testAccessTaskListPageAnonymous()
    {
        $this->client->request('GET', '/tasks');
        $this->assertResponseStatusCodeSame(302);
    }
}
