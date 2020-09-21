<?php

namespace App\Tests\Unit\Controller;

use App\Tests\AbstractWebTestCaseClass;


class SecurityControllerTest extends AbstractWebTestCaseClass
{

    public function testAccessLogin()
    {
        $this->client->request('GET', "/login");
        $this->assertResponseIsSuccessful();
    }


}
