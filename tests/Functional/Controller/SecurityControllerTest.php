<?php

namespace App\Tests\Functional\Controller;

use App\Tests\AbstractWebTestCaseClass;

class SecurityControllerTest extends AbstractWebTestCaseClass
{
    public function testLogout()
    {
        $this->logUtils->login(self::USERS[rand(0, 1)]); //login
        $this->client->request('GET', '/'); //homepage
        $this->client->clickLink('Se déconnecter'); 
        $this->assertTrue($this->client->getResponse()->isRedirect()); 
        $this->client->followRedirect();
        $this->assertSelectorTextContains('button', 'Se connecter');
    }
}
