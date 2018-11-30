<?php

namespace App\Controller;

use App\Entity\TodoList;
use App\Form\TodoListType;
use App\Repository\TodoListRepository;
use App\Security\Voter\TodoVoter;
use App\Service\ListShare;
use App\Service\ViewBuilder\ItemListCreator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/")
 */
class TodoListController extends AbstractController
{
    /**
     * @Route("/", name="todo_list_index", methods="GET")
     */
    public function index(TodoListRepository $todoListRepository): Response
    {
        $lists = $todoListRepository->findBy(['user' => $this->getUser()]);
        return $this->render('todo_list/index.html.twig', ['todo_lists' => $lists]);
    }

    /**
     * @Route("/new", name="todo_list_new", methods="GET|POST")
     */
    public function new(Request $request): Response
    {
        $todoList = new TodoList();
        $todoList->setUser($this->getUser());
        $form = $this->createForm(TodoListType::class, $todoList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($todoList);
            $em->flush();

            return $this->redirectToRoute('todo_list_show', ['id' => $todoList->getId()]);
        }

        return $this->render('todo_list/new.html.twig', [
            'todo_list' => $todoList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="todo_list_show", methods="GET")
     */
    public function show(ItemListCreator $listCreator, TodoList $todoList): Response
    {
        $this->denyAccessUnlessGranted(TodoVoter::VOTER_SHOW_LIST, $todoList);

        $items = $listCreator->createList($todoList->getId());
        return $this->render('todo_list/show.html.twig', ['todo_list' => $todoList, 'items' => $items]);
    }

    /**
     * @Route("/shared/{token}", name="todo_list_show_shared", methods="GET")
     */
    public function shared(ItemListCreator $listCreator, TodoList $todoList): Response
    {
        $this->denyAccessUnlessGranted(TodoVoter::VOTER_SHOW_LIST, $todoList);

        $items = $listCreator->createList($todoList->getId());
        return $this->render('todo_list/show_shared.html.twig', ['todo_list' => $todoList, 'items' => $items]);
    }

    /**
     * @Route("/{id}/edit", name="todo_list_edit", methods="GET|POST")
     */
    public function edit(Request $request, TodoList $todoList): Response
    {
        $this->denyAccessUnlessGranted(TodoVoter::VOTER_EDIT_LIST, $todoList);

        $form = $this->createForm(TodoListType::class, $todoList);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('todo_list_show', ['id' => $todoList->getId()]);
        }

        return $this->render('todo_list/show.html.twig', [
            'todo_list' => $todoList,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/share", name="todo_list_share", methods="POST")
     */
    public function share(ListShare $listShare, TodoList $todoList): Response
    {
        $this->denyAccessUnlessGranted(TodoVoter::VOTER_EDIT_LIST, $todoList);
        $listShare->shareList($todoList);
        return $this->redirectToRoute('todo_list_show', ['id' => $todoList->getId()]);
    }

    /**
     * @Route("/{id}/unshare", name="todo_list_unshare", methods="POST")
     */
    public function unshare(ListShare $listShare, TodoList $todoList): Response
    {
        $this->denyAccessUnlessGranted(TodoVoter::VOTER_EDIT_LIST, $todoList);
        $listShare->unshareList($todoList);
        return $this->redirectToRoute('todo_list_show', ['id' => $todoList->getId()]);
    }

    /**
     * @Route("/{id}", name="todo_list_delete", methods="DELETE")
     */
    public function delete(Request $request, TodoList $todoList): Response
    {
        $this->denyAccessUnlessGranted(TodoVoter::VOTER_EDIT_LIST, $todoList);

        if ($this->isCsrfTokenValid('delete'.$todoList->getId(), $request->request->get('_token'))) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($todoList);
            $em->flush();
        }

        return $this->redirectToRoute('todo_list_index');
    }
}
