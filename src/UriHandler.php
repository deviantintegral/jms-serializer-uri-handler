<?php

declare(strict_types=1);

namespace Deviantintegral\JmsSerializerUriHandler;

use GuzzleHttp\Psr7\Uri;
use JMS\Serializer\DeserializationContext;
use JMS\Serializer\GraphNavigatorInterface;
use JMS\Serializer\Handler\SubscribingHandlerInterface;
use JMS\Serializer\Visitor\DeserializationVisitorInterface;
use JMS\Serializer\Visitor\SerializationVisitorInterface;
use Psr\Http\Message\UriInterface;

class UriHandler implements SubscribingHandlerInterface
{
    /**
     * {@inheritdoc}
     */
    public static function getSubscribingMethods()
    {
        return [
          [
            'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
            'format' => 'json',
            'type' => 'GuzzleHttp\Psr7\Uri',
            'method' => 'serializeUriToString',
          ],
          [
            'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
            'format' => 'json',
            'type' => 'Psr\Http\Message\UriInterface',
            'method' => 'serializeUriToString',
          ],
          [
            'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
            'format' => 'json',
            'type' => 'GuzzleHttp\Psr7\Uri',
            'method' => 'deserializeStringToUri',
          ],
          [
            'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
            'format' => 'json',
            'type' => 'Psr\Http\Message\UriInterface',
            'method' => 'deserializeStringToUri',
          ],
          [
            'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
            'format' => 'xml',
            'type' => 'GuzzleHttp\Psr7\Uri',
            'method' => 'serializeUriToString',
          ],
          [
            'direction' => GraphNavigatorInterface::DIRECTION_SERIALIZATION,
            'format' => 'xml',
            'type' => 'Psr\Http\Message\UriInterface',
            'method' => 'serializeUriToString',
          ],
          [
            'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
            'format' => 'xml',
            'type' => 'GuzzleHttp\Psr7\Uri',
            'method' => 'deserializeStringToUri',
          ],
          [
            'direction' => GraphNavigatorInterface::DIRECTION_DESERIALIZATION,
            'format' => 'xml',
            'type' => 'Psr\Http\Message\UriInterface',
            'method' => 'deserializeStringToUri',
          ],
        ];
    }

    public function serializeUriToString(
        SerializationVisitorInterface $visitor,
        UriInterface $data,
        array $type
    ) {
        return $visitor->visitString((string) $data, $type);
    }

    public function deserializeStringToUri(
        DeserializationVisitorInterface $visitor,
        $data,
        array $type,
        DeserializationContext $context
    ): ?Uri {
        return new Uri((string) $data);
    }
}
