<?php
/**
 * User: jszutkowski
 */

namespace App\Service\ViewBuilder\Builder;


class NullBuilder extends AbstractItemBuilder
{
    function buildTitle()
    {
        $this->itemRow->title = $this->item->getName();
    }

    function buildDescription()
    {
    }
}