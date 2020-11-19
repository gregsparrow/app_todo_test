<?php


namespace App\Infrastructure\Persistence;


interface EntityInterface
{
    public function getId();

    public function setId($id);

    public function getEntityName(): string;

    public function import(array $row);

    public function export(): array;
}