# Documentación Attributes 

Agregar al composer.json:

```php
"require" : {
   "gbenitez/attribute-bundle": "dev-master"
}
```
Registrar los bundles en el **AppKernel.php**:

```php
public function registerBundles()
{
    $bundles = array(
            new gbenitez\Bundle\AttributeBundle\AttributeBundle(),
            new Knp\Bundle\PaginatorBundle\KnpPaginatorBundle(),
        );
    
    ...
}
```
En el **app/config/routing.yml** agregar:

```yaml
attribute:
    resource: "@AttributeBundle/Controller/"
    type:     annotation
    prefix:   /admin/attributes
``` 
En el **app/config/config.yml** agregar:

```yaml
attribute:
    bundles:
        - AttributeBundle
``` 


Agregar a la bd las tablas del bundle:

    php app/console doctrine:database:create
    php app/console doctrine:schema:update --force


#### Entity Attributes

| column | Descripción |
|--------|--------|
| name   | Nombre del campo    |
|presentation|Label del Campo presentación para el usuario|
|type| Tipo de campo(checkbox, choice, money, number, percent, text, entity)|
|configuration| Representa las opciones del campo|
|active| Indica si el attributo estará activo para el formulario|
|targetEntity| Indicara a que entity se agregara ese attribute|
|position| Es el encargado de mostrar el orden en que se muestre los attribute|

#### Entity AttributeValueTargetEntity
Será la encargada de la relación con attribute y nuestra entidad y obtendrá el valor seleccionado o ingresado

| column | Descripción |
|--------|--------|
|attribute| Id attribute ManyToOne y su targetEntity sera Attribute|
|value|Valor seleccionado o ingresado por el usuario de type array|
|ID TargetEntity| Id de la nuestra entidad será ManyToOne|


> Entity/AttributeValueTargetEntity.php

####En nuestro formulario

Se agrega un campo de tipo attributes

```php
->add('attributes', 'attributes')
```
####Ejemplo de la Entity AttributeValueTargetEntity

```php
<?php

namespace AppBundle\Entity;

use AttributeBundle\Entity\AbstractAttributeValue;
use AttributeBundle\Entity\Attribute;
use AttributeBundle\Model\AttributeTypes;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * AttributeValues
 *
 * @ORM\Table(name="attribute_value_Test_Attribute")
 * @ORM\Entity
 */
class AttributeValueTestAttribute extends AbstractAttributeValue
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
     * @ORM\ManyToOne(targetEntity="gbenitez\Bundle\AttributeBundle\Entity\Attribute")
     * @Assert\NotBlank()
     */
    protected $attribute;

    /**
     * @var array
     *
     * @ORM\Column(name="value", type="array", length=255, nullable=true)
     */
    protected $value;

    /**
     * @var string
     *
     * @ORM\ManyToOne(targetEntity="AppBundle\Entity\TargetEntity")
     * @Assert\NotBlank()
     */
    private $targetEntityAttribute;

    /**
     * AttributeValueCompany constructor.
     *
     * @param string $attribute
     * @param string $company
     */
    public function __construct(Attribute $attribute = null, TargetEntity $targetEntityAttribute = null)
    {
        $this->attribute = $attribute;
        $this->testAttribute = $targetEntityAttribute;
    }


    public function __toString()
    {
        return  json_encode($this->value);
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
     * {@inheritdoc}
     */
    public function getName()
    {
        $this->assertAttributeIsSet();
        return $this->attribute->getName();
    }
    /**
     * {@inheritdoc}
     */
    public function getPresentation()
    {
        $this->assertAttributeIsSet();
        return $this->attribute->getPresentation();
    }
    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        $this->assertAttributeIsSet();
        return $this->attribute->getType();
    }
    /**
     * {@inheritdoc}
     */
    public function getConfiguration()
    {
        $this->assertAttributeIsSet();
        return $this->attribute->getConfiguration();
    }
    /**
     * @throws \BadMethodCallException When attribute is not set
     */
    protected function assertAttributeIsSet()
    {
        if (null === $this->attribute) {
            throw new \BadMethodCallException('The attribute is undefined, so you cannot access proxy methods.');
        }
    }

    /**
     * @param Attribute $attribute
     */
    public function setAttribute(Attribute $attribute)
    {
        $this->attribute = $attribute;
    }
    /**
     * @return string
     * @return \AppBundle\Entity\TargetEntity
     */
    public function getTargetEntityAttribute()
    {
        return $this->targetEntityAttribute;
    }

    /**
     * Get targetEntityAttribute
     *
     * @return \AppBundle\Entity\TargetEntity
     */
    public function setTargetEntityAttribute(\AppBundle\Entity\TargetEntity $targetEntityAttribute)
    {
        $this->targetEntityAttribute = $targetEntityAttribute;
        return $this;
    }

}

```

####Ejemplo de la Entity TargetEntity
```php

/**
     * @var Assert\Collection
     *
     * @ORM\OneToMany(
     *  targetEntity="AppBundle\Entity\AttributeValueTestAttribute",
     *  mappedBy="TargetEntity",
     *  cascade={"all"},
     *  orphanRemoval=true
     * )
     * @Assert\Valid
     */
    private $attributes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    /**
     * Add attributes
     *
     * @param \AppBundle\Entity\AttributeValueTestAttribute $attributes
     *
     * @return Company
     */
    public function addAttributes(\AppBundle\Entity\AttributeValueTestAttribute $attributes)
    {
        $this->attributes[] = $attributes;

        $attributes->setTestAttribute($this);

        return $this;
    }

    /**
     * Remove attributes
     *
     * @param \AppBundle\Entity\AttributeValueTestAttribute $attributes
     */
    public function removeAttributes(\AppBundle\Entity\AttributeValueTestAttribute $attributes)
    {
        $this->attributes->removeElement($attributes);

        $attributes->setTestAttribute(null);
    }

    /**
     * Get attributes
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getAttributes()
    {
        return $this->attributes;
    }

```