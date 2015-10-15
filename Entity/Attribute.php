<?php

namespace gbenitez\Bundle\AttributeBundle\tributeBundle\Entity;

use AttributeBundle\Model\AttributeTypes;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Attributes
 *
 * @ORM\Table(name="attribute")
 * @ORM\Entity(repositoryClass="AttributeBundle\Entity\Repository\AttributeRepository")
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
     * @return Attributes
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
     * @return Attributes
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
     * @return Attributes
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
     * @return Attributes
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
     * @return Term
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
        throw new \InvalidArgumentException("Esté valor no puede ser establecido manualmente");
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
}
