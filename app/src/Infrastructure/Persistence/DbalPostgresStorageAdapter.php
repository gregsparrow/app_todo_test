<?php


namespace App\Infrastructure\Persistence;


use Doctrine\DBAL\Connection;

class DbalPostgresStorageAdapter implements StorageAdapterInterface
{
    /**
     * @var Connection
     */
    private Connection $connection;

    public function __construct(Connection $connection)
    {
        $this->connection = $connection;
    }

    public function find(string $entityName, $id): ?array
    {
        $result = $this->connection->fetchAssociative("select * from {$entityName} where id = :id", ['id' => $id]);

        if ($result == false) {
            $result = null;
        }

        return $result;
    }

    public function findBy()
    {
        // TODO: Implement findBy() method.
    }

    public function create(EntityInterface $entity)
    {
        $table = $entity->getEntityName();
        $entity->setId($this->generateId($table));
        $row = $entity->export();

        $this->connection->insert($table, $row);
    }

    public function update(EntityInterface $entity)
    {
        $table = $entity->getEntityName();
        $row = $entity->export();

        $this->connection->update($table, $row, ['id' => $entity->getId()]);
    }

    public function findAll(string $entityName): array
    {
        $result = $this->connection->fetchAllAssociative("select * from {$entityName}");

        if ($result == false) {
            $result = [];
        }

        return $result;
    }

    public function delete(string $entityName, $id)
    {
        $this->connection->delete($entityName, ['id' => $id]);
    }

    public function getLastId(string $entityName): int
    {
        $result = $this->connection->fetchOne("select max(id) FROM {$entityName}");

        return $result ?? 0;
    }

    public function generateId(string $entityName): int
    {
        return $this->getLastId($entityName) + 1;
    }
}