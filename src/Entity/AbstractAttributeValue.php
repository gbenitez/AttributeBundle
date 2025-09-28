<?php

namespace Gbenitez\AttributeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @author Manuel Aguirre <programador.manuel@gmail.com>
 */
#[ORM\MappedSuperclass]
abstract class AbstractAttributeValue implements AttributeValueInterface
{
    #[ORM\ManyToOne(targetEntity: Attribute::class, fetch: "EAGER")]
    protected ?Attribute $attribute = null;

    #[ORM\Column(name: "value", type: "json", nullable: true)]
    protected mixed $value = null;

    public function getAttribute(): ?Attribute
    {
        return $this->attribute;
    }

    public function setAttribute(Attribute $attribute): void
    {
        $this->attribute = $attribute;
    }

    public function getValue(): mixed
    {
        return $this->value;
    }

    public function setValue(mixed $value): void
    {
        $this->value = $value;
    }
}
