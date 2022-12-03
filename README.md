# Carion-Framework

Carion is a simple singleton manager framework.

# Installing

```bash
composer require wallaceosmar/carion-framework
```

## How Use

The access of the values can be due to a object value or array access.
```php

require_once './vendor/autoload.php';

$carion->example = 'example';

$carion['example2'] = 'example2';

echo $cation->example;
echo $carion['example2'];

```

## Seting a singleton

Singleton is used to call some functions or instantiate custom class.

### Setting a singleton to be used later
```php

$carion = new Carion\Carion();

$carion->singleton( 'example', function () {
    return 'example';
});

echo $carion->example;
```

#### Setting a singleton with parameter
The function singleton map the names of the paramter to a
singleton register in the class.
```php

$carion = new Carion\Carion();

$cation->value = 'example';

$carion->singleton( 'example', function ( $value ) {
    return $value;
});

echo $carion->example;
```

If the does`t have a value to the paramter, will be seted null as the value.
```php

$carion = new Carion\Carion();

$carion->singleton( 'example', function ( $value ) {
    if ( 'example' == $value ) {
        $value = md5( $value )';
    }
    return $value;
});

echo $carion->example;
```

## Calling a function or class method

You can call a function or a class method using the function call.

The function call is reponsibly to map all the paramters.
```php
echo $carion->call(function( $value1, $value2 ) {
    return $value1 + $value2;
}, array( 10, 20 ));
```

Parsing a array with key name overwrite the order of values.
```php
echo $carion->call(function( $value1, $value2 ) {
    return $value1 + $value2;
}, array( 10, 20, 'value1' => 0 ));
```
