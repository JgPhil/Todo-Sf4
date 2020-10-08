<?php

namespace App\Tests\Unit\Controller;

use App\Tests\AbstractWebTestCaseClass;


class UserControllerTest extends AbstractWebTestCaseClass
{

    public function testAccessCreateActionPageLogged()
    {

        $this->logUtils->login('admin');
        $this->client->request('GET', '/users/create');
        $this->assertResponseIsSuccessful();
    }

    public function testAccessCreateActionPageAnonymous()
    {
        $this->client->request('GET', '/users/create');
        $this->assertResponseRedirects();
    }
}
