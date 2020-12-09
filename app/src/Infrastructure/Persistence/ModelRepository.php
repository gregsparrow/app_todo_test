<?php


namespace App\Infrastructure\Persistence;


abstract class ModelRepository
{
    /**
     * @var StorageAdapterInterface
     */
    protected StorageAdapterInterface $storage;

    final public function __construct(StorageAdapterInterface $dbalPostgresStorageAdapter)
    {
        $this->storage = $dbalPostgresStorageAdapter;
    }
}