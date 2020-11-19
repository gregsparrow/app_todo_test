<?php

namespace App\Tests\Domain\Todo;

use App\Domain\Todo\TodoService;
use App\Infrastructure\Persistence\MemoryStorageAdapter;
use App\Infrastructure\Todo\TodoRepository;
use PHPUnit\Framework\TestCase;

class TodoServiceTest extends TestCase
{
    private TodoService $service;

    protected function setUp(): void
    {
        $data['todoitem'] = [
            1 => [
                'id' => 1,
                'name' => 'test',
                'description' => 'test desc',
                'completed' => false,
            ]
        ];
        $storage = new MemoryStorageAdapter($data);
        $todoRepository = new TodoRepository($storage);
        $this->service = new TodoService($todoRepository);
    }

    public function testTodoItemList()
    {
        $list = $this->service->todoItemList();

        $this->assertCount(1, $list);
    }

    public function testCreateTodoItem()
    {
        $this->service->createTodoItem("test name", "test desc", true);
        $list = $this->service->todoItemList();

        $this->assertCount(2, $list);
    }

    public function testDeleteTodoItem()
    {
         $this->service->deleteTodoItem(1);
        $list = $this->service->todoItemList();

        $this->assertCount(0, $list);

    }
}
