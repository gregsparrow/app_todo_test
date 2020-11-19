<?php


namespace App\Domain\Todo;


class TodoService
{
    /**
     * @var TodoRepositoryInterface
     */
    private TodoRepositoryInterface $todoRepository;

    public function __construct(TodoRepositoryInterface $todoRepository)
    {
        $this->todoRepository = $todoRepository;
    }

    public function todoItemList()
    {
        return $this->todoRepository->getTodoItems();
    }

    public function createTodoItem(string $name, string $description, bool $completed)
    {
        $obj = TodoFactory::createTodoItem($name, $description, $completed);
        $this->todoRepository->addTodoItem($obj);
    }

    public function deleteTodoItem(int $id)
    {
        $obj = $this->todoRepository->getTodoItem($id);
        $this->todoRepository->removeTodoItem($obj);
    }

    public function updateTodoItem(int $id, array $data)
    {
        $obj = $this->todoRepository->getTodoItem($id);
        $obj->import($data);
        $this->todoRepository->addTodoItem($obj);
    }
}