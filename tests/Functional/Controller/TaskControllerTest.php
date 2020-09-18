<?php

namespace App\Tests\Functional\Controller;


use App\Tests\AbstractWebTestCaseClass;


class TaskTest extends AbstractWebTestCaseClass
{
    public function testcreateTaskAction()
    {
        $this->logUtils->login(self::USERS[1]);
        $this->client->request('GET', '/');
        $crawler = $this->client->clickLink('Créer une nouvelle tâche');
        $button = $crawler->filter('button')->text();
        $this->assertStringContainsString("Ajouter", $button);
    }


}
