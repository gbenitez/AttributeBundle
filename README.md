# Documentación Attributes 

Registrar los bundles en el **AppKernel.php**:

```php
public function registerBundles()
{
    $bundles = array(
            new AttributeBundle\AttributeBundle(),
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

