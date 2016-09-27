<?php

namespace KaemoTestBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase as WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;

class MoviesControllerTest extends WebTestCase
{
    public function setUp()
    {
        $this->client = static::createClient(array());
        $this->verbosityLevel = 'verbose';
        $this->decorated = false;
        //réinitialise la base
        $em = $this->getContainer()->get('doctrine')->getManager();
        if (!isset($metadatas)) {
            $metadatas = $em->getMetadataFactory()->getAllMetadata();
        }
        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();
        if (!empty($metadatas)) {
            $schemaTool->createSchema($metadatas);
        }
        $this->runCommand('kaemo:movie:import');
    }

    public function testListMovies()
    {
        $route =  $this->getUrl('kaemo_test_movies_list', array('_format' => 'json'));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['count']));
        $this->assertTrue(isset($decoded['videos']));
        foreach ($decoded['videos'] as $video) {
            $this->assertTrue(isset($video['title']));
            $this->assertTrue(isset($video['date']));
            $this->assertTrue(isset($video['realisator']));
        }
        $this->assertEquals(count($decoded['videos']), $decoded['count']);
        $this->assertEquals(10, $decoded['count']);
    }

    public function testSearchMovies()
    {
        $route =  $this->getUrl('kaemo_test_movies_list', array('_format' => 'json', 'realisator'=>"Nolan", "from"=>"20100101", "to"=>"20150101"));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['count']));
        $this->assertTrue(isset($decoded['videos']));
        $this->assertEquals(count($decoded['videos']), $decoded['count']);
        $this->assertEquals(2, $decoded['count']);

        $route =  $this->getUrl('kaemo_test_movies_list', array('_format' => 'json', "from"=>"20100101", "to"=>"20150101"));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['count']));
        $this->assertTrue(isset($decoded['videos']));
        $this->assertEquals(count($decoded['videos']), $decoded['count']);
        $this->assertEquals(3, $decoded['count']);

        $route =  $this->getUrl('kaemo_test_movies_list', array('_format' => 'json', "from"=>"20000101"));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['count']));
        $this->assertTrue(isset($decoded['videos']));
        $this->assertEquals(count($decoded['videos']), $decoded['count']);
        $this->assertEquals(6, $decoded['count']);
    }

    public function testMovie()
    {
        $route =  $this->getUrl('kaemo_test_movie', array('id' => 1, '_format' => 'json'));
        $this->client->request('GET', $route);
        $response = $this->client->getResponse();
        $this->assertJsonResponse($response, 200);
        $content = $response->getContent();

        $decoded = json_decode($content, true);
        $this->assertTrue(isset($decoded['video']));
        $this->assertTrue(isset($decoded['video']['title']));
        $this->assertTrue(isset($decoded['video']['date']));
        $this->assertTrue(isset($decoded['video']['realisator']));
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
