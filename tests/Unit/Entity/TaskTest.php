<?php

namespace App\Tests\Unit\Entity;

use App\Entity\Task;
use App\Entity\User;
use PHPUnit\Framework\TestCase;


class TaskTest extends TestCase
{
    
    private $task;

    public function setUp()
    {
        $this->task = new Task();

    }

    public function testCreatedAt()
    {
        $date = '2020-09-17';
        $this->task->setCreatedAt($date);
        $this->assertEquals($date, $this->task->getCreatedAt());
    }

    public function testTitle()
    {
        $title = 'test_titre';
        $this->task->setTitle($title);
        $this->assertEquals($title, $this->task->getTitle());
    }

    public function testContent()
    {
        $content = 'Lorem Elsass ipsum hopla mollis rucksack amet leverwurscht quam. sit bissame';
        $this->task->setContent($content);
        $this->assertEquals($content, $this->task->getContent());
    }

    public function testIsDone()
	{
		$isDone = true;
		$this->task->toggle($isDone);
		$this->assertEquals($isDone, $this->task->isDone());
    }
    
    public function testUser()
	{
		$user = new User();

		$this->task->setUser($user);
		$this->assertEquals($user, $this->task->getUser());
	}




}