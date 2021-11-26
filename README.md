# Otrium Reporting Tool

## Install

Clone the repo:

```bash
git clone https://github.com/eduardbudacu/reporting-tool
```

Install composer dependencies:

```bash
composer install
```

Copy credential json files into the ```credentials``` directory. Credentials were provided by email.

## Usage


```
php console.php generate <report> [options]
```

```
Arguments:
  report                       The name of the report.

Options:
  -d, --datasource=DATASOURCE  Data source for the report [default: "local"]
  -p, --publish=PUBLISH        Destination for the report [default: "local"]
  -s, --startdate=STARTDATE    Start date
  -e, --enddate=ENDDATE        End date
```

Valid report arguments: turnover-per-brand|turnover-per-day

Valid DATASOURCE: local|s3|api

Valid PUBLISH: local|datastudio

Date format: dd-mm-YYYY (eg. 01-05-2018)


### Examples


Generate brands report:

```
php console.php generate turnover-per-brand
```

Generate daily report:
```
php console.php generate turnover-per-day
```

Filtering:

```
php console.php generate turnover-per-day --startdate=01-05-2018 --enddate=07-05-2018
```


## Technical specs

### Architecture and design decisions

Demo and design described in this short video: https://www.youtube.com/watch?v=vsFq6j68u7E

![Architecture overview](./docs/architecture.jpg)

1. Data sources

- local data source
- REST API
- Amazon S3

2. Publishing

- local csv file
- DataStudio

### Frameworks and tools

- symfony/console
- aws/aws-sdk-php
- guzzlehttp/guzzle
- google/cloud-storage