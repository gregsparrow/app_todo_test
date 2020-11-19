<?php


namespace App\Domain\Todo;


use App\Domain\Todo\Model\TodoItem;

interface TodoRepositoryInterface
{
    public function getTodoItem($id): TodoItem;

    /**
     * @return array|TodoItem[]
     */
    public function getTodoItems(): array;

    public function addTodoItem(TodoItem $obj);

    public function removeTodoItem(TodoItem $obj);
}