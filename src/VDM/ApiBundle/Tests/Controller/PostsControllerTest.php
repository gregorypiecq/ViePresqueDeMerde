<?php
namespace VDM\ApiBundle\Tests\Controller;

use Liip\FunctionalTestBundle\Test\WebTestCase;
use Doctrine\ORM\Tools\SchemaTool;
class PostsControllerTest extends WebTestCase {
    
    static public $expectedPost = array(
        '{"post":[{"id":1,"content":"Test content 1","date":"2015-05-30T00:00:00+0200","author":"Groumpf"}]}',
        '{"post":[{"id":2,"content":"Test content 2","date":"2015-05-29T00:00:00+0200","author":"Genius"}]}',
    );
    
    static public $expectedPosts = '{"posts":[{"id":1,"content":"Test content 1","date":"2015-05-30T00:00:00+0200","author":"Groumpf"},{"id":2,"content":"Test content 2","date":"2015-05-29T00:00:00+0200","author":"Genius"}],"count":2}';
    
    public function setUp()
    {
        $em = $this->getContainer()->get('doctrine')->getManager();
        if (!isset($metadatas)) {
            $metadatas = $em->getMetadataFactory()->getAllMetadata();
        }
        $schemaTool = new SchemaTool($em);
        $schemaTool->dropDatabase();
        if (!empty($metadatas)) {
            $schemaTool->createSchema($metadatas);
        }
        $this->postFixtureSetup();

        $fixtures = array(
            'VDM\ApiBundle\DataFixtures\ORM\LoadPostsData',
        );
        $this->loadFixtures($fixtures);
    }
    /**
     * Methode pour tester la methode GetPost pour la route /api/posts/1
     * Recupere un post par son id
     */
    public function testPost(){
        
        $posts = \VDM\ApiBundle\DataFixtures\ORM\LoadPostsData::$posts;
        $limit = 2;

        for($i=0; $i<$limit; $i++) {
            $route =  $this->getUrl('get_post', array('id' => $posts[$i]->getId()));
            $client = $this->createClient();
            $client->request('GET', $route, array('ACCEPT' => 'application/json'));
            $response = $client->getResponse();
            $content = $response->getContent();
            
            $this->assertEquals(PostsControllerTest::$expectedPost[$i], $content);
        }
    }
    /**
     * Methode pour tester la récupération de tous les posts
     */
    public function testPosts(){
        $posts = \VDM\ApiBundle\DataFixtures\ORM\LoadPostsData::$posts;

            $route =  $this->getUrl('get_posts');
            $client = $this->createClient();
            $client->request('GET', $route, array('ACCEPT' => 'application/json'));
            $response = $client->getResponse();
            $content = $response->getContent();
            
            $this->assertEquals(PostsControllerTest::$expectedPosts, $content);
    }
}

