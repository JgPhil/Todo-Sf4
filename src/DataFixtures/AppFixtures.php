<?php

namespace App\DataFixtures;

use App\Entity\Task;
use App\Entity\User;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Yaml\Yaml;

class AppFixtures extends Fixture
{

    public function load(ObjectManager $manager)
    {
        $data = Yaml::parseFile('config/users.yaml')['users'];

        foreach ($data as $userData) {
            $user = new User;
            $user->setUsername($userData["username"]);
            $user->setPassword(password_hash($userData["password"], PASSWORD_BCRYPT));
            $user->setEmail($userData["email"]);
            $user->setRole($userData["role"]);
            $manager->persist($user);

            for ($i = 0; $i < 35; $i++) {
                $task = new Task;
                $task->setUser($user);
                $task->setCreatedAt(new \DateTime());
                $task->setIsDone(rand(0, 1));
                $task->setTitle('Titre de la tâche n° ' . $i);
                $task->setContent('Contenu de la tâche n° ' . $i);
                $manager->persist($task);
            }

            $manager->flush();
        }
    }
}
