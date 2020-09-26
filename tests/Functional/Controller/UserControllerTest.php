<?php

namespace App\Tests\Functional\Controller;


use App\Entity\Task;
use App\Entity\User;
use App\Tests\AbstractWebTestCaseClass;
use Symfony\Component\Security\Core\Encoder\PasswordEncoderInterface;

class UserControllerTest extends AbstractWebTestCaseClass
{


    public function testCreateUserAction()
    {
        $this->client->request('GET', '/login');
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/users/create');
        $this->assertSelectorTextContains('h1', 'Création d\'un utilisateur');
        $createUserForm = $crawler->selectButton('Créer')->form();
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

    public function testEditUserAction()
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/users');
        $this->assertSelectorTextContains('h1', 'Liste');

        $crawler = $this->client->clickLink('Edit')->first();

        $editUserForm = $crawler->selectButton('Modifier')->form();

        $editUserForm['user[username]'] = 'user_updated';
        $editUserForm['user[password][first]']  = 'user';
        $editUserForm['user[password][second]']  = 'user';
        $editUserForm['user[email]'] = 'user_updated@todo.fr';
        $editUserForm['user[role]'] = 'ROLE_ADMIN';

        $crawler = $this->client->submit($editUserForm);

        $crawler = $this->client->followRedirect();

        $user = $this->entityManager
            ->getRepository(User::class)
            ->findOneBy(['username' => 'user_updated']);
        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        $this->assertEquals('user_updated', $user->getUsername());
        $this->assertEquals('user_updated@todo.fr', $user->getEmail());

        //Reverse to the original credentials
        $user->setUsername('user');
        $user->setEmail('user@todo.fr');
        $user->setRole('ROLE_USER');
        $this->entityManager->flush();
    }
}
