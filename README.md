# AutoMapper


[![pipeline status](https://gitlab.com/Etshy/automapper/badges/main/pipeline.svg)](https://gitlab.com/Etshy/automapper/-/commits/main) 
[![coverage report](https://gitlab.com/Etshy/automapper/badges/main/coverage.svg)](https://gitlab.com/Etshy/automapper/-/commits/main)

A lightweight library to quickly map an object or an array to another object, greatly inspired by [Automapper-plus](https://github.com/mark-gerarts/automapper-plus). I hope this cover most of you use-cases

This library doesn't use any Reflection !

### Installation

`composer require etshy/automapper`

Future bundle for symfony here

### Usage
 
Here is an example on how to configure a Mapping

```php
<?php

use Etshy\AutoMapper\Configuration\AutoMapperConfiguration;
use Etshy\AutoMapper\AutoMapper;

$config = new AutoMapperConfiguration();

$config
    ->registerMapping(Source::class, Target::class) // -> this is enough if Source and Target have the same property name
    ->forMember('updatedAt', function (Source $source) { // -> use callback function or PropertyMapper on the forMember method
        return $source->getCreatedAt();
    });
                            
$mapper = new AutoMapper($config);

$source = new Source();
$target = $mapper->map($source, Target::class);
// $mapper->mapToObject(source,target); -> use mapToObject() to map to an existing object
// $mapper->mapMultiple([$source],Target::class); -> use mapMultiple to map an iterable of source 

echo $target->getUpdatedAt();
```

### PropertyMapper

`PropertyMapper`s are used to customize a mapping

#### FromCallable
`PropertyMapper::fromCallable(function($souce) {})` 

equivalent to directly using a callback.

#### FromProperty
`Propertymapper::fromProperty('propName')`

Defines from which property you get the data

#### ToClass
`PropertyMapper::toClass($targetClass, $sourceIsObjectArray)`

Defines a class to map the property, useful if you have nested classes. You'll have to register a mapping for the subclasses

#### Ignore
`PropertyMapper::ignore()`

Simply ignore the target property

### Options

#### IgnoreNullProperties/dontIgnoreNullProperties
```php
$mapping = $this->config->registerMapping(Source::class, Target::class);
$mapping->getOptions()->ignoreNullProperties();
```

This option will ignore the source's null properties. Particularly useful when used with  `mapToObject`, but can be useful if you have default value in you Target class

#### setPropertyWriter/setPropertyReader
```php
$mapping = $this->config->registerMapping(Source::class, Target::class);
$mapping->getOptions()->setPropertyWriter(new SwaggerModelPropertyAccessor());
```

This option is useful if you want to use another PropertyReader/PropertyWriter than the default ones (`ObjectPropertyAccessor` and `ArrayPropertyAccessor`). I provide a `SwaggerModelPropertyAccessor` for Swagger generated Models.

And that's it (for now).
Hope this light library can help you !
