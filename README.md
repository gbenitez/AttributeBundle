# Documentación Attributes 

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

AttributeValueTargetEntity extenderá de una clase que llamamos **AbstractAttributeValue** y esta implementara una clase interfaz  **AttributeValueInterface** las misma están ubicadas en la carpeta Entity
> Entity/AttributeValueTargetEntity.php
> Entity/AbstractAttributeValue.php

####En nuestro formulario

Se agrega un campo de tipo attributes

```php
->add('attributes', 'attributes')
```

Este tipo de campo lo registramos como un servicio

```yaml
app.form.type.attributes:
        class: AppBundle\Form\Type\AttributesType
        tags:
            - { name: form.type, alias: attributes }
```

la clase **AttributesType.php**

Será la encargada de agregar los attributos a nuestro formType en el evento PRE_SET_DATA

El tipo de campo de cada uno de los attributes se cargara con un nuevo tipo de campo llamado attribute_value

Que registramos como servicio

```yaml
    app.form.type.attribute_value:
        class: AppBundle\Form\Type\AttributeValueType
        arguments:
            - @=service('doctrine.orm.entity_manager').getRepository('AppBundle:Attribute')
        tags:
            - { name: form.type, alias: attribute_value }
```

Esta clase **AttributeValueType.php** será la encargada de cargar las configuraciones de cada attributes

###AttributeTypes.php

Clase modelo que contienen los tipo de atributes

###AttributeAdmin.php
modulo para cargar los attibutes se hizo con un CRUD de sonata Bundle 

###YamlType.php
esta formulario agrega un tipo de campo yaml, para cargar la configuracion de los attrbites y se registra como servicio

```yaml
    app.form.type.yaml:
        class: AppBundle\Form\Type\YamlType
        tags:
            - { name: form.type, alias: yaml }
```

