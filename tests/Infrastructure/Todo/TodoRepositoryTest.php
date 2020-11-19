<?php

namespace App\Tests\Infrastructure\Todo;

use App\Domain\Todo\TodoFactory;
use App\Infrastructure\Persistence\MemoryStorageAdapter;
use App\Infrastructure\Todo\TodoRepository;
use PHPUnit\Framework\TestCase;

class TodoRepositoryTest extends TestCase
{
    private TodoRepository $todoRepository;

    protected function setUp(): void
    {
        $data['todoitem'] = [
            1 => [
                'id' => 1,
                'name' => 'test',
                'description' => 'test desc',
                'completed' => false,
            ],
        ];
        $storage = new MemoryStorageAdapter($data);
        $this->todoRepository = new TodoRepository($storage);
    }

    public function testGetTodoItems()
    {
        $list = $this->todoRepository->getTodoItems();

        $this->assertCount(1, $list);
    }

    public function testRemoveTodoItem()
    {
        $item = $this->todoRepository->getTodoItem(1);
        $this->todoRepository->removeTodoItem($item);

        $list = $this->todoRepository->getTodoItems();

        $this->assertCount(0, $list);
    }

    public function testGetTodoItem()
    {
        $item = $this->todoRepository->getTodoItem(1);

        $this->assertEquals(1, $item->getId());
        $this->assertEquals('test', $item->getName());
        $this->assertEquals('test desc', $item->getDescription());
        $this->assertEquals(false, $item->isCompleted());
    }

    public function testGetTodoItemThrowException()
    {
        $id = 2;
        $this->expectException(\InvalidArgumentException::class);
        $this->expectExceptionMessage("TodoItem {$id} not found");

        $item = $this->todoRepository->getTodoItem($id);
    }

    public function testAddTodoItem()
    {
        $obj = TodoFactory::createTodoItem('new name', 'new desc', true);

        $this->assertNull($obj->getId());
        $this->todoRepository->addTodoItem($obj);
        $this->assertEquals(2, $obj->getId());
    }
}
