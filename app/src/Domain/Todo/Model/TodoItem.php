<?php


namespace App\Domain\Todo\Model;


use App\Infrastructure\Persistence\EntityInterface;

class TodoItem implements EntityInterface
{
    private $id;
    private ?string $name;
    private ?string $description;
    private bool $completed;

    /**
     * @return string
     */
    public function getName(): ?string
    {
        return $this->name;
    }

    /**
     * @param string|null $name
     */
    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     */
    public function setDescription(?string $description): void
    {
        $this->description = $description;
    }

    /**
     * @return bool
     */
    public function isCompleted(): bool
    {
        return $this->completed;
    }

    /**
     * @param bool $completed
     */
    public function setCompleted(bool $completed): void
    {
        $this->completed = $completed;
    }

    public function getId()
    {
        return $this->id;
    }

    public function setId($id)
    {
        $this->id = $id;
    }

    public function getEntityName(): string
    {
        return 'todoitem';
    }

    public function import(array $row)
    {
        $this->setId($row['id'] ?? $this->getId());
        $this->setName($row['name'] ?? $this->getName());
        $this->setDescription($row['description'] ?? $this->getDescription());
        $this->setCompleted((bool)$row['completed'] ?? $this->isCompleted());
    }

    public function export(): array
    {
        return [
            'id' => $this->getId(),
            'name' => $this->getName(),
            'description' => $this->getDescription(),
            'completed' => var_export($this->isCompleted(), true),
        ];
    }
}