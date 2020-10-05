<?php


namespace App\Controller;

use App\Entity\Task;
use App\Form\TaskType;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;


class TaskController extends AbstractController
{

    /**
     * taskRepository
     *
     * @var mixed
     */
    private $taskRepository;

    /**
     * __construct
     *
     * @param  mixed $taskRepository
     * @return void
     */
    public function __construct(TaskRepository $taskRepository)
    {
        $this->taskRepository = $taskRepository;
    }

    /**
     * listAction
     *
     * @param  mixed $taskRepository
     * @return void
     * 
     * @Route("/tasks", name="task_list", methods={"GET"})
     */
    public function listAction()
    {
        return $this->render('task/list.html.twig', [
            'tasks' => $this->taskRepository->findAll()
        ]);
    }

    /**
     * listNotDoneTasksAction
     *
     * @param  mixed $taskRepository
     * @return void
     * 
     * @Route("/tasks/notDone", name="task_list_not_done", methods={"GET"})
     */
    public function listNotDoneTasksAction()
    {
        return $this->render('task/list.html.twig', [
            'tasks' => $this->taskRepository->findby(['isDone' => 0])
        ]);
    }

    /**
     * listDoneTasksAction
     *
     * @param  mixed $taskRepository
     * @return void
     * 
     * @Route("/tasks/done", name="task_list_done", methods={"GET"})
     */
    public function listDoneTasksAction()
    {
        return $this->render('task/list.html.twig', [
            'tasks' => $this->taskRepository->findby(['isDone' => 1])
        ]);
    }

    /**
     * showAction
     *
     * @param  mixed $taskRepository
     * @param  mixed $id
     * @return void
     * 
     * @Route("/tasks/{id}/show", name="task_show", methods={"GET"})
     */
    public function showAction($id)
    {
        return $this->render('task/show.html.twig', [
            'task' => $this->taskRepository->find($id)
        ]);
    }

    /**
     * createAction
     *
     * @param  mixed $request
     * @param  mixed $em
     * @return void
     * 
     * @Route("/tasks/create", name="task_create", methods={"GET", "POST"})
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

        return $this->render('task/task_form.html.twig', ['form' => $form->createView()]);
    }

    /**
     * editAction
     *
     * @param  mixed $task
     * @param  mixed $request
     * @return void
     * 
     * @Route("/tasks/{id}/edit", name="task_edit", methods={"GET", "POST"})
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

            return $this->render('task/task_form.html.twig', [
                'form' => $form->createView(),
                'task' => $task,
            ]);
        }
        $this->addFlash('error', 'Vous n\'avez pas la permission de modifier cette tâche');
        return $this->redirectToRoute('task_list');
    }

    /**
     * toggleTaskAction
     *
     * @param  mixed $task
     * @return void
     * 
     * @Route("/tasks/{id}/toggle", name="task_toggle", methods={"GET", "POST"})
     */
    public function toggleTaskAction(Task $task)
    {
        if (
            $task->getUser() === $this->getUser() ||
            $this->getUser()->getRole() === 'ROLE_ADMIN'
        ) {
            $task->toggle(!$task->isDone());
            $this->getDoctrine()->getManager()->flush();

            $this->addFlash('success', sprintf('La tâche %s a bien été marquée comme faite.', $task->getTitle()));

            return $this->redirectToRoute('task_list');
        }
        $this->addFlash('error', 'Cette tâche a été créée par quelqu\'un d\'autre');
        return $this->redirectToRoute('task_list');
    }

    /**
     * deleteTaskAction
     *
     * @param  mixed $task
     * @param  mixed $em
     * @return void
     * 
     * @Route("/tasks/{id}/delete", name="task_delete", methods={"GET", "DELETE"})
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
