<?php


namespace App\Infrastructure\Todo;


use App\Domain\Todo\Model\TodoItem;
use App\Domain\Todo\TodoRepositoryInterface;
use App\Infrastructure\Persistence\ModelRepository;

class TodoRepository extends ModelRepository implements TodoRepositoryInterface
{
    public function getTodoItem($id): TodoItem
    {
        $row = $this->storage->find((new TodoItem())->getEntityName(), $id);

        if ($row == null) {
            throw new \InvalidArgumentException("TodoItem {$id} not found");
        }

        $obj = new TodoItem();
        $obj->import($row);

        return $obj;
    }

    public function getTodoItems(): array
    {
        $rows = $this->storage->findAll((new TodoItem())->getEntityName());
        $items = [];
        foreach ($rows as $row) {
            $obj = new TodoItem();
            $obj->import($row);
            $items[] = $obj;
        }

        return $items;
    }

    public function addTodoItem(TodoItem $obj)
    {
        if (empty($obj->getId())) {
            $this->storage->create($obj);
        } else {
            $this->storage->update($obj);
        }
    }

    public function removeTodoItem(TodoItem $obj)
    {
        $this->storage->delete($obj->getEntityName(), $obj->getId());
    }
}