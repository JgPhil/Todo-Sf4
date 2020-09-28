<?php

namespace App\Tests\Functional\Controller;


use App\Entity\Task;
use App\Repository\TaskRepository;
use App\Tests\AbstractWebTestCaseClass;


class TaskControllerTest extends AbstractWebTestCaseClass
{

    public function testcreateTaskAction()
    {
        $this->logUtils->login(self::USERS[rand(0, 1)]); // USERS defined in AbstractWebTestCaseClass
        $this->client->request('GET', '/');
        $crawler = $this->client->clickLink('Créer une nouvelle tâche');
        $this->assertSelectorTextContains('h1', 'Créer une nouvelle tâche');
        $createTaskForm = $crawler->selectButton('Créer')->form();
        $createTaskForm['task[title]'] = 'titre bidon';
        $createTaskForm['task[content]'] = 'description bidon';

        $crawler = $this->client->submit($createTaskForm);
        $crawler = $this->client->followRedirect();

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy(['title' => 'titre bidon']);

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
        $this->assertNotNull($task);
        $this->entityManager->remove($task);
        $this->entityManager->flush();
    }

    public function testEditTaskRedirection()
    {
        // Fetch the first Task on the list page
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/tasks');

        if (!$crawler->filter('.thumbnail')->count()) {
            return;
        }
        //fetching the first task elements
        $firstTaskTitle = $crawler->filter('.task_update_link')->first()->text();
        $firstTaskContent = $crawler->filter('.task_content')->first()->text();
        $firstTaskShowLink = $crawler->filter('.task_update_link')->first()->attr('href');

        $crawler = $this->client->request('GET', $firstTaskShowLink); //show
        $editLink = $crawler->filter('.edit_task')->attr('href');
        $crawler = $this->client->request('GET', $editLink);
        $this->assertResponseIsSuccessful(); //edit

        // Fetching the elements on the update form yo compare with the ones above
        $titleTask = $crawler->filter('input[name="task[title]"]')->attr('value');
        $contentTask = substr($crawler->filter('textarea[name="task[content]"]')->text(), 0, 30);

        $this->assertEquals($firstTaskTitle, $titleTask);
        $this->assertEquals($firstTaskContent, $contentTask);
    }

    public function testEditAction()
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/tasks');

        if (!$crawler->filter('.thumbnail')->count()) {
            return;
        }
        // Fetch the first Task on the list page
        $firstTaskShowLink = $crawler->filter('.task_update_link')->first()->attr('href');

        $crawler = $this->client->request('GET', $firstTaskShowLink); //show
        $editLink = $crawler->filter('.edit_task')->attr('href');
        $crawler = $this->client->request('GET', $editLink);
        $this->assertResponseIsSuccessful(); //edit

        $updateTaskForm = $crawler->selectButton("Modifier")->form();
        // Changing the form fields
        $updateTaskForm['task[title]'] = 'tittre bidon';
        $updateTaskForm['task[content]'] = 'Description bidon ';

        $crawler = $this->client->submit($updateTaskForm);

        $crawler = $this->client->followRedirect();

        $this->assertSame(1, $crawler->filter('div.alert.alert-success')->count());
    }

    
    public function testDeleteTaskButton()
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();

        $firstTask = $crawler->filter(".task")->first();
        $firstTaskId = explode('/', $firstTask->filter(".task_update_link")->attr('href'))[2];

        $crawler = $this->client->request('DELETE', $firstTask->filter(".task_delete_btn")->attr('href'));

        $crawler = $this->client->followRedirect();

        $successMessage = $crawler->filter('div.alert.alert-success')->text(null, true);

        $task = $this->entityManager
            ->getRepository(Task::class)
            ->findOneBy(['id' => $firstTaskId]);

        $this->assertStringContainsString('La tâche a bien été supprimée.', $successMessage);
        $this->assertEquals(null, $task);
    }


    public function testListNotDoneTasksAction()
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();

        $crawler = $this->client->clickLink("Voir les tâches non terminées");
        $this->assertSelectorTextContains('h1', "Liste des tâches non terminées");
    }

    public function testListDoneTasksAction()
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();

        $crawler = $this->client->clickLink("Voir les tâches terminées");
        $this->assertSelectorTextContains('h1', "Liste des tâches terminées");
    }

    public function testToggleTaskAction()
    {
        $this->logUtils->login('admin');
        $crawler = $this->client->request('GET', '/tasks');
        $this->assertResponseIsSuccessful();

        if (!$crawler->filter('.thumbnail')->count()) {
            return;
        }
        $firstTask = $crawler->filter(".task")->first();
        $firstTaskToogleButtonText = $firstTask->filter('.toggle')->text(null, false);
        $toggleButtonHref = $firstTask->filter("a")->attr('href');
        $this->client->request('GET', $toggleButtonHref);
        $this->assertNotEquals($crawler->filter(".task")->first()->text(null, false), $firstTaskToogleButtonText);
    }
}
