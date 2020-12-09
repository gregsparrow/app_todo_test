<?php

namespace App\Controller;

use App\Domain\Todo\TodoService;
use JMS\Serializer\Serializer;
use JMS\Serializer\SerializerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\NameConverter\CamelCaseToSnakeCaseNameConverter;
use Symfony\Component\Validator\ConstraintViolation;

class TodoController extends AbstractController
{
    private TodoService $service;
    private SerializerInterface $serializer;

    public function __construct(TodoService $service, SerializerInterface $serializer)
    {
        $this->service = $service;
        $this->serializer = $serializer;
    }

    /**
     * @Route("/api/todo_items", name="todo_items_list", methods={"GET"})
     * @return JsonResponse
     */
    public function todoItems()
    {
        $data = $this->service->todoItemList();

        return $this->success($this->serializer, $data);
    }

    /**
     * @Route("/api/todo_items", name="todo_items_add", methods={"POST"})
     * @param Request $request
     * @return JsonResponse
     */
    public function todoItemsAdd(Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->service->createTodoItem($data['name'], $data['description'], false);

        $data = $this->service->todoItemList();

        return $this->success($this->serializer, $data);
    }

    /**
     * @Route("/api/todo_items/{id}", name="todo_items_delete", methods={"DELETE"})
     * @param int $id
     * @return JsonResponse
     */
    public function todoItemDelete(int $id)
    {
        $this->service->deleteTodoItem($id);
        $data = $this->service->todoItemList();

        return $this->success($this->serializer, $data);
    }

    /**
     * @Route("/api/todo_items/{id}", name="todo_items_update", methods={"PUT"})
     * @param int $id
     * @return JsonResponse
     */
    public function todoItemUpdate(int $id, Request $request)
    {
        $data = json_decode($request->getContent(), true);
        $this->service->updateTodoItem($id, $data);

        $data = $this->service->todoItemList();

        return $this->success($this->serializer, $data);
    }

    private function success(SerializerInterface $serializer, $result): JsonResponse
    {
        return new JsonResponse($serializer->serialize($result, 'json'), 200, [], true);
    }


    private function failure(iterable $errors, int $status = 500): JsonResponse
    {
        $formattedErrors = [];

        foreach ($errors as $name => $error) {
            $isViolation = $error instanceof ConstraintViolation;
            $property = $isViolation ? (new CamelCaseToSnakeCaseNameConverter())->normalize($error->getPropertyPath()) : $name;

            $formattedErrors[$property][] = $isViolation ? $error->getMessage() : $error;
        }

        return new JsonResponse($formattedErrors, $status);
    }
}
