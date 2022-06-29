# Vehicle fleet parking management
### [Instructions](https://github.com/fulll/developers/blob/master/Backend/ddd-and-cqs-level-1.md)
## Requirements

- Docker
- Docker compose
- Make

## Notes

Running the tests should build all necessary dependencies (Docker builds, vendors).

## Running tests

You can run behat tests with a make command
```shell
make behat
```

## Cloc : Line numbers

You can run cloc from the image with a make command
```shell
make cloc
```
### Output
```shell
docker run --rm -v /srv/www/ddd-cqs-tests/backend-level-1:/tmp aldanial/cloc --exclude-dir=vendor .
      27 text files.
      27 unique files.                              
       4 files ignored.

github.com/AlDanial/cloc v 1.89  T=0.02 s (1468.6 files/s, 49051.0 lines/s)
-------------------------------------------------------------------------------
Language                     files          blank        comment           code
-------------------------------------------------------------------------------
PHP                             17            120            116            429
Cucumber                         2              8              0             41
Markdown                         1              8              0             39
JSON                             1              0              0             33
YAML                             2              0              0             15
make                             1              1              0             15
Dockerfile                       1              3              0              7
-------------------------------------------------------------------------------
SUM:                            25            140            116            579
-------------------------------------------------------------------------------
```