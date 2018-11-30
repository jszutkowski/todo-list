<?php
/**
 * User: jszutkowski
 */

namespace App\Service;


use App\Entity\TodoList;
use Doctrine\Common\Persistence\ObjectManager;

class ListShare
{

    /**
     * @var ObjectManager
     */
    private $manager;

    public function __construct(ObjectManager $manager)
    {
        $this->manager = $manager;
    }

    public function shareList(TodoList $list): void
    {
        if ($list->getToken()) {
            return;
        }

        $list->setToken($this->generateToken($list));
        $this->save($list);
    }

    public function unshareList(TodoList $list): void
    {
        if (!$list->getToken()) {
            return;
        }

        $list->setToken(null);
        $this->save($list);
    }

    private function save(TodoList $list): void
    {
        $this->manager->persist($list);
        $this->manager->flush();
    }

    private function generateToken(TodoList $list)
    {
        return hash('sha512', $list->getId() . mt_rand(1, 9999999) . time());
    }
}