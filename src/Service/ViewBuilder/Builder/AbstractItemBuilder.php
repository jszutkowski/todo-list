<?php
/**
 * User: jszutkowski
 */

namespace App\Service\ViewBuilder\Builder;


use App\Entity\Item;
use App\Service\ViewBuilder\ItemRow;

abstract class AbstractItemBuilder implements ItemBuilderInterface
{
    /**
     * @var Item
     */
    protected $item;

    /**
     * @var ItemRow
     */
    protected $itemRow;

    public function init(Item $item)
    {
        $this->item = $item;
        $this->itemRow = new ItemRow();
        $this->itemRow->item = $item;
    }

    public function getResult(): ItemRow
    {
        return $this->itemRow;
    }
}