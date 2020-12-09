<?php


namespace App\Infrastructure\Persistence;


interface StorageAdapterInterface
{
    public function findAll(string $entityName): array;

    public function find(string $entityName, $id): ?array;

    public function create(EntityInterface $entity);

    public function update(EntityInterface $entity);

    public function delete(string $entityName, $id);

    public function getLastId(string $entityName): int;

    public function generateId(string $entityName): int;
}