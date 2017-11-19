<?php

namespace DddStarterPack\Domain\Model;

use Ramsey\Uuid\Uuid;

abstract class BasicEntityId implements EntityId
{
    private $id;

    private function __construct(?string $anId = null)
    {
        $this->verifyInputId($anId);

        $this->id = (string)$anId ?: Uuid::uuid4()->toString();
    }

    public static function create(?string $anId = null): EntityId
    {
        return new static($anId);
    }

    public function id(): string
    {
        return $this->id;
    }

    public function equals(IdentifiableDomainObject $entity): bool
    {
        return $this->id() === $entity->id()->id();
    }

    private function verifyInputId($anId)
    {
        if (is_object($anId)) {
            throw new \InvalidArgumentException("Entity id input must be scalar type");
        }
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->id();
    }
}
