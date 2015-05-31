<?php
namespace VDM\ApiBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use VDM\ApiBundle\Entity\Article;

class LoadPostsData extends AbstractFixture implements OrderedFixtureInterface {
    static public $posts = array();
    
    
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $post1 = new Article();
        $post1->setContent('Test content 1');
        $post1->setDate(new \DateTime('2015-05-30'));
        $post1->setAuthor('Groumpf');
        $post1->setIdVdm(1);
        
        $post2 = new Article();
        $post2->setContent('Test content 2');
        $post2->setDate(new \DateTime('2015-05-29'));
        $post2->setAuthor('Genius');
        $post2->setIdVdm(2);
       
        $manager->persist($post1);
        $manager->persist($post2);

        $manager->flush();

        $this->addReference('post-1', $post1);
        $this->addReference('post-2', $post2);

        self::$posts = array($post1, $post2); 
        
    }
    
    /**
     * {@inheritDoc}
     */
    public function getOrder()
    {
        return 1;
    }
}