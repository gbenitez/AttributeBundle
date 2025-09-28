# Solución de Problemas - Attribute Bundle

## Error: "No route found for GET /admin/attributes/list"

Este error indica que las rutas del bundle no se están cargando correctamente. Sigue estos pasos para solucionarlo:

### 1. Verificar que el Bundle está Registrado

En tu aplicación principal, verifica `config/bundles.php`:

```php
<?php

return [
    // ... otros bundles
    Gbenitez\AttributeBundle\GbenitezAttributeBundle::class => ['all' => true],
];
```

**Comando para verificar:**
```bash
php bin/console debug:container | grep -i GbenitezAttribute
```

### 2. Configurar las Rutas Correctamente

En `config/routes.yaml` de tu aplicación principal, añade:

```yaml
gbenitez_attribute:
    resource: "@GbenitezAttributeBundle/config/routing/routing.yaml"
```

**O alternativamente:**

```yaml
gbenitez_attribute_controllers:
    resource: "@GbenitezAttributeBundle/src/Controller/"
    type: attribute
```

### 3. Verificar que las Rutas se Cargan

```bash
php bin/console debug:router | grep gbenitez
```

**Resultado esperado:**
```
gbenitez_attribute_list     GET      /admin/attributes/list
gbenitez_attribute_new      GET|POST /admin/attributes/new
gbenitez_attribute_edit     GET|POST /admin/attributes/{id}/edit
gbenitez_attribute_delete   DELETE   /admin/attributes/{id}/delete
```

### 4. Limpiar la Caché

```bash
php bin/console cache:clear
```

### 5. Ejecutar Diagnóstico del Bundle

Si has instalado el comando de diagnóstico:

```bash
php bin/console gbenitez:attribute:diagnostic
```

## Pasos de Verificación Completos

### Paso 1: Verificar Instalación
```bash
# Verificar que el bundle está instalado
composer show gbenitez/attribute-bundle

# Verificar que está en vendor/
ls -la vendor/gbenitez/attribute-bundle/
```

### Paso 2: Verificar Configuración
```bash
# Verificar bundles registrados
php bin/console debug:container --parameters | grep kernel.bundles

# Verificar rutas cargadas
php bin/console debug:router --show-controllers
```

### Paso 3: Verificar Permisos y Archivos
```bash
# Verificar que los archivos existen
ls -la vendor/gbenitez/attribute-bundle/src/Controller/AttributeController.php
ls -la vendor/gbenitez/attribute-bundle/config/routing/routing.yaml
```

## Configuraciones Comunes que Causan Problemas

### 1. Configuración Incorrecta de Rutas

❌ **Incorrecto:**
```yaml
# config/routes.yaml
gbenitez_attribute:
    resource: "GbenitezAttributeBundle/Controller/"  # Falta @
    type: annotation  # Debería ser 'attribute'
```

✅ **Correcto:**
```yaml
# config/routes.yaml
gbenitez_attribute:
    resource: "@GbenitezAttributeBundle/config/routing/routing.yaml"
```

### 2. Bundle No Registrado

❌ **Incorrecto:**
```php
// config/bundles.php - Bundle faltante
return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    // Falta GbenitezAttributeBundle
];
```

✅ **Correcto:**
```php
// config/bundles.php
return [
    Symfony\Bundle\FrameworkBundle\FrameworkBundle::class => ['all' => true],
    Gbenitez\AttributeBundle\GbenitezAttributeBundle::class => ['all' => true],
];
```

### 3. Caché No Limpiada

Después de cambios en configuración, siempre limpia la caché:
```bash
php bin/console cache:clear
```

## Si el Problema Persiste

### Opción 1: Configuración Manual de Rutas

En `config/routes.yaml`:

```yaml
gbenitez_attribute_list:
    path: /admin/attributes/list
    controller: Gbenitez\AttributeBundle\Controller\AttributeController::listAction
    methods: [GET]

gbenitez_attribute_new:
    path: /admin/attributes/new
    controller: Gbenitez\AttributeBundle\Controller\AttributeController::newAction
    methods: [GET, POST]

gbenitez_attribute_edit:
    path: /admin/attributes/{id}/edit
    controller: Gbenitez\AttributeBundle\Controller\AttributeController::editAction
    methods: [GET, POST]
    requirements:
        id: '\d+'

gbenitez_attribute_delete:
    path: /admin/attributes/{id}/delete
    controller: Gbenitez\AttributeBundle\Controller\AttributeController::deleteAction
    methods: [DELETE]
    requirements:
        id: '\d+'
```

### Opción 2: Verificar Versión de Symfony

El bundle requiere Symfony 7.0+. Verifica tu versión:

```bash
php bin/console about
```

### Opción 3: Modo Debug

Activa el modo debug para ver más información:

```bash
# En .env
APP_ENV=dev
APP_DEBUG=true
```

## Contacto

Si sigues teniendo problemas después de seguir estos pasos, proporciona:

1. Resultado de `php bin/console debug:router`
2. Contenido de tu `config/bundles.php`
3. Contenido de tu `config/routes.yaml`
4. Resultado de `composer show gbenitez/attribute-bundle`
5. Versión de Symfony: `php bin/console about`
