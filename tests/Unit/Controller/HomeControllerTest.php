<?php

namespace App\Tests\Unit\Controller;

use App\Tests\LogUtils;
use App\Tests\Unit\Controller\DefaultController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class HomeControllerTest extends AbstractTestController
{

    public function testAccessTaskListPage()
    {
        foreach (self::USERS  as $type) {
            if ($type !== '') {
                $this->logUtils->login($type);
                $this->client->request('GET', '/');
                $this->assertResponseIsSuccessful();
            }
        }
    }

    public function testAccessTaskListPageAnonymous()
    {
        $this->logUtils->login(self::USERS[2]);
        $this->client->request('GET', '/');
        $this->assertResponseStatusCodeSame(302);
    }
}
