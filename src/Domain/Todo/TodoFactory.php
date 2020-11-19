<?php


namespace App\Domain\Todo;


use App\Domain\Todo\Model\TodoItem;

class TodoFactory
{
    public static function createTodoItem(string $name = '', string $description = '', bool $completed = false)
    {
        $obj = new TodoItem();
        $obj->setId(null);
        $obj->setName($name);
        $obj->setDescription($description);
        $obj->setCompleted($completed);

        return $obj;
    }
}