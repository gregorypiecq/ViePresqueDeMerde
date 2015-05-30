<?php

namespace VDM\UpdatePostBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    public function indexAction($name)
    {
        return $this->render('VDMUpdatePostBundle:Default:index.html.twig', array('name' => $name));
    }
}
