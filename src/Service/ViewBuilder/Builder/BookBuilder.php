<?php
/**
 * User: jszutkowski
 */

namespace App\Service\ViewBuilder\Builder;



use Symfony\Component\Templating\EngineInterface;

class BookBuilder extends AbstractItemBuilder
{
    /**
     * @var EngineInterface;
     */
    private $template;

    public function __construct(EngineInterface $template)
    {
        $this->template = $template;
    }

    function buildTitle()
    {
        $this->itemRow->title = $this->item->getName();
    }

    function buildDescription()
    {
        $this->itemRow->description = $this->template->render('item/view/book/_book_description.html.twig', ['item' => $this->item]);
    }
}