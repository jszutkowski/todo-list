<?php

namespace App\Form;

use App\Embeddable\Book;
use App\Embeddable\Movie;
use App\Entity\Item;
use App\Enum\CategoryEnum;
use App\Form\ItemTypes\BookType;
use App\Form\ItemTypes\MovieType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ItemType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name')
            ->add('category', ChoiceType::class, [
                'choices' => array_flip(CategoryEnum::getNames())
            ])
            ->add('bookDetails', BookType::class)
            ->add('movieDetails', MovieType::class)
            ->addEventListener(FormEvents::POST_SUBMIT, [$this, 'onPostSubmit'])
        ;
    }

    public function onPostSubmit(FormEvent $event)
    {

        /** @var $item Item*/
        $item = $event->getData();

        switch ($item->getCategory()) {
            case CategoryEnum::MOVIE:
                $item->setBookDetails(new Book());
                break;
            case CategoryEnum::BOOK:
                $item->setMovieDetails(new Movie());
                break;
            default:
                $item->setBookDetails(new Book());
                $item->setMovieDetails(new Movie());
                break;
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Item::class,
        ]);
    }
}
