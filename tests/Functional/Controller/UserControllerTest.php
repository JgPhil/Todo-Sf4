<?php

namespace App\Tests\Functional\Controller;


use App\Entity\Task;
use App\Entity\User;
use App\Tests\AbstractWebTestCaseClass;

class UserControllerTest extends AbstractWebTestCaseClass
{

    public function testcreatetUserRedirection()
    {
        $this->client->request('GET', '/login');
        $crawler = $this->client->clickLink('Créer un utilisateur');
        $button = $crawler->filter('button')->text();
        $this->assertStringContainsString("Ajouter", $button);
    }

    public function testcreateUserAction()
    {
        $this->client->request('GET', '/login');
        $this->logUtils->login('admin');
        $crawler = $this->client->clickLink('Créer un utilisateur');
        $createUserForm = $crawler->selectButton('Ajouter')->form();
        $createUserForm['user[username]'] = 'nom bidon';
        $createUserForm['user[password][first]']  = 'mot de passe bidon';
        $createUserForm['user[password][second]']  = 'mot de passe bidon';
        $createUserForm['user[email]'] = 'email@bidon.fr';
        $createUserForm['user[role]'] = 'ROLE_USER';

        $crawler = $this->client->submit($createUserForm);
        $crawler = $this->client->followRedirect();

        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => 'nom bidon']);

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        $this->assertNotNull($user);
        $this->entityManager->remove($user);
        $this->entityManager->flush();
    }


}
