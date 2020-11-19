<?php


namespace App\Infrastructure\Persistence;


class MemoryStorageAdapter implements StorageAdapterInterface
{
    private array $data;

    public function __construct(array $data = [])
    {
        $this->data = $data;
    }

    public function findAll(string $entityName): array
    {
        return $this->data[$entityName];
    }

    public function find(string $entityName, $id): ?array
    {
        return $this->data[$entityName][$id] ?? null;
    }

    public function create(EntityInterface $entity)
    {
        $entityName = $entity->getEntityName();
        $entity->setId($this->generateId($entityName));
        $row = $entity->export();

        $this->data[$entityName][$entity->getId()] = $row;
    }

    public function update(EntityInterface $entity)
    {
        $this->data[$entity->getEntityName()][$entity->getId()] = $entity->export();
    }

    public function delete(string $entityName, $id)
    {
        unset($this->data[$entityName][$id]);
    }

    public function getLastId(string $entityName): int
    {
        return array_key_last($this->data[$entityName]) ?? 0;
    }

    public function generateId(string $entityName): int
    {
        return $this->getLastId($entityName) + 1;
    }
}