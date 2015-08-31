# Juju Jobs Client

[![Latest Version](https://img.shields.io/github/release/JobBrander/jobs-juju.svg?style=flat-square)](https://github.com/JobBrander/jobs-juju/releases)
[![Software License](https://img.shields.io/badge/license-APACHE%202.0-brightgreen.svg?style=flat-square)](LICENSE.md)
[![Build Status](https://img.shields.io/travis/JobBrander/jobs-juju/master.svg?style=flat-square&1)](https://travis-ci.org/JobBrander/jobs-juju)
[![Coverage Status](https://img.shields.io/scrutinizer/coverage/g/JobBrander/jobs-juju.svg?style=flat-square)](https://scrutinizer-ci.com/g/JobBrander/jobs-juju/code-structure)
[![Quality Score](https://img.shields.io/scrutinizer/g/JobBrander/jobs-juju.svg?style=flat-square)](https://scrutinizer-ci.com/g/JobBrander/jobs-juju)
[![Total Downloads](https://img.shields.io/packagist/dt/jobbrander/jobs-juju.svg?style=flat-square)](https://packagist.org/packages/jobbrander/jobs-juju)

This package provides [Juju Jobs API](http://www.juju.com/publisher/spec/)
support for the JobBrander's [Jobs Client](https://github.com/JobBrander/jobs-common).

## Installation

To install, use composer:

```
composer require jobbrander/jobs-juju
```

## Usage

Usage is the same as Job Branders's Jobs Client, using `\JobBrander\Jobs\Client\Provider\Juju` as the provider.

```php
$client = new JobBrander\Jobs\Client\Provider\Juju([
    'partnerId' => 'XXXXX',
]);

// Search for 200 job listings for 'project manager' in Chicago, IL
$jobs = $client->setKeyword('project manager') // The query. This is in the same format as a basic search. Try their search or advanced search for possible formats.
    ->setCity('Chicago')    // Combined with state to form location. The location can be a state, county, city, or zip code. Using multiple locations in one query is not supported.
    ->setState('IL')
    ->setCount(20)          // The number of jobs per page to return with each request. The maximum is 20, which is also the default.
    ->getJobs();
```

The `getJobs` method will return a [Collection](https://github.com/JobBrander/jobs-common/blob/master/src/Collection.php) of [Job](https://github.com/JobBrander/jobs-common/blob/master/src/Job.php) objects.

## Testing

``` bash
$ ./vendor/bin/phpunit
```

## Contributing

Please see [CONTRIBUTING](https://github.com/jobbrander/jobs-juju/blob/master/CONTRIBUTING.md) for details.

## Credits

- [Karl Hughes](https://github.com/karllhughes)
- [Steven Maguire](https://github.com/stevenmaguire)
- [All Contributors](https://github.com/jobbrander/jobs-juju/contributors)

## License

The Apache 2.0. Please see [License File](https://github.com/jobbrander/jobs-juju/blob/master/LICENSE) for more information.
