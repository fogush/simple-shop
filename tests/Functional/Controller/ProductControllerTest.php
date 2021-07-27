<?php

declare(strict_types=1);

namespace App\Tests\Functional\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Component\HttpFoundation\Response;

class ProductControllerTest extends WebTestCase
{
    public function testProductIsCreated(): void
    {
        $client = static::createClient();
        $client->request('POST', '/products', content: '{"name": "test", "title": "Test", "priceCents": 300}');

        $content = $client->getResponse()->getContent();

        self::assertResponseStatusCodeSame(Response::HTTP_CREATED);
        self::assertStringStartsWith('{"id":', $content);
    }

    /**
     * @dataProvider provideInvalidProducts
     */
    public function testInvalidProductIsNotCreated(string $body, string $expectedError): void
    {
        $client = static::createClient();
        $client->request('POST', '/products', content: $body);

        $content = $client->getResponse()->getContent();

        self::assertResponseStatusCodeSame(Response::HTTP_BAD_REQUEST);
        self::assertSame($expectedError, $content);
    }

    public function provideInvalidProducts(): array
    {
        echo 'asd' . 'asdsd';

        return [
            'invalid JSON' => [
                'something',
                '{"error":"Invalid JSON"}',
            ],
            'no name' => [
                '{"title": "Test", "priceCents": 300}',
                '{"error":"Provided product is not valid: ERROR: Name should not be blank\n"}',
            ],
            'no title' => [
                '{"name": "test", "priceCents": 300}',
                '{"error":"Provided product is not valid: ERROR: Title should not be blank\n"}',
            ],
            'no price' => [
                '{"name": "test", "title": "Test"}',
                '{"error":"Provided product is not valid: ERROR: Price should not be blank\n"}',
            ],
        ];
    }

    //TODO: write all the tests
}
