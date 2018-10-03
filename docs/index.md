---
layout: page
permalink: index.html
---

# <a name="introduccion"></a> Introduccion

Config es un componente que permite cargar, combinar y acceder de forma sencilla a los datos de configuracion de una aplicacion.

Los archivos de configuracion pueden estar en distintos formatos, actualmente se proporciona adaptadores para archivo JSON, PHP y arrays de PHP.

[![Build Status](https://travis-ci.org/mostofreddy/smarttly_config.svg?branch=master)](https://travis-ci.org/mostofreddy/smarttly_config)
[![Coverage Status](https://coveralls.io/repos/github/mostofreddy/smarttly_config/badge.svg?branch=master)](https://coveralls.io/github/mostofreddy/smarttly_config?branch=master)
[![Latest Stable Version](https://poser.pugx.org/smarttly/config/v/stable)](https://packagist.org/packages/smarttly/config)
[![Total Downloads](https://poser.pugx.org/smarttly/config/downloads)](https://packagist.org/packages/smarttly/config)
[![License](https://poser.pugx.org/smarttly/config/license)](https://packagist.org/packages/smarttly/config)
[![composer.lock](https://poser.pugx.org/smarttly/config/composerlock)](https://packagist.org/packages/smarttly/config)

-- ---

# <a name="funcionalidades"></a> Funcionalidades

## [1.1.0]

* Metodo has() en clase Config
* Metodo toArray() en clase Config

## [1.0.0]

* Interfaz sencilla para acceder a los valores de configuracion
* Adaptadores para cargar archivos JSON y PHP
* Merge anidados de configuraciones
* Acceso a la configuracion mediante formato array (ArrayAccess interface)
* Acceso a la configuracion mediente formato iterator (Iterator interface)
* Clase ConfigFactory para instanciar Config cargando los archivos de configuraicon de un directorio

-- ---

# <a name="requerimientos"></a> Requerimientos

* PHP 7.1+.

-- ---

# <a name="instalacion"></a> Instalacion

Via Composer, agregando en la sección `require` del archivo `composer.json`

```json
{
    "require": {
        "smarttly/config": "1.1.*"
    }
}
```

Luego correr el comando `composer install` o `composer update` para instalar el componente

-- ---

# <a name="uso"></a> Uso

El uso mas sencillo del componente es instanciando la clase `Config` y pasarle un array de configuracion

```php
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

## <span class="numeral">\#</span> Acceder a los valores de configuracion

Para acceder a los valores de configuracion se puede realizar mediante el metodo `get` o a travez del nombre de la variable.

```php
$config->get('name');
// print: John

$config->name;
// print: John
```

### \- Acceder a valores aniddados

Cuando un valor de configuracion es del tipo `array` automaticamente es transformado a una instancia de `Config` para facilitar el acceso y el merge de configuraciones

```php
echo $config->get('database')->get('driver');
// print: mongodb

echo $config->database->get('driver');
// print: mongodb

echo $config->database->driver;
// print: mongodb
```

## <span class="numeral">\#</span> Validar si una configuracion existe

```php
$config->has('database');
// true

$config->database->has('driver');
// true

$config->has('fail');
// false
```

## <span class="numeral">\#</span> Transformar la configuracion a un array simple

```php
$defaultConfiguration = [
    'name' => 'John',
    'lastname' => 'Doe',
    'database' => [
        'driver' => 'mongodb'
    ]
];

$config = new Config($defaultConfiguration);

echo print_r($config->toArray(), true);
// print:
//Array
//(
//    [name] => John
//    [lastname] => Doe
//    [database] => Array
//        (
//            [driver] => mongodb
//        )
//
//    [func] => Callable function
//)

echo print_r($config->database->toArray(), true);
// print:
//Array
//(
//    [driver] => mongodb
//)

```

## <span class="numeral">\#</span> Cargar configuracion desde archivos

Los archivos externos pueden ser JSON o PHP

### \- Leer archivos JSON

```json
// config.json
{
    "say": "hello"
}
```

```php
use Smarttly\Config\Reader\Json;
use Smarttly\Config\Config;

$reader = new Json();
$defaultConfiguration = $reader->fromFile('config.json);

$config = new Config($defaultConfiguration);

$config->get('say');
// print: hello
```

### \- Leer archivos PHP

```php
// config.php
$config = [
    'say' => 'hello'
];
return $config;
```

```php
use Smarttly\Config\Reader\Php;
use Smarttly\Config\Config;

$reader = new Php();
$defaultConfiguration = $reader->fromFile('config.php);

$config = new Config($defaultConfiguration);

$config->get('say');
// print: hello
```

## <span class="numeral">\#</span> Combinar archivos de configuracion

Config permite combinar distintas configuraciones en una sola. Esto es muy util para dividir la configuracion en distintos archivos (uno para los logs, otro para la base de datos, etc) y tambien cuando contamos con configuraciones por defecto y particulares de un ambiente (produccion, test, development, etc)

```php
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

## <span class="numeral">\#</span> Cargar/ cambiar valores de configuracion

```php
use Smarttly\Config\Config;

$defaultConfiguration = [
    'name' => 'John',
    'lastname' => 'Doe',
    'database' => [
        'driver' => 'mongodb'
    ]
];

$config = new Config($defaultConfiguration);

$config->set('lastname', 'Smith');
echo $config->lastname;
// print: Smith
```

## <span class="numeral">\#</span> Clase Factory

La clase ConfigFactory permite instanciar la configuracion cargando un set de archivos

```php
$files = [
    'path/to/file1.php',
    'path/to/file2.php',
    'path/to/file3.json',
];

$config = (ConfigFactory::create(...$files))
    ->getConfig();

// or

$config = (ConfigFactory::create('path/to/file1.php', 'path/to/file2.php', 'path/to/file3.json'))
    ->getConfig();

// or

$factory = ConfigFactory::create();
$config = $factory->loadFiles(...$files)
    ->getConfig();
```

-- ---

# <a name="roadmap"></a> Roadmap

Pendientes a desarrollar

* Leer configuracion desde archivios INI
* Leer configuracion desde archivios YAML
* Leer configuracion desde archivo .env
* Posibilidad de cargar un directorio
* Notacion por puntos para acceder a configuraciones
* Cache

<br/>
