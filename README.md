# URI Handler for JMS Serializer

[![CircleCI](https://circleci.com/gh/deviantintegral/jms-serializer-uri-handler.svg?style=svg)](https://circleci.com/gh/deviantintegral/jms-serializer-uri-handler)

This library supports serializing and deserializing URI instances, as defined
by [PSR-7](https://www.php-fig.org/psr/psr-7/#35-psrhttpmessageuriinterface).

Add this handler to your serializer with the following:

```php
AnnotationRegistry::registerLoader('class_exists');
$this->serializer = SerializerBuilder::create()
  ->configureHandlers(
    function (HandlerRegistry $registry) {
        $registry->registerSubscribingHandler(new UriHandler());
    }
  )->build();
```
