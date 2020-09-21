<?php

namespace App\Tests;

use App\Tests\LogUtils;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AbstractWebTestCaseClass extends WebTestCase
{
    const USERS = ['user', 'admin'];

    protected $client;
    protected $logUtils;
    protected $entityManager;

    public function setUp(): void
    {
        $this->client = static::createClient();
        $this->logUtils = new LogUtils($this->client);
        $this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
    }

    public function testAvoidWarnings()
    {
        $this->assertNotEquals(1,2);
    }
}
