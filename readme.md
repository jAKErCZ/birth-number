# Birth Number

Slovak Birth Number utility _(sk: Rodné Číslo)_

## Instalation

`composer require jakercz/birth-number`

## Defining a Birth Number

```php
use DevFusion\BirthNumber;

$bn1 = new BirthNumber(9707192649);
	
$bn2 = (new BirthNumber())
	->createFromNumber(9707192649);
	
$bn3 = (new BirthNumber())
	->createFromDate(1997, 7, 19, 2649, BirthNumber::GENDER_MALE);
```

## Validating the Birth Number

```php
use DevFusion\BirthNumber;

$bn = new BirthNumber(9707192649);
$bn->isValid(); // TRUE
```

## Getting Day, Month and Year of Birth from Birth Number

```php
use DevFusion\BirthNumber;

$bn = new BirthNumber(9707192649);
$bn->getDay(); // 19
$bn->getMonth(); // 7
$bn->getYear(); // 1997
```

## Getting Date Object

```php
use DevFusion\BirthNumber;

$bn = new BirthNumber(9707192649);
$bn->getBirthDate(); // DateTime object
```

## Getting Age

```php
use DevFusion\BirthNumber;

$bn = new BirthNumber(9707192649);
$bn->getAge(); // 24.016438356164
```

## Checking Adulthood

```php
use DevFusion\BirthNumber;

$bn = new BirthNumber(9707192649);
$bn->isAdult(); // TRUE
$bn->isAdolescent(); // FALSE
```

## Getting Gender

```php
use DevFusion\BirthNumber;

$bn = new BirthNumber(9707192649);
$bn->getGender(); // 0 (BirthNumber::GENDER_MALE)
```

## Checking Gender

```php
use DevFusion\BirthNumber;

$bn = new BirthNumber(9707192649);
$bn->isMale(); // TRUE
$bn->isFemale(); // FALSE
```
