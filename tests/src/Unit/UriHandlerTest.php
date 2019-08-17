<?php

namespace Deviantintegral\JmsSerializerUriHandler\Tests\Unit;

use Deviantintegral\JmsSerializerUriHandler\UriHandler;
use Doctrine\Common\Annotations\AnnotationRegistry;
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

    /**
     * {@inheritdoc}
     */
    protected function setUp(): void
    {
        AnnotationRegistry::registerLoader('class_exists');

        $this->serializer = SerializerBuilder::create()
          ->configureHandlers(
            function (HandlerRegistry $registry) {
                $registry->registerSubscribingHandler(new UriHandler());
            }
          )->build();
    }

    /**
     * @dataProvider typesToTest
     *
     * @param string $class
     */
    public function testSerializeJson(string $class)
    {
        $uri = new Uri("http://www.example.com");
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
        $this->assertEquals($dummy, $deserialized);
    }

    /**
     * @dataProvider typesToTest
     *
     * @param string $class
     */
    public function testSerializeXml(string $class)
    {
        $uri = new Uri("http://www.example.com");
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
        $this->assertEquals($dummy, $deserialized);
    }

    /**
     * Data provider with the classes to test.
     *
     * @return array
     */
    public function typesToTest() {
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
     * @Serializer\Type("GuzzleHttp\Psr7\Uri")
     */
    private $uri;

    /**
     * @return \GuzzleHttp\Psr7\Uri
     */
    public function getUri(): \GuzzleHttp\Psr7\Uri
    {
        return $this->uri;
    }

    /**
     * @param \GuzzleHttp\Psr7\Uri $uri
     *
     * @return UriDummy
     */
    public function setUri(\GuzzleHttp\Psr7\Uri $uri): self
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
     * @Serializer\Type("Psr\Http\Message\UriInterface")
     */
    private $uri;

    /**
     * @return \Psr\Http\Message\UriInterface
     */
    public function getUri(): UriInterface
    {
        return $this->uri;
    }

    /**
     * @param \Psr\Http\Message\UriInterface $uri
     *
     * @return \Deviantintegral\JmsSerializerUriHandler\Tests\Unit\UriInterfaceDummy
     */
    public function setUri(UriInterface $uri): self
    {
        $this->uri = $uri;

        return $this;
    }
}
