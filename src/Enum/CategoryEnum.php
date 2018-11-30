<?php
/**
 * User: jszutkowski
 */

namespace App\Enum;


class CategoryEnum
{
    const MOVIE = 1;
    const BOOK = 2;
    const SHOPPING_ITEM = 3;
    const OTHER = 4;

    private static $names = [
        self::MOVIE => 'category.movie',
        self::BOOK => 'category.book',
        self::SHOPPING_ITEM => 'category.shopping_item',
        self::OTHER => 'category.other'
    ];

    public static function getNames(): array
    {
        return static::$names;
    }

    public static function getName(int $category): string
    {
        return static::$names[$category] ?? '';
    }
}