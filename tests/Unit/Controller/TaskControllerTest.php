<?php

namespace App\Tests\Unit\Controller;

use App\Tests\AbstractWebTestCaseClass;


class TaskControllerTest extends AbstractWebTestCaseClass
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
