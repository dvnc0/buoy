# Buoy

A small PHP Feature Flag library.

`composer require dvnc0/buoy`

## Usage

```php
use Buoy;

Buoy::init();

Buoy::register('feature', function() {
	return true;
});

Buoy::can()->access('feature'); // true
```

This also includes a probability function:

```php
Buoy::lotto(50); // true 50% of the time
```

### Feature Validators
The register method can take a callable or a class that implements the `Buoy\Feature_Validator` interface.