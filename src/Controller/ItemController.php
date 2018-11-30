<?php

namespace App\Controller;

use App\Entity\Item;
use App\Entity\TodoList;
use App\Form\ItemType;
use App\Security\Voter\TodoVoter;
use App\Service\ItemCreator;
use App\Service\ItemSorter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/todo/item")
 */
class ItemController extends AbstractController
{

    /**
     * @var ItemCreator
     */
    private $itemCreator;

    public function __construct(ItemCreator $itemCreator)
    {
        $this->itemCreator = $itemCreator;
    }

    /**
     * @Route("/{id}/new", name="item_new", methods="GET|POST")
     */
    public function new(Request $request, TodoList $list): Response
    {
        $this->denyAccessUnlessGranted(TodoVoter::VOTER_EDIT_LIST_ITEM, $list);

        $item = new Item();

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->itemCreator->save($list, $item);

            return $this->redirectToRoute('todo_list_show', ['id' => $list->getId()]);
        }

        return $this->render('item/new.html.twig', [
            'list' => $list,
            'item' => $item,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/edit", name="item_edit", methods="GET|POST")
     */
    public function edit(Request $request, Item $item): Response
    {
        $this->denyAccessUnlessGranted(TodoVoter::VOTER_EDIT_LIST_ITEM, $item->getList());

        $form = $this->createForm(ItemType::class, $item);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('todo_list_show', ['id' => $item->getList()->getId()]);
        }

        return $this->render('item/edit.html.twig', [
            'list' => $item->getList(),
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/toggle-complete", name="item_toggle_complete", methods="POST")
     */
    public function toggleComplete(Item $item)
    {
        $this->denyAccessUnlessGranted(TodoVoter::VOTER_EDIT_LIST_ITEM, $item->getList());

        $manager = $this->getDoctrine()->getManager();

        $item->setIsComplete(!$item->getIsComplete());
        $manager->persist($item);
        $manager->flush();

        return $this->redirectToRoute('todo_list_show', ['id' => $item->getList()->getId()]);
    }

    /**
     * @Route("/change-order/{id}", name="item_change_order", methods="POST", options={"expose"=true})
     */
    public function changeOrder(Request $request, ItemSorter $sorter, TodoList $todoList): Response
    {
        if (!$request->isXmlHttpRequest()) {
            throw new NotFoundHttpException();
        }

        $this->denyAccessUnlessGranted(TodoVoter::VOTER_EDIT_LIST, $todoList);

        $items = $request->request->get('data');

        $result = $sorter->sort($this->getUser(), $todoList, $items);

        $status = $result ? 'ok' : 'error';
        $code = $result ? Response::HTTP_OK : Response::HTTP_BAD_REQUEST;

        return new JsonResponse(['status' => $status], $code);
    }
}
