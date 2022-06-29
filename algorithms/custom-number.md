# Increment a custom Number type
### [Instructions](https://github.com/fulll/developers/blob/master/Algo/custom-number-type-increment.md)
## Implementation
```php
<?php

declare(strict_types=1);

final class DigitArray implements Stringable
{
    /**
     * @var int[]
     */
    protected array $digits = [];

    public function __construct(int...$digit)
    {
        if (0 === count(func_get_args())) {
            throw new InvalidArgumentException('You must provide at least one unsigned digit');
        }

        foreach (func_get_args() as $arg) {
            if (!preg_match('/^\d$/', (string)$arg)) {
                throw new InvalidArgumentException(sprintf("Parameters must only be unsigned digits. \"%d\" provided", $arg));
            }
            $this->digits[] = $arg;
        }
    }

    public function increment(): self
    {
        //This operation is safe because of the constructor validations
        ++$this->digits[count($this->digits) - 1];

        for ($i = count($this->digits) - 1; $i + 1 > 0; --$i) {
            if ($this->digits[$i] === 10) {
                $this->digits[$i] = 0;
                isset($this->digits[$i - 1]) ? ++$this->digits[$i - 1] : array_unshift($this->digits, 1);
            }
        }

        return $this;
    }

    //For testing purposes
    public function __toString(): string
    {
        return implode('', $this->digits);
    }
}

```
## Testing
### Tests increments
```php
<?php

$digitArray = new DigitArray(8, 0);

for ($i = 0; $i <= 100; $i++) {
    echo $digitArray = $digitArray->increment();
    echo PHP_EOL;
}

```
#### Output
```shell
81
82
83
84
85
86
87
88
89
90
91
92
93
94
95
96
97
98
99
100
101
```


### Tests unsigned
```php
<?php

new DigitArray(8, -1);

```
#### Output
```shell
Fatal error: Uncaught InvalidArgumentException: Parameters must only be unsigned digits. "-1" provided in /var/www/DigitArray.php:19
Stack trace:
#0 /var/www/DigitArray.php(45): DigitArray->__construct(8, -1)
#1 {main}
  thrown in /var/www/DigitArray.php on line 19
```

### Tests non-digit number
```php
<?php

new DigitArray(8, 65);

```
#### Output
```shell
Fatal error: Uncaught InvalidArgumentException: Parameters must only be unsigned digits. "-1" provided in /var/www/DigitArray.php:19
Stack trace:
#0 /var/www/DigitArray.php(45): DigitArray->__construct(8, -1)
#1 {main}
  thrown in /var/www/DigitArray.php on line 19
```