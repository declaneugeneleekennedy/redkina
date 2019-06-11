# Redkina

[![CircleCI](https://circleci.com/gh/declaneugeneleekennedy/redkina.svg?style=svg)](https://circleci.com/gh/declaneugeneleekennedy/redkina) [![codecov](https://codecov.io/gh/declaneugeneleekennedy/redkina/branch/master/graph/badge.svg)](https://codecov.io/gh/declaneugeneleekennedy/redkina)

A graph indexing system written in PHP, backed by Redis.

## Background

Read about hexastores here: https://redis.io/topics/indexes#representing-and-querying-graphs-using-an-hexastore

## Installation

Install using composer:

```bash
composer require devdeclan/redkina
```

## Planned Roadmap

* v0.1: Initial release with basic API
* v0.2: Implement validation rules using metadata and [Respect](https://respect-validation.readthedocs.io/en/1.1/)
* v0.3: Lazy-loading of related entities
