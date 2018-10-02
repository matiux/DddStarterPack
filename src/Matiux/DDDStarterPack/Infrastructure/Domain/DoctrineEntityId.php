<?php

namespace DDDStarterPack\Infrastructure\Domain;

use DDDStarterPack\Domain\Model\BasicEntityId;
use Doctrine\DBAL\Platforms\AbstractPlatform;
use Doctrine\DBAL\Types\GuidType;
use DDDStarterPack\Domain\Model\EntityId;

abstract class DoctrineEntityId extends GuidType
{
    public function convertToDatabaseValue($value, AbstractPlatform $platform)
    {
        if (!$value) {
            return null;
        }

        return $value instanceof EntityId ? $value->id() : $value;
    }

    private function isValidUuid($value): bool
    {
        if (is_string($value) && BasicEntityId::isValidUuid($value)) {
            return true;
        }

        return false;
    }

    public function convertToPHPValue($value, AbstractPlatform $platform)
    {
        if (!$this->isValidUuid($value) && !$this->isCustomValid()) {
            return $value;
        }

        $className = $this->getFQCN();

        return $className::create($value);
    }

    abstract protected function getNamespace(): string;

    protected function getFQCN(): string
    {
        return $this->getNamespace() . '\\' . $this->getName();
    }

    protected function isCustomValid(): bool
    {
        return false;
    }
}
