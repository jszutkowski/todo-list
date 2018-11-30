<?php
/**
 * User: jszutkowski
 */

namespace App\Service\ViewBuilder;


use App\Enum\CategoryEnum;
use App\Service\ViewBuilder\Builder\BookBuilder;
use App\Service\ViewBuilder\Builder\ItemBuilderInterface;
use App\Service\ViewBuilder\Builder\MovieBuilder;
use App\Service\ViewBuilder\Builder\NullBuilder;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;

class BuilderFactory
{
    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    public function create(int $category): ItemBuilderInterface
    {
        switch ($category) {
            case CategoryEnum::MOVIE:
                return new MovieBuilder($this->templating);
            case CategoryEnum::BOOK:
                return new BookBuilder($this->templating);
            default:
                return new NullBuilder();
        }
    }
}