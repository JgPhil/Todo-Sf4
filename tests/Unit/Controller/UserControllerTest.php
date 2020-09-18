<?php

namespace App\Tests\Unit\Controller;

use App\Tests\LogUtils;
use App\Tests\Unit\Controller\DefaultController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class UserControllerTest extends AbstractTestController
{

    public function testAccessCreateActionPageLogged()
    {
        foreach (self::USERS as $type) {
            $this->logUtils->login($type);
            $this->client->request('GET', '/users/create');
            $this->assertResponseIsSuccessful();
        }
    }

    public function testAccessCreateActionPageAnonymous()
    {
        $this->client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();
    }
}
