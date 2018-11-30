<?php
/**
 * User: jszutkowski
 */

namespace App\Service;


use App\Entity\Item;
use App\Entity\TodoList;
use App\Entity\User;
use App\Exception\ItemSorterException;
use App\Repository\ItemRepository;
use Doctrine\Common\Persistence\ObjectManager;

class ItemSorter
{

    /**
     * @var ObjectManager
     */
    private $manager;
    /**
     * @var ItemRepository
     */
    private $repository;

    /**
     * @var int[]
     */
    private $ids;

    /**
     * @var Item[]
     */
    private $items;

    public function __construct(ObjectManager $manager, ItemRepository $repository)
    {
        $this->manager = $manager;
        $this->repository = $repository;
    }

    public function sort(User $loggedUser, TodoList $list, array $ids)
    {
        try {

            $this->setData($ids);
            $this->checkData();
            $this->setItems($loggedUser, $list);
            $this->checkItems($list);
            $this->changeOrder();

            return true;
        } catch (ItemSorterException $e) {
            return false;
        }
    }

    private function setData(array $ids): void
    {
        $this->ids = array_unique($ids);
    }

    /**
     * @throws ItemSorterException
     */
    private function checkData(): void
    {
        foreach ($this->ids as $id) {
            if (!ctype_digit($id)) {
                throw new ItemSorterException('Invalid data');
            }
        }
    }

    private function setItems(User $user, TodoList $list): void
    {
        $this->items = $this->repository->findAllByUserAndList($user, $list, $this->ids);
    }

    /**
     * @throws ItemSorterException
     */
    private function checkItems(TodoList $list)
    {
        if (count($list->getItems()) !== count ($this->ids)) {
            throw new ItemSorterException('Invalid data');
        }
    }

    private function changeOrder(): void
    {
        $flippedIds = array_flip($this->ids);

        foreach ($this->items as $item) {
            $newOrder = $flippedIds[$item->getId()];
            if ($item->getWeight() !== $newOrder) {
                $item->setWeight($newOrder);
                $this->manager->persist($item);
            }
        }

        $this->manager->flush();
    }
}