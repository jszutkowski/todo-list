<?php
/**
 * User: jszutkowski
 */

namespace App\Service\ViewBuilder\Builder;


use App\Entity\Item;
use App\Service\ViewBuilder\ItemRow;

interface ItemBuilderInterface
{
    function init(Item $item);
    function buildTitle();
    function buildDescription();
    function getResult(): ItemRow;
}