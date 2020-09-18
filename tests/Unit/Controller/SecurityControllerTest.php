<?php

namespace App\Tests\Unit\Controller;

use App\Tests\LogUtils;
use App\Tests\Unit\Controller\DefaultController;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class SecurityControllerTest extends AbstractTestController
{

    public function testAccessLogin()
    {
        $this->client->request('GET', "/login");
        $this->assertResponseIsSuccessful();
    }


}
