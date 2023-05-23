<?php

namespace Deviantintegral\JmsSerializerUriHandler\Tests\Unit;

use Deviantintegral\JmsSerializerUriHandler\UriHandler;
use GuzzleHttp\Psr7\Uri;
use JMS\Serializer\Annotation as Serializer;
use JMS\Serializer\Handler\HandlerRegistry;
use JMS\Serializer\SerializerBuilder;
use PHPUnit\Framework\TestCase;
use Psr\Http\Message\UriInterface;

/**
 * Tests serializing of Uri classes.
 */
class UriHandlerTest extends TestCase
{
    /**
     * @var \JMS\Serializer\SerializerInterface
     */
    private $serializer;

    protected function setUp(): void
    {
        $this->serializer = SerializerBuilder::create()
          ->configureHandlers(
              function (HandlerRegistry $registry) {
                  $registry->registerSubscribingHandler(new UriHandler());
              }
          )->build();
    }

    /**
     * @dataProvider typesToTest
     */
    public function testSerializeJson(string $class)
    {
        $uri = new Uri('http://www.example.com');
        $dummy = (new $class())->setUri($uri);
        $serialized = $this->serializer->serialize($dummy, 'json');
        $this->assertEquals(
            [
              'uri' => 'http://www.example.com',
            ],
            json_decode($serialized, true)
        );

        $deserialized = $this->serializer->deserialize(
            $serialized,
            $class,
            'json'
        );
        $this->assertEquals((string) $dummy->getUri(), (string) $deserialized->getUri());
    }

    /**
     * @dataProvider typesToTest
     */
    public function testSerializeXml(string $class)
    {
        $uri = new Uri('http://www.example.com');
        $dummy = (new $class())->setUri($uri);
        $serialized = $this->serializer->serialize($dummy, 'xml');
        $expected = <<<'XML'
<?xml version="1.0" encoding="UTF-8"?>
<result>
  <uri><![CDATA[http://www.example.com]]></uri>
</result>

XML;
        $this->assertEquals(
            $expected,
            $serialized
        );

        $deserialized = $this->serializer->deserialize(
            $serialized,
            $class,
            'xml'
        );
        $this->assertEquals((string) $dummy->getUri(), (string) $deserialized->getUri());
    }

    /**
     * Data provider with the classes to test.
     *
     * @return array
     */
    public function typesToTest()
    {
        return [
          [UriDummy::class],
          [UriInterfaceDummy::class],
        ];
    }
}

/**
 * Test class for serializing concrete URI objects.
 */
class UriDummy
{
    /**
     * @var Uri
     *
     * @Serializer\Type("GuzzleHttp\Psr7\Uri")
     */
    private $uri;

    public function getUri(): Uri
    {
        return $this->uri;
    }

    public function setUri(Uri $uri): self
    {
        $this->uri = $uri;

        return $this;
    }
}

/**
 * Test class for serializing only UriInterface objects.
 */
class UriInterfaceDummy
{
    /**
     * @var \Psr\Http\Message\UriInterface
     *
     * @Serializer\Type("Psr\Http\Message\UriInterface")
     */
    private $uri;

    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * @return \Deviantintegral\JmsSerializerUriHandler\Tests\Unit\UriInterfaceDummy
     */
    public function setUri(UriInterface $uri): self
    {
        $this->uri = $uri;

        return $this;
    }
}
