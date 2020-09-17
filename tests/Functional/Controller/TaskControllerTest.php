<?php

namespace App\Tests\Unit\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;


class TaskControllerTest extends WebTestCase
{
    private $client;
    private $user;
    private $idCreatedTask;
    private $entityManager;
    private $userRepository;

    public function setUp(): void
    {
        $this->client = static::createClient();

        $this->entityManager = $this->client->getContainer()
            ->get('doctrine')
            ->getManager();
    }

    public function testAccessTaskList()
    {
        $this->client->loginUser($this->user);
    }
}
