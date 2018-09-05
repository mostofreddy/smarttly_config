---
layout: page
permalink: index.html
---

# <a name="introduccion">\#</a> Introduccion

Config es un componente que permite cargar, combinar y acceder de forma sencilla a los datos de configuracion de una aplicacion.

Los archivos de configuracion pueden estar en distintos formatos, actualmente se proporciona adaptadores para archivo JSON, PHP y arrays de PHP.

# <a name="funcionalidades">\#</a> Funcionalidades

* Interfaz sencilla para acceder a los valores de configuracion
* Adaptadores para cargar archivos JSON y PHP
* Merge anidados de configuraciones

# <a name="requerimientos">\#</a> Requerimientos

* PHP 7.2+.

# <a name="instalacion">\#</a> Instalacion

Via Composer, agregando en la sección `require` del archivo `composer.json`

```
{
    "require": {
        "smarttly/config": "*"
    }
}
```

Luego correr el comando `composer update` para instalar el componente


# <a name="uso">\#</a> Uso

El uso mas sencillo del componente es instanciando la clase `Config` pasandole un array de configuracion

```
use Smarttly\Config\Config;

$defaultConfiguration = [
    'name' => 'John',
    'lastname' => 'Doe',
    'database' => [
        'driver' => 'mongodb'
    ]
];

$config = new Config($defaultConfiguration);

$config->get('name');
// print: John
```

## Como acceder a los valores de configuracion

Para acceder a los valores de configuracion se puede realizar mediante el metodo `get` o a travez del nombre de la variable.

```
$config->get('name');
// print: John

$config->name;
// print: John
```

**Acceder a un valor del tipo array**

Cuando un valor de configuracion es del tipo `array` automaticamente es transformado a una instancia de `Config` para facilitar el acceso y el merge de configuraciones

```
echo $config->get('database')->get('driver');
// print: mongodb

echo $config->database->get('driver');
// print: mongodb

echo $config->database->driver;
// print: mongodb
```

## Cargar configuracion desde archivo

Los archivos externos pueden ser JSON o PHP

### Leer archivos JSON

```
// archivo config.json
{
    "say": "hello"
}
```

```
use Smarttly\Config\Reader\Json;
use Smarttly\Config\Config;

$reader = new Json();
$defaultConfiguration = $reader->fromFile('config.json);

$config = new Config($defaultConfiguration);

$config->get('say');
// print: hello
```

### Leer archivos PHP

```
// archivo config.php
<?php
$config = [
    'say' => 'hello'
];
return $config;
```

```
use Smarttly\Config\Reader\Php;
use Smarttly\Config\Config;

$reader = new Php();
$defaultConfiguration = $reader->fromFile('config.php);

$config = new Config($defaultConfiguration);

$config->get('say');
// print: hello
```

## Combinar archivos de configuracion

Config permite combinar distintas configuraciones en una sola. Esto es muy util para dividir la configuracion en distintos archivos (uno para los logs, otro para la base de datos, etc) y tambien cuando contamos con configuraciones por defecto y particulares de un ambiente (produccion, test, development, etc)

```
$data1 = [
    'name' => 'John',
    'lastname' => 'Doe'
];
$config1 = new Config($data1);

$data2 = [
    'lastname' => 'Smith',
    'database' => [
        'driver' => 'mongodb'
    ]
];
$config2 = new Config($data2);

$config1->merge($config2);

echo $config1->lastname;
// print: Smith

echo $config1->name;
// print: John

echo $config1->database->driver;
// print: mongodb
```

<br/><br/>
