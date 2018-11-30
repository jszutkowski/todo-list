<?php
/**
 * User: jszutkowski
 */

namespace App\Service;


use App\Entity\Item;
use App\Entity\TodoList;
use App\Repository\ItemRepository;
use Doctrine\Common\Persistence\ObjectManager;

class ItemCreator
{

    /**
     * @var ItemRepository
     */
    private $repository;
    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ObjectManager $manager, ItemRepository $repository)
    {
        $this->repository = $repository;
        $this->manager = $manager;
    }

    public function save(TodoList $list, Item $item)
    {
        $maxWeight = $this->repository->findMaxWeight($list->getId());
        $item->setWeight($maxWeight + 1);
        $list->addItem($item);

        $this->manager->persist($item);
        $this->manager->flush();
    }

}