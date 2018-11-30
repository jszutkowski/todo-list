<?php
/**
 * User: jszutkowski
 */

namespace App\Service\ViewBuilder;


use App\Entity\Item;
use App\Service\ViewBuilder\Builder\ItemBuilderInterface;

class ItemDirector
{
    public function build(ItemBuilderInterface $builder, Item $item): ItemRow
    {
        $builder->init($item);
        $builder->buildTitle();
        $builder->buildDescription();

        return $builder->getResult();
    }
}