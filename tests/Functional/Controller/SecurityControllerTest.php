<?php

namespace App\Tests\Functional\Controller;

use App\Tests\AbstractWebTestCaseClass;

class SecurityControllerTest extends AbstractWebTestCaseClass
{
    public function testLogout()
    {
        $this->logUtils->login(self::USERS[rand(0, 1)]); // first login
        $this->client->request('GET', '/'); //homepage only accessible when logged
        $this->client->clickLink('Se déconnecter'); 
        $this->assertTrue($this->client->getResponse()->isRedirect()); 
        $this->client->followRedirect(); // login page
        $this->assertSelectorTextContains('button', 'Se connecter');
    }
}
