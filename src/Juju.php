<?php namespace JobBrander\Jobs\Client\Providers;

use JobBrander\Jobs\Client\Job;

class Juju extends AbstractProvider
{
    /**
     * Map of setter methods to query parameters
     *
     * @var array
     */
    protected $queryMap = [
        'setPartnerid' => 'partnerid',
        'setIpaddress' => 'ipaddress',
        'setUseragent' => 'useragent',
        'setK' => 'k',
        'setL' => 'l',
        'setC' => 'c',
        'setR' => 'r',
        'setOrder' => 'order',
        'setDays' => 'days',
        'setJpp' => 'jpp',
        'setPage' => 'page',
        'setChannel' => 'channel',
        'setHighlight' => 'highlight',
        'setStartindex' => 'startindex',
        'setSession' => 'session',
        'setKeyword' => 'k',
        'setLocation' => 'l',
        'setCount' => 'jpp',
    ];

    /**
     * Current api query parameters
     *
     * @var array
     */
    protected $queryParams = [
        'partnerid' => null,
        'ipaddress' => null,
        'useragent' => null,
        'k' => null,
        'l' => null,
        'c' => null,
        'r' => null,
        'order' => null,
        'days' => null,
        'jpp' => null,
        'page' => null,
        'channel' => null,
        'highlight' => null,
        'startindex' => null,
        'session' => null,
    ];

    /**
     * Create new J2c jobs client.
     *
     * @param array $parameters
     */
    public function __construct($parameters = [])
    {
        parent::__construct($parameters);
        array_walk($parameters, [$this, 'updateQuery']);
        // Set default parameters
        if (!isset($this->ipaddress)) {
            $this->updateQuery($this->getIpAddress(), 'ipaddress');
        }
        if (!isset($this->useragent)) {
            $this->updateQuery($this->getUserAgent(), 'useragent');
        }
    }

    /**
     * Magic method to handle get and set methods for properties
     *
     * @param  string $method
     * @param  array  $parameters
     *
     * @return mixed
     */
    public function __call($method, $parameters)
    {
        if (isset($this->queryMap[$method], $parameters[0])) {
            $this->updateQuery($parameters[0], $this->queryMap[$method]);
        }
        return parent::__call($method, $parameters);
    }

    /**
     * Returns the standardized job object
     *
     * @param array $payload
     *
     * @return \JobBrander\Jobs\Client\Job
     */
    public function createJobObject($payload)
    {
        $defaults = [
            'title',
            'company',
            'city',
            'state',
            'country',
            'source',
            'link',
            'onclick',
            'guid',
            'postdate',
            'description',
        ];

        $payload = static::parseAttributeDefaults($payload, $defaults);

        $job = new Job([
            'title' => $payload['title'],
            'name' => $payload['title'],
            'description' => $payload['description'],
            'url' => $payload['link'],
            'sourceId' => $payload['guid'],
            'javascriptAction' => 'onclick',
            'javascriptFunction' => $payload['onclick'],
            'location' => $payload['city'].', '.$payload['state'],
        ]);

        $job->setCompany($payload['company'])
            ->setState($payload['state'])
            ->setCity($payload['city'])
            ->setDatePostedAsString($payload['postdate']);

        return $job;
    }

    /**
     * Get data format
     *
     * @return string
     */
    public function getFormat()
    {
        return 'xml';
    }

    /**
     * Get IP Address
     *
     * @return  string
     */
    public function getIpAddress()
    {
        if (isset($this->ipAddress)) {
            return $this->ipAddress;
        } else {
            return getHostByName(getHostName());
        }
    }

    /**
     * Get listings path
     *
     * @return  string
     */
    public function getListingsPath()
    {
        return 'channel.item';
    }

    /**
     * Get query string for client based on properties
     *
     * @return string
     */
    public function getQueryString()
    {
        return http_build_query($this->queryParams);
    }

    /**
     * Get url
     *
     * @return  string
     */
    public function getUrl()
    {
        $query_string = $this->getQueryString();

        return 'http://api.juju.com/jobs?'.$query_string;
    }

    /**
     * Get user agent (currently defaults to mozilla/windows)
     *
     * @return  string
     */
    public function getUserAgent()
    {
        return 'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.7) Gecko/20040803 Firefox/0.9.3';
    }

    /**
     * Get http verb
     *
     * @return  string
     */
    public function getVerb()
    {
        return 'GET';
    }

    /**
     * Attempts to update current query parameters.
     *
     * @param  string  $value
     * @param  string  $key
     *
     * @return Juju
     */
    protected function updateQuery($value, $key)
    {
        if (array_key_exists($key, $this->queryParams)) {
            $this->queryParams[$key] = $value;
        }
        return $this;
    }
}
