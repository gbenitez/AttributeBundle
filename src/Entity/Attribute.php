<?php

namespace Gbenitez\AttributeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gbenitez\AttributeBundle\Model\AttributeTypes;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: "Gbenitez\AttributeBundle\Entity\Repository\AttributeRepository")]
#[ORM\Table(name: "attribute_system_attribute")]
class Attribute
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(name: "id", type: "integer")]
    private int $id;

    #[ORM\Column(name: "name", type: "string", length: 255)]
    #[Assert\NotBlank(message: "Por favor, ingrese un nombre")]
    private string $name;

    #[ORM\ManyToOne(targetEntity: Region::class, fetch: "EAGER")]
    #[ORM\JoinColumn(nullable: true)]
    private ?Region $region = null;

    #[ORM\Column(name: "presentation", type: "string", length: 255)]
    #[Assert\NotBlank(message: "Por favor, ingrese un nombre para etiqueta")]
    private string $presentation;

    #[ORM\Column(name: "type", type: "string", length: 255)]
    private string $type = AttributeTypes::TEXT;

    #[ORM\Column(name: "configuration", type: "json", nullable: true)]
    protected ?array $configuration = null;

    #[ORM\Column(name: "container_class", type: "string", nullable: true)]
    protected ?string $containerClass = null;

    #[ORM\Column(name: "active", type: "boolean")]
    private bool $active = true;

    #[ORM\Column(name: "target_entity", type: "string", length: 50, nullable: true)]
    private ?string $targetEntity = null;

    #[ORM\Column(name: "position", type: "integer", nullable: true)]
    private ?int $position = null;

    #[ORM\Column(name: "value_template", type: "text", nullable: true)]
    private ?string $valueTemplate = null;

    #[ORM\Column(name: "javascript_code", type: "text", nullable: true)]
    private ?string $javascriptCode = null;

    #[ORM\Column(name: "constraints", type: "json", nullable: true)]
    private ?array $constraints = null;

    // Commented out temporarily - requires resolve_target_entities configuration
    // #[ORM\ManyToOne(targetEntity: AttributeOwnerInterface::class)]
    // protected ?AttributeOwnerInterface $owner = null;

    #[ORM\Column(name: "created_at", type: "datetime")]
    protected \DateTime $createdAt;

    #[ORM\Column(name: "updated_at", type: "datetime", nullable: true)]
    protected ?\DateTime $updatedAt = null;

    /**
     * Attribute constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setPresentation(string $presentation): self
    {
        $this->presentation = $presentation;

        return $this;
    }

    public function getPresentation(): string
    {
        return $this->presentation;
    }

    /**
     * Set type
     *
     * @param integer $type
     * @return Attribute
     */
    public function setType($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * Get type
     *
     * @return integer
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Get configuration
     *
     * @return array
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }
    /**
     * Set configuration
     *
     * @param array $configuration
     * @return Attribute
     */
    public function setConfiguration($configuration)
    {
        $this->configuration = $configuration;
        return $this;
    }


    public function __toString()
    {
        return $this->name;
    }
    /**
     * @return boolean
     */
    public function isActive()
    {
        return $this->active;
    }
    /**
     * Set active
     *
     * @param boolean $active
     *
     * @return Attribute
     */
    public function setActive($active)
    {
        $this->active = $active;

        return $this;
    }

    /**
     * Get active
     *
     * @return boolean
     */
    public function getActive()
    {
        return $this->active;
    }

    /**
     * @return string
     */
    public function getTargetEntity()
    {
        return $this->targetEntity;
    }

    /**
     * @param string $targetEntity
     */
    public function setTargetEntity($targetEntity)
    {
        $this->targetEntity = $targetEntity;
    }

    /**
     * @return mixed
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * @param mixed $position
     */
    public function setPosition($position)
    {
        $this->position = $position;
    }

    /**
     * Sets createdAt.
     *
     * @param  \DateTime $createdAt
     * @return $this
     */
    public function setCreatedAt(\DateTime $createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Returns createdAt.
     *
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Sets updatedAt.
     *
     * @param  \DateTime $updatedAt
     * @return $this
     */
    private function setUpdatedAt(\DateTime $updatedAt)
    {
        throw new \InvalidArgumentException("Est valor no puede ser establecido manualmente");
    }

    /**
     * Returns updatedAt.
     *
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @return mixed
     */
    public function getJavascriptCode()
    {
        return $this->javascriptCode;
    }

    /**
     * @param mixed $javascriptCode
     */
    public function setJavascriptCode($javascriptCode)
    {
        $this->javascriptCode = $javascriptCode;
    }

    /**
     * @return mixed
     */
    public function getConstraints()
    {
        return $this->constraints;
    }

    /**
     * @param mixed $constraints
     */
    public function setConstraints($constraints)
    {
        $this->constraints = $constraints;
    }

    // Commented out temporarily - requires resolve_target_entities configuration
    /*
    public function getOwner(): ?AttributeOwnerInterface
    {
        return $this->owner;
    }

    public function setOwner(?AttributeOwnerInterface $owner): self
    {
        $this->owner = $owner;
        return $this;
    }
    */

    /**
     * @return Region
     */
    public function getRegion()
    {
        return $this->region;
    }

    /**
     * @param Region $region
     */
    public function setRegion(Region $region)
    {
        $this->region = $region;
    }

    /**
     * @return string
     */
    public function getContainerClass()
    {
        return $this->containerClass;
    }

    /**
     * @param string $containerClass
     */
    public function setContainerClass($containerClass)
    {
        $this->containerClass = $containerClass;
    }

    /**
     * @return mixed
     */
    public function getValueTemplate()
    {
        return $this->valueTemplate;
    }

    /**
     * @param mixed $valueTemplate
     */
    public function setValueTemplate($valueTemplate)
    {
        $this->valueTemplate = $valueTemplate;
    }
}
