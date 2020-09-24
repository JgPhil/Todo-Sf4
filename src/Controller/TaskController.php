<?php

namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    /**
     * @Route("/tasks", name="task_list")
     */
    public function listAction()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('App:Task')->findAll()]);
    }

    /**
     * @Route("/tasks/notDone", name="task_list_not_done")
     */
    public function listNotDoneTasksAction()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('App:Task')->findby(['isDone' => 0])]);
    }

    /**
     * @Route("/tasks/done", name="task_list_done")
     */
    public function listDoneTasksAction()
    {
        return $this->render('task/list.html.twig', ['tasks' => $this->getDoctrine()->getRepository('App:Task')->findby(['isDone' => 1])]);
    }

    /**
     * @Route("/tasks/{id}/show", name="task_show")
     */
    public function showAction($id)
    {
        return $this->render('task/show.html.twig',['task' => $this->getDoctrine()->getRepository('App:Task')->find($id)]);
    }

    /**
     * @Route("/tasks/create", name="task_create")
     */
    public function createAction(Request $request, EntityManagerInterface $em)
    {
        $task = new Task();
        $form = $this->createForm(TaskType::class, $task);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUser($this->getUser()); // Improvement
            $em->persist($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a été bien été ajoutée.');

            return $this->redirectToRoute('task_list');
        }

        return $this->render('task/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/tasks/{id}/edit", name="task_edit")
     */
    public function editAction(Task $task, Request $request)
    {
        if (
            $task->getUser() === $this->getUser() ||
            $this->getUser()->getRole() === 'ROLE_ADMIN'
        ) {
            $form = $this->createForm(TaskType::class, $task);

            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $this->getDoctrine()->getManager()->flush();

                $this->addFlash('success', 'La tâche a bien été modifiée.');

                return $this->redirectToRoute('task_list');
            }

            return $this->render('task/edit.html.twig', [
                'form' => $form->createView(),
                'task' => $task,
            ]);
        } else {
            $this->addFlash('error', 'Vous n\'avez pas la permission de modifier cette tâche');
            return $this->redirectToRoute('task_list');
        }
    }

    /**
     * @Route("/tasks/{id}/toggle", name="task_toggle")
     */
    public function toggleTaskAction(Task $task)
    {
        $task->toggle(!$task->isDone());
        $this->getDoctrine()->getManager()->flush();

        $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

        return $this->redirectToRoute('task_list');
    }

    /**
     * @Route("/tasks/{id}/delete", name="task_delete")
     */
    public function deleteTaskAction(Task $task, EntityManagerInterface $em)
    {

        if (
            $task->getUser() === $this->getUser() ||
            $this->getUser()->getRole() === 'ROLE_ADMIN'
        ) {
            $em->remove($task);
            $em->flush();

            $this->addFlash('success', 'La tâche a bien été supprimée.');
            return $this->redirectToRoute('task_list');
        }

        $this->addFlash('error', 'Cette tâche a été créée par quelqu\'un d\'autre');
        return $this->redirectToRoute('task_list');
    }
}
