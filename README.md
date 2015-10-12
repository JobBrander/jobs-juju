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
    'partnerid' => 'XXXXX',
]);

// Search for 20 job listings for 'project manager' in Chicago, IL
$jobs = $client
    // Setter methods supported by JuJu API
    ->setPartnerid('')  // (Required) Your assigned Publisher ID. This is given to you when signing up.
    ->setIpaddress('')  // (Required) The IP Address of the end-user
    ->setUseragent('')  // (Required) The User-Agent of the end-user
    ->setK('')          // (K, L, or C Required)
    ->setL('')          // (K, L, or C Required)
    ->setC('')          // (K, L, or C Required)
    ->setR('')          // The radius, in miles, around the search location. The default is 20 and the maximum is 100.
    ->setOrder('')      // The order in which to return results. Choices are: relevance, date, distance. The default is relevance.
    ->setDays('')       // The number of days back to search. Default is 90.
    ->setJpp('')        // The number of jobs per page to return with each request. The maximum is 20, which is also the default.
    ->setPage('')       // The page of results to return. Page numbers start at 1, the default.
    ->setChannel('')    // The channel name used to track performance for multiple sites. See the section on channels.
    ->setHighlight('')  // By default, results will be highlighted with HTML bolding. Set this flag to 0 to turn highlighting off.
    ->setStartindex('') // If you are using API results as backfill on one page of results, use this flag to 'skip' jobs from the top of further API results, because you've already shown them in backfill. The minimum (and default) is 1, which indicates that results should start on the first job. Simple paging should be implemented with the page and jpp parameters. If you are unsure, you probably want to use page and jpp.
    ->setSession('')    // This parameter should be uniquely associated with a particular user. It can be an anonymized persistent or session cookie for web requests, or an anonymized contact id for email. Juju currently uses this internally for testing new algorithms. If you cannot or do not wish to provide this parameter, it's fine to omit it.
    // Additional setter methods
    ->setKeyword('project manager') // The query. This is in the same format as a basic search. Try their search or advanced search for possible formats.
    ->setLocation('Chicago, IL')
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
