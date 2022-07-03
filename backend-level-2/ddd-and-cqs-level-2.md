# Vehicle fleet parking management
### [Instructions](https://github.com/fulll/developers/blob/master/Backend/ddd-and-cqs-level-2.md)
## Requirements

- Docker
- Docker compose
- Make

## Notes

- The container doesn't need to be running for the application or testing

## Usage
As per the instructions, all commands are run from the "fleet" executable at the root of this directory

### Create a fleet
```shell
./fleet create <userId> # returns fleetId on the standard output
```
- userId : Uuid V4 valid string
    * Some examples
    * 712b2ccf-c0e4-4c5f-9c77-2e2942215ad0
    * 0d36f42b-d5ac-45e8-ac30-fcf920680720

### Register a vehicle by plate number into an existing fleet
```shell
register-vehicle <fleetId> <vehiclePlateNumber>
```
- fleetId : Uuid V4 valid string of an existing fleet (Presumably obtain from previous command)
- vehiclePlateNumber : Any string with no whitespace

### Register the location of a vehicle from a given fleet
```shell
./fleet localize-vehicle <fleetId> <vehiclePlateNumber> lat lng [alt]
```
- fleetId : Uuid V4 valid string of an existing fleet (Presumably obtain from previous command)
- vehiclePlateNumber : Any string with no whitespace
- lat : Latitude as float
- lng : Longitude as float
- alt : (Optional) Altitude as float

## Running tests
You can run tests tools with a single make command
```shell
make tests
```
### PHP Coding Standards Fixer
To standardize code syntax and styling

To run a diff
```shell
make php-cs-fixer-dry-run
```
### PHP Static Analysis Tool
Static code analysis tracking

To run
```shell
make phpstan
```
### Symfony linting
Framework analysis of dependency injection and configurations files syntax
To run
```shell
make symfony-lint
```
### Behat
Behaviour driven testing

To run all suits
```shell
make behat
```


