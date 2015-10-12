<?php namespace JobBrander\Jobs\Client\Providers\Test;

use JobBrander\Jobs\Client\Providers\Juju;
use Mockery as m;

class JujuTest extends \PHPUnit_Framework_TestCase
{
    private $clientClass = 'JobBrander\Jobs\Client\Providers\AbstractProvider';
    private $collectionClass = 'JobBrander\Jobs\Client\Collection';
    private $jobClass = 'JobBrander\Jobs\Client\Job';

    public function setUp()
    {
        $this->params = [
            'partnerid' => 'XXXX'
        ];
        $this->client = new Juju($this->params);
    }

    public function testItWillUseXmlFormat()
    {
        $format = $this->client->getFormat();

        $this->assertEquals('xml', $format);
    }

    public function testItWillUseGetHttpVerb()
    {
        $verb = $this->client->getVerb();

        $this->assertEquals('GET', $verb);
    }

    public function testListingPath()
    {
        $path = $this->client->getListingsPath();

        $this->assertEquals('channel.item', $path);
    }

    public function testUrlIncludesKeywordWhenProvided()
    {
        $keyword = uniqid().' '.uniqid();
        $param = 'k='.urlencode($keyword);

        $url = $this->client->setKeyword($keyword)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlNotIncludesKeywordWhenNotProvided()
    {
        $param = 'k=';

        $url = $this->client->getUrl();

        $this->assertNotContains($param, $url);
    }

    public function testUrlIncludesLocationWhenCityStateProvided()
    {
        $city = uniqid();
        $state = uniqid();
        $param = 'l='.urlencode($city.', '.$state);

        $url = $this->client->setLocation($city.', '.$state)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlIncludesLocationWhenCityProvided()
    {
        $city = uniqid();
        $param = 'l='.urlencode($city);

        $url = $this->client->setLocation(urlencode($city))->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlIncludesLocationWhenStateProvided()
    {
        $state = uniqid();
        $param = 'l='.urlencode($state);

        $url = $this->client->setLocation(urlencode($state))->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlNotIncludesLocationWhenNotProvided()
    {
        $param = 'l=';

        $url = $this->client->getUrl();

        $this->assertNotContains($param, $url);
    }

    public function testUrlIncludesCountWhenProvided()
    {
        $count = uniqid();
        $param = 'jpp='.$count;

        $url = $this->client->setCount($count)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlNotIncludesCountWhenNotProvided()
    {
        $param = 'jpp=';

        $url = $this->client->setCount(null)->getUrl();

        $this->assertNotContains($param, $url);
    }

    public function testUrlIncludesDeveloperKeyWhenProvided()
    {
        $param = 'partnerid='.$this->params['partnerid'];

        $url = $this->client->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlIncludesPageWhenProvided()
    {
        $page = uniqid();
        $param = 'page='.$page;

        $url = $this->client->setPage($page)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlNotIncludesPageWhenNotProvided()
    {
        $param = 'page=';

        $url = $this->client->setPage(null)->getUrl();

        $this->assertNotContains($param, $url);
    }

    public function testUrlIncludesIpWhenProvided()
    {
        $ip = uniqid();
        $param = 'ipaddress='.$ip;

        $url = $this->client->setIpaddress($ip)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlIncludesIpWhenNotProvided()
    {
        $param = 'ipaddress=';

        $url = $this->client->setIpaddress(null)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testUrlIncludesHighlightWhenProvided()
    {
        $highlight = uniqid();
        $param = 'highlight='.$highlight;

        $url = $this->client->setHighlight($highlight)->getUrl();

        $this->assertContains($param, $url);
    }

    public function testItWillProvideEmptyParameters()
    {
        $parameters = $this->client->getParameters();

        $this->assertEmpty($parameters);
        $this->assertTrue(is_array($parameters));
    }

    public function testItCanCreateJobFromPayload()
    {
        $payload = $this->createJobArray();
        $results = $this->client->createJobObject($payload);

        $this->assertEquals($payload['title'], $results->title);
        $this->assertEquals($payload['company'], $results->company);
        $this->assertEquals($payload['description'], $results->description);
        $this->assertEquals($payload['link'], $results->url);
    }

    public function testItCanConnect()
    {
        $keyword = uniqid();
        $responseBody = $this->createXmlResponse();

        $job = m::mock($this->jobClass);
        $job->shouldReceive('setQuery')
            ->with($keyword)
            ->once()
            ->andReturnSelf();
        $job->shouldReceive('setSource')
            ->with('Juju')
            ->once()
            ->andReturnSelf();

        $response = m::mock('GuzzleHttp\Message\Response');
        $response->shouldReceive('getBody')->once()->andReturn($responseBody);

        $http = m::mock('GuzzleHttp\Client');

        $this->client->setKeyword($keyword);

        $http->shouldReceive(strtolower($this->client->getVerb()))
            ->with($this->client->getUrl(), $this->client->getHttpClientOptions())
            ->once()
            ->andReturn($response);
        $this->client->setClient($http);

        $results = $this->client->getJobs();

        $this->assertInstanceOf($this->collectionClass, $results);
    }

    private function createJobArray()
    {
        return [
            'title' => uniqid(),
            'company' => uniqid(),
            'description' => uniqid(),
            'link' => uniqid(),
            'postdate' => '2015-07-'.rand(1,31),
        ];
    }

    private function createXmlResponse()
    {
        $array = [
            'channel' => [
                'item' => [
                    0 => $this->createJobArray(),
                    1 => $this->createJobArray()
                ]
            ]
        ];
        $xml = '<?xml version="1.0" encoding="UTF-8"?>
            <rss version="2.0">
                <channel>
                    <title>Juju </title>
                    <link>
                    http://api.juju.com/jobs?partnerid=3f6eacc5a03e03b4287c1a0b43ece6bb&amp;ipaddress=10.1.30.15&amp;useragent=Mozilla%2F5.0+%28Windows%3B+U%3B+Windows+NT+5.1%3B+en-US%3B+rv%3A1.7%29+Gecko%2F20040803+Firefox%2F0.9.3&amp;k=engineering&amp;jpp=10&amp;page=1&amp;highlight=0
                </link>
                    <description>
                    Juju - Search thousands of job sites at once for local jobs in your field.
                </description>
                    <language>en-us</language>
                    <totalresults>437219</totalresults>
                    <startindex>0</startindex>
                    <itemsperpage>10</itemsperpage>
                    <item>
                        <title>Director of Quality Assurance</title>
                        <zip></zip>
                        <city>Birmingham</city>
                        <county>Jefferson</county>
                        <state>AL</state>
                        <country>US</country>
                        <source>GlidePath.com</source>
                        <company>Milo&#39;s Tea Company</company>
                        <link>http://www.juju.com/jad/00000000lss51f?impression_id=AESwhPSWQKWXJ90_tqmllg&amp;partnerid=3f6eacc5a03e03b4287c1a0b43ece6bb&amp;channel=</link>
                        <onclick>juju_partner(this, \'235\');</onclick>
                        <guid isPermaLink="false">00000000lss51f</guid>
                        <postdate>07/29/15</postdate>
                        <description>…quality control personnel on a day-to-day basis. | Supports concurrent  engineering  efforts by participating in design development projects representing quality assurance</description>
                    </item>
                    <item>
                        <title>Director of Quality Assurance</title>
                        <zip></zip>
                        <city>Birmingham</city>
                        <county>Jefferson</county>
                        <state>AL</state>
                        <country>US</country>
                        <source>GlidePath.com</source>
                        <company>Milo&#39;s Tea Company</company>
                        <link>http://www.juju.com/jad/00000000lss51f?impression_id=AESwhPSWQKWXJ90_tqmllg&amp;partnerid=3f6eacc5a03e03b4287c1a0b43ece6bb&amp;channel=</link>
                        <onclick>juju_partner(this, \'235\');</onclick>
                        <guid isPermaLink="false">00000000lss51f</guid>
                        <postdate>07/29/15</postdate>
                        <description>…quality control personnel on a day-to-day basis. | Supports concurrent  engineering  efforts by participating in design development projects representing quality assurance</description>
                    </item>
                </channel>
            </rss>';
        return $xml;
    }
}
