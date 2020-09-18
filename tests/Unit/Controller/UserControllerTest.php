<?php

namespace App\Tests\Unit\Controller;

use App\Tests\AbstractWebTestCaseClass;


class UserControllerTest extends AbstractWebTestCaseClass
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
