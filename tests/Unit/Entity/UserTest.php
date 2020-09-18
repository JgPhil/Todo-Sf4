<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class UserTest extends WebTestCase
{
    private $encoder;
    private $user;

    public function setUp()
    {
        static::bootKernel();
        $container = self::$container;
        $this->encoder = $container->get('security.password_encoder');
        $this->user = new User();
    }

    public function testUsername()
    {
        $username = "Test username";
        $this->user->setUsername($username);
        $this->assertEquals($username, $this->user->getUsername());
    }

    public function testEncodePassword()
    {
        $password = "test password";
        $this->user->setPassword($this->encoder->encodePassword($this->user, $password));
        $this->assertTrue($this->encoder->isPasswordValid($this->user, $password));
    }

    public function testPassword()
    {
        $password = "test password";
        $passwordEncode = $this->encoder->encodePassword($this->user, $password);
        $this->user->setPassword($passwordEncode);
        $this->assertEquals($passwordEncode, $this->user->getPassword());
    }

    public function testEmail()
    {
        $email = 'user@user.com';
        $this->user->setEmail($email);
        $this->assertEquals($email, $this->user->getEmail());
    }

    public function testRole()
    {
        $role = 'ROLE_ADMIN';
        $this->user->setRole($role);
        $this->assertEquals($role, $this->user->getRole());
        $this->assertEquals($this->user->isAdmin(), 1);
    }


    public function testAddAndRemoveTask()
    {
        $task = new Task();
        $this->user->addTask($task);
        $this->assertEquals($task, $this->user->getTasks()[0]);
        $this->user->removeTask($task);
        $this->assertEquals([], $this->user->getTasks()->toArray());
    }

    
}
