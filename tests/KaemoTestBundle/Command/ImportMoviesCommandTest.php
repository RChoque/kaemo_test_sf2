<?php

namespace KaemoTestBundle\Tests\Command;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;

class ImportMoviesCommandTest extends WebTestCase
{
    public function setUp()
    {
        $this->client = static::createClient(array());
        $this->verbosityLevel = 'verbose';
        $this->decorated = false;
        $em = $this->getContainer()->get('doctrine')->getManager();
        if (!isset($metadatas)) {
            $metadatas = $em->getMetadataFactory()->getAllMetadata();
        }
        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();
        if (!empty($metadatas)) {
            $schemaTool->createSchema($metadatas);
        }
    }

    public function testImportMovies()
    {
        $route =  $this->getUrl('kaemo_test_movies_list', array('_format' => 'json'));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['count']));
        $this->assertEquals(0, $decoded['count']);

        $this->runCommand('kaemo:movie:import');

        $route =  $this->getUrl('kaemo_test_movies_list', array('_format' => 'json'));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['count']));
        $this->assertEquals(10, $decoded['count']);
    }

    protected function assertJsonResponse(
        $response, 
        $statusCode = 200, 
        $checkValidJson =  true, 
        $contentType = 'application/json'
    )
    {
        $this->assertEquals(
            $statusCode, $response->getStatusCode(),
            $response->getContent()
        );
        $this->assertTrue(
            $response->headers->contains('Content-Type', $contentType),
            $response->headers
        );
        if ($checkValidJson) {
            $decode = json_decode($response->getContent());
            $this->assertTrue(($decode != null && $decode != false),
                'is response valid json: [' . $response->getContent() . ']'
            );
        }
    }
}
