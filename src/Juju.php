<?php namespace JobBrander\Jobs\Client\Providers;

use JobBrander\Jobs\Client\Job;

class Juju extends AbstractProvider
{
    /**
     * Highlight
     *
     * @var string
     */
    protected $highlight;

    /**
     * Client IP Address
     *
     * @var string
     */
    protected $ipAddress;

    /**
     * Partner ID
     *
     * @var string
     */
    protected $partnerId;

    /**
     * User Agent
     *
     * @var string
     */
    protected $userAgent;

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
     * Get listings path
     *
     * @return  string
     */
    public function getListingsPath()
    {
        return 'channel.item';
    }

    /**
     * Get Highlight
     *
     * @return  string
     */
    public function getHighlight()
    {
        if (isset($this->highlight)) {
            return $this->highlight;
        } else {
            return '0';
        }
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
     * Get combined location
     *
     * @return string
     */
    public function getLocation()
    {
        $location = ($this->city ? $this->city.', ' : null).($this->state ?: null);

        if ($location) {
            return $location;
        }

        return null;
    }

    /**
     * Get parameters
     *
     * @return  array
     */
    public function getParameters()
    {
        return [];
    }

    /**
     * Get query string for client based on properties
     *
     * @return string
     */
    public function getQueryString()
    {
        $query_params = [
            'partnerid' => 'getPartnerId',
            'ipaddress' => 'getIpAddress',
            'useragent' => 'getUserAgent',
            'k' => 'getKeyword',
            'l' => 'getLocation',
            'jpp' => 'getCount',
            'page' => 'getPage',
            'highlight' => 'getHighlight',
        ];

        $query_string = [];

        array_walk($query_params, function ($value, $key) use (&$query_string) {
            $computed_value = $this->$value();
            if (!is_null($computed_value)) {
                $query_string[$key] = $computed_value;
            }
        });
        return '?'.http_build_query($query_string);
    }

    /**
     * Get url
     *
     * @return  string
     */
    public function getUrl()
    {
        $query_string = $this->getQueryString();

        return 'http://api.juju.com/jobs'.$query_string;
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
}
