<?php

namespace App\Tests\Functional\Controller;


use App\Entity\Task;
use App\Tests\AbstractWebTestCaseClass;


class TaskTest extends AbstractWebTestCaseClass
{
    public function testcreateTaskAction()
    {
        $this->logUtils->login(self::USERS[rand(0, 1)]); // USERS defined in AbstractWebTestCaseClass
        $this->client->request('GET', '/');
        $crawler = $this->client->clickLink('Créer une nouvelle tâche');
        $button = $crawler->filter('button')->text();
        $this->assertStringContainsString("Ajouter", $button);
    }

    public function testEditTaskRedirection()
    {
        // Fetch the first Task on the list page
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/tasks');

        if (!$crawler->filter('.thumbnail')->count()) {
            return;
        } else {
            //fetching the first task elements
            $firstTaskTitle = $crawler->filter('.task_update_link')->first()->text();
            $firstTaskContent = $crawler->filter('.task_content')->first()->text();
            $firstTaskUpdateLink = $crawler->filter('.task_update_link')->first()->attr('href');

            $crawler = $this->client->request('GET', $firstTaskUpdateLink);
            $this->assertResponseIsSuccessful();

            // Fetching the elements on the update form yo compare with the ones above
            $titleTask = $crawler->filter('input[name="task[title]"]')->attr('value');
            $contentTask = $crawler->filter('textarea[name="task[content]"]')->text();

            $this->assertEquals($firstTaskTitle, $titleTask);
            $this->assertEquals($firstTaskContent, $contentTask);
        }
    }

    public function testFormEditTask()
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/tasks');
        if (!$crawler->filter('.thumbnail')->count()) {
            return;
        } else {
            // Fetch the first Task on the list page
            $firstTaskUpdateLink = $crawler->filter('.task_update_link')->first()->attr('href');

            $crawler = $this->client->request('GET', $firstTaskUpdateLink);

            $updateTaskForm = $crawler->selectButton("Modifier")->form();
            // Changing the form fields
            $updateTaskForm['task[title]'] = 'tittre bidon';
            $updateTaskForm['task[content]'] = 'Description bidon ';

            $crawler = $this->client->submit($updateTaskForm);

            $crawler = $this->client->followRedirect();

            $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        }
    }


    public function testRemoveTaskButton()
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/tasks');

        $firstTask = $crawler->filter(".task")->first();
        $firstTaskId = explode('/', $firstTask->filter(".task_update_link")->attr('href'))[2];
        $removeTaskForm = $firstTask->selectButton("Supprimer")->form();

        $crawler = $this->client->submit($removeTaskForm);

        $crawler = $this->client->followRedirect();

        $successMessage = $crawler->filter('div.alert.alert-success')->text();

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy(['id' => $firstTaskId]);

        $this->assertStringContainsString('La tâche a bien été supprimée.', $successMessage);
        $this->assertEquals(null, $task);
    }
}
