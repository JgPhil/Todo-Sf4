<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {


        $user = new User;
        $user->setUsername('user');
        $user->setPassword(password_hash('user', PASSWORD_BCRYPT));
        $user->setEmail('user@todo.fr');
        $user->setRole('ROLE_USER');
        $manager->persist($user);

        $admin = new User;
        $admin->setUsername('admin');
        $admin->setPassword(password_hash('admin', PASSWORD_BCRYPT));
        $admin->setEmail('admin@todo.fr');
        $admin->setRole('ROLE_ADMIN');
        $manager->persist($admin);

        $anonym = new User;
        $anonym->setUsername('anonym');
        $anonym->setPassword(password_hash('anonym', PASSWORD_BCRYPT));
        $anonym->setEmail('anonym@todo.fr');
        $anonym->setRole('ROLE_USER');
        $manager->persist($anonym);

        $users =[$user, $admin];

        for ($i = 0; $i < 55; $i++) {
            $task = new Task;
            $task->setUser($users[array_rand($users)]);
            $task->setCreatedAt(new \DateTime());
            $task->setIsDone(rand(0,1));
            $task->setTitle('Titre de la tâche n° '. $i);
            $task->setContent('Contenu de la tâche n° '.$i);
            $manager->persist($task);
        }
         $anonymousTasks = $manager->getRepository(Task::class)->findBy(['user' => null]);
        // fixture file loads the test database
        foreach ($anonymousTasks as $anonymousTask) {
            $anonymousTask->setUser($anonym);
        } 
        $manager->flush();
    }
}
