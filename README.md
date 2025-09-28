#### Documentación Attributes

## Requisitos

- PHP 8.2 o superior
- Symfony 7.0 o superior
- Twig 3.0 o superior

## Instalación

Agregar al composer.json:

```php
"require" : {
   "gbenitez/attribute-bundle": "^3.0"
}
```
Registrar el bundle en **config/bundles.php**:

```php
<?php

return [
    // ... otros bundles
    Gbenitez\AttributeBundle\GbenitezAttributeBundle::class => ['all' => true],
];
```
En **config/routes.yaml** agregar:

```yaml
gbenitez_attribute:
    resource: "@GbenitezAttributeBundle/config/routing/routing.yaml"
```

O alternativamente, puedes cargar directamente los controladores:

```yaml
gbenitez_attribute_controllers:
    resource: "@GbenitezAttributeBundle/src/Controller/"
    type: attribute
```

Agregar a la bd las tablas del bundle:

    php bin/console doctrine:database:create
    php bin/console doctrine:schema:update --force


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


#### Ejemplo de la Entity AttributeValueTargetEntity

```php
<?php

namespace App\Entity;

use Gbenitez\AttributeBundle\Entity\AbstractAttributeValue;
use Gbenitez\AttributeBundle\Entity\Attribute;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity]
#[ORM\Table(name: "attribute_value_target_entity")]
class AttributeValueTargetEntity extends AbstractAttributeValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "AUTO")]
    #[ORM\Column(name: "id", type: "integer")]
    private int $id;

    #[ORM\ManyToOne(targetEntity: Attribute::class)]
    #[Assert\NotBlank()]
    protected ?Attribute $attribute = null;

    #[ORM\Column(name: "value", type: "array", length: 255, nullable: true)]
    protected mixed $value = null;

    #[ORM\ManyToOne(targetEntity: TargetEntity::class)]
    #[Assert\NotBlank()]
    private ?TargetEntity $targetEntityAttribute = null;

    public function __construct(?Attribute $attribute = null, ?TargetEntity $targetEntityAttribute = null)
    {
        $this->attribute = $attribute;
        $this->targetEntityAttribute = $targetEntityAttribute;
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

#### Ejemplo de la Entity TargetEntity
```php

    #[ORM\OneToMany(
        targetEntity: AttributeValueTargetEntity::class,
        mappedBy: "targetEntityAttribute",
        cascade: ["all"],
        orphanRemoval: true
    )]
    #[Assert\Valid]
    private Collection $attributes;

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->attributes = new \Doctrine\Common\Collections\ArrayCollection();
    }
    public function addAttributes(AttributeValueTargetEntity $attributes): self
    {
        $this->attributes[] = $attributes;

        $attributes->setTargetEntityAttribute($this);

        return $this;
    }

    public function removeAttributes(AttributeValueTargetEntity $attributes): void
    {
        $this->attributes->removeElement($attributes);

        $attributes->setTargetEntityAttribute(null);
    }

    public function getAttributes(): Collection
    {
        return $this->attributes;
    }

```
#### Ejemplo de Nuestro controlador para cargar los attribute
```php
/**
     * @Route("/admin/edit/{id}", name="admin_edit")
     *
     */
    public function editAction(Request $request, TargetEntity $targetEntity)
    {
        if (count($targetEntity->getAttributes()) == 0) {
        //repository del attribute entity
            $attrRepoCompany = $this->get('attribute.repository')->findBy(
                array(
                    'active' => 1
                ),
                array('position' => 'ASC')
            );
            foreach ($attrRepoCompany as $attributeTargetEntity) {
                $targetEntity->addAttributes(new AttributeValueTargetEntity($attributeTargetEntity, $targetEntity));
            }
        }

        $form = $this->createForm(new TargetEntityType(), $targetEntity, array(
            'action' => $request->getRequestUri(),
        ))->handleRequest($request);

        if ($form->isSubmitted() and $form->isValid()) {

            $this->getDoctrine()->getEntityManager()->persist($targetEntity);
            $this->getDoctrine()->getEntityManager()->flush();

            return $this->redirectToRoute('homepage');
        }

        return $this->render('default/index.html.twig' , array('form' => $form->createView()) );
    }

```
#### En nuestro formulario TargetEntityType()

Se agrega un campo de tipo attributes

```php
->add('attributes', 'attributes')
```

#### CRUD para la carga de attributes

admin/attributes/list

#### Ejemplo para la configuración de un campo type choice

```yaml
choices:
    1: label.yes
    0: label.no
multiple: false
expanded: true
required: true
```

#### Ejemplo para la configuración de un campo type entity

```yaml
class: AppBundle\Entity\Country
```

#### Para definir la relación con el Owner de un Attribute

- Implementar en la entidad relación la interface:

gbenitez\Bundle\AttributeBundle\Entity\AttributeOwnerInterface

- agregar al archivo app/config/config.yml :

```yaml
# app/config/config.yml
doctrine:
    # ...
    orm:
        # ...
        resolve_target_entities:
            gbenitez\Bundle\AttributeBundle\Entity\AttributeOwnerInterface: Acme\AppBundle\Entity\EntidadRelacion
```

#### Para definir targetEntity

```yaml
# app/config/config.yml
attribute:
    target_entities:
        - 'Entity'
        - 'AnotherEntity'
```
