<?php

namespace Gbenitez\Bundle\AttributeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Gbenitez\Bundle\AttributeBundle\Model\AttributeTypes;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Attributes
 *
 * @ORM\Table(name="attribute_system_attribute")
 * @ORM\Entity(repositoryClass="Gbenitez\Bundle\AttributeBundle\Entity\Repository\AttributeRepository")
 *
 */
class Attribute
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="name", type="string", length=255)
     * @Assert\NotBlank(message="Por favor, ingrese un nombre")
     */
    private $name;

    /**
     * @var Region
     * @ORM\ManyToOne(targetEntity="Region", fetch="EAGER")
     * @ORM\JoinColumn(nullable=false)
     * @Assert\NotBlank(message="Por favor, seleccione una región")
     */
    private $region;

    /**
     * @var string
     *
     * @ORM\Column(name="presentation", type="string", length=255)
     * @Assert\NotBlank(message="Por favor, ingrese un nombre para etiqueta")
     */
    private $presentation;

    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=255)
     */
    private $type = AttributeTypes::TEXT;

    /**
     * @var array
     * @ORM\Column(name="configuration", type="array", nullable=true)
     */
    protected $configuration;

    /**
     * @var string
     * @ORM\Column(name="container_class", type="string", nullable=true)
     */
    protected $containerClass;

    /**
     * @var boolean
     *
     * @ORM\Column(name="active", type="boolean")
     */
    private $active = true;

    /**
     * @var string
     *
     * @ORM\Column(name="target_entity", type="string", length=50, nullable=true)
     */
    private $targetEntity;

    /**
     * @ORM\Column(name="position", type="integer" , nullable=true)
     */
    private $position;

    /**
     * @ORM\Column(name="value_template", type="text" , nullable=true)
     */
    private $valueTemplate;

    /**
     * @ORM\Column(name="javascript_code", type="text" , nullable=true)
     */
    private $javascriptCode;

    /**
     * @ORM\Column(name="constraints", type="json_array", nullable=true)
     */
    private $constraints;

    /**
     * @ORM\ManyToOne(targetEntity="Gbenitez\Bundle\AttributeBundle\Entity\AttributeOwnerInterface")
     * @var AttributeOwnerInterface
     */
    protected $owner;

    /**
     * @var \DateTime
     * @ORM\Column(name="created_at", type="datetime")
     */
    protected $createdAt;

    /**
     * @var \DateTime
     * @ORM\Column(
     *      name="updated_at",
     *      type="datetime",
     *      columnDefinition="timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP"
     * )
     */
    protected $updatedAt;

    /**
     * Attribute constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('now');
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     * @return Attribute
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set presentation
     *
     * @param string $presentation
     * @return Attribute
     */
    public function setPresentation($presentation)
    {
        $this->presentation = $presentation;

        return $this;
    }

    /**
     * Get presentation
     *
     * @return string
     */
    public function getPresentation()
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
        throw new \InvalidArgumentException("Est� valor no puede ser establecido manualmente");
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

    /**
     * @return AttributeOwnerInterface
     */
    public function getOwner()
    {
        return $this->owner;
    }

    /**
     * @param AttributeOwnerInterface $owner
     */
    public function setOwner(AttributeOwnerInterface $owner)
    {
        $this->owner = $owner;
    }

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
