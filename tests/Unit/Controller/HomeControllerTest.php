<?php

namespace App\Tests\Unit\Controller;

use App\Tests\LogUtils;
use App\Tests\Unit\Controller\DefaultController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class HomeControllerTest extends AbstractTestController
{

    public function testAccessTaskListPageLogged()
    {
        foreach (self::USERS  as $type) {
            $this->logUtils->login($type);
            $this->client->request('GET', '/');
            $this->assertResponseIsSuccessful();
        }
    }

    public function testAccessTaskListPageAnonymous()
    {
        $this->logUtils->login('');
        $this->client->request('GET', '/');
        $this->assertResponseStatusCodeSame(302);
    }
}
