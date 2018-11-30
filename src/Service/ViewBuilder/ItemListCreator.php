<?php
/**
 * User: jszutkowski
 */

namespace App\Service\ViewBuilder;


use App\Repository\ItemRepository;
use Doctrine\Common\Collections\Criteria;

class ItemListCreator
{
    /**
     * @var BuilderFactory
     */
    private $builderFactory;

    /**
     * @var ItemDirector
     */
    private $director;

    /**
     * @var ItemRepository
     */
    private $repository;

    public function __construct(BuilderFactory $builderFactory, ItemDirector $director, ItemRepository $repository)
    {
        $this->builderFactory = $builderFactory;
        $this->director = $director;
        $this->repository = $repository;
    }

    /**
     * @param int $listId
     * @return ItemRow[]
     */
    public function createList(int $listId): array
    {
        $output = [];

        $items = $this->repository->findBy(['list' => $listId], ['weight' => Criteria::ASC]);

        foreach ($items as $item) {
            $builder = $this->builderFactory->create($item->getCategory());
            $output[] = $this->director->build($builder, $item);
        }

        return $output;
    }
}