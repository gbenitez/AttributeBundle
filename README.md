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

