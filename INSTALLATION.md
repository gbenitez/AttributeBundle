# Instalación y Configuración del Attribute Bundle

## Paso 1: Instalación

```bash
composer require gbenitez/attribute-bundle:^3.0
```

## Paso 2: Registrar el Bundle

En `config/bundles.php`:

```php
<?php

return [
    // ... otros bundles
    Gbenitez\AttributeBundle\GbenitezAttributeBundle::class => ['all' => true],
];
```

## Paso 3: Configurar las Rutas

### Opción A: Cargar rutas del bundle (Recomendado)

En `config/routes.yaml`:

```yaml
gbenitez_attribute:
    resource: "@GbenitezAttributeBundle/config/routing/routing.yaml"
```

### Opción B: Cargar directamente los controladores

En `config/routes.yaml`:

```yaml
gbenitez_attribute_controllers:
    resource: "@GbenitezAttributeBundle/src/Controller/"
    type: attribute
```

## Paso 4: Configurar el Bundle (Opcional)

Si necesitas configurar entidades objetivo, crea `config/packages/gbenitez_attribute.yaml`:

```yaml
gbenitez_attribute:
    target_entities:
        - 'App\Entity\Product'
        - 'App\Entity\Category'
```

## Paso 5: Crear las Tablas de Base de Datos

```bash
# Crear la base de datos (si no existe)
php bin/console doctrine:database:create

# Actualizar el schema
php bin/console doctrine:schema:update --force

# O usar migraciones (recomendado)
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

## Paso 6: Verificar la Instalación

### Verificar que el bundle está registrado:
```bash
php bin/console debug:container | grep -i attribute
```

### Verificar que las rutas están cargadas:
```bash
php bin/console debug:router | grep gbenitez
```

Deberías ver algo como:
```
gbenitez_attribute_list     GET      /admin/attributes/list
gbenitez_attribute_new      GET|POST /admin/attributes/new
gbenitez_attribute_edit     GET|POST /admin/attributes/{id}/edit
gbenitez_attribute_delete   DELETE   /admin/attributes/{id}/delete
```

## Paso 7: Acceder a la Interfaz

Una vez configurado, puedes acceder a:
- Lista de attributes: `http://tu-dominio.com/admin/attributes/list`

## Solución de Problemas

### Error: "No route found"

1. **Verificar que el bundle está registrado:**
   ```bash
   php bin/console debug:container GbenitezAttributeBundle
   ```

2. **Verificar que las rutas están cargadas:**
   ```bash
   php bin/console debug:router
   ```

3. **Limpiar la caché:**
   ```bash
   php bin/console cache:clear
   ```

### Error: "Bundle not found"

Asegúrate de que:
- El bundle está en `vendor/gbenitez/attribute-bundle/`
- Está registrado en `config/bundles.php`
- Has ejecutado `composer install`

### Error: "Template not found"

El bundle incluye sus propias plantillas. Si necesitas personalizarlas, puedes sobrescribirlas en:
- `templates/bundles/GbenitezAttributeBundle/admin/list.html.twig`

## Configuración Avanzada

### Personalizar las Rutas

Si quieres cambiar el prefijo de las rutas, puedes usar:

```yaml
gbenitez_attribute_controllers:
    resource: "@GbenitezAttributeBundle/src/Controller/"
    type: attribute
    prefix: /custom-admin/attributes
```

### Configurar Seguridad

Para proteger las rutas con seguridad, añade en `config/packages/security.yaml`:

```yaml
security:
    access_control:
        - { path: ^/admin/attributes, roles: ROLE_ADMIN }
```
