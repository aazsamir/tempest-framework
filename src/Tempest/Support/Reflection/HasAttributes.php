<?php

declare(strict_types=1);

namespace Tempest\Support\Reflection;

use ReflectionAttribute as PHPReflectionAttribute;
use ReflectionClass as PHPReflectionClass;
use ReflectionMethod as PHPReflectionMethod;
use ReflectionParameter as PHPReflectionParameter;
use ReflectionProperty as PHPReflectionProperty;

trait HasAttributes
{
    abstract public function getReflection(): PHPReflectionClass|PHPReflectionMethod|PHPReflectionProperty|PHPReflectionParameter;

    public function hasAttribute(string $name): bool
    {
        return $this->getReflection()->getAttributes($name) !== [];
    }

    /**
     * @template TAttributeClass of object
     * @param class-string<TAttributeClass> $attributeClass
     * @return TAttributeClass|null
     */
    public function getAttribute(string $attributeClass): object|null
    {
        $attribute = $this->getReflection()->getAttributes($attributeClass, PHPReflectionAttribute::IS_INSTANCEOF)[0] ?? null;

        return $attribute?->newInstance();
    }

    /**
     * @template TAttributeClass of object
     * @param class-string<TAttributeClass> $attributeClass
     * @return TAttributeClass[]
     */
    public function getAttributes(string $attributeClass): array
    {
        return array_map(
            fn (PHPReflectionAttribute $attribute) => $attribute->newInstance(),
            $this->getReflection()->getAttributes($attributeClass, PHPReflectionAttribute::IS_INSTANCEOF)
        );
    }
}
