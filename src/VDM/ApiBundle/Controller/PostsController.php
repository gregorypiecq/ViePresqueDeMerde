<?php

namespace VDM\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;

class PostsController extends FOSRestController
{
    public function getPostsAction(Request $request)
    {
        
        $repository = $this->getDoctrine()->getManager()->getRepository('VDMApiBundle:Article');
        
        $author = $request->get('author');
        $from = $request->get('from');
        $to = $request->get('to');
        
        if($author!="" && $from != "" && $to !=""){
            $articles = $repository->findByAuthorFromTo($author, $from, $to);
        } elseif($author!="" && $from != "" && $to ==""){
            $articles = $repository->findByAuthorForm($author, $from);
        } elseif($author!="" && $from == "" && $to =="") {
            $articles = $repository->findByAuthor($author);
        } elseif($author=="" && $from != "" && $to !="") {
            $articles = $repository->findByFromTo($from, $to);
        } elseif($author=="" && $from != "" && $to ==""){
            $articles = $repository->findByFrom($from);
        } elseif($author=="" && $from == "" && $to !=""){
            $articles = $repository->findByTo($to);
        } else {
            $articles = $this->getDoctrine()->getManager()->getRepository('VDMApiBundle:Article')->findAll();
        }
        
        
        if (!$articles) {
            $view = View::create()->setData(array('message' => 'Aucun post trouvé !', 'count'=>0));
            return $this->getViewHandler()->handle($view);
        }
        
        
        $view = View::create()->setData(array('posts' => $articles, 'count'=>count($articles)));
        return $this->getViewHandler()->handle($view);
    }
    
    public function getPostAction($id){
        $article = $this->getDoctrine()->getManager()->getRepository('VDMApiBundle:Article')->findById($id);
        if (!$article) {
            $view = View::create()->setData(array('message' => 'Aucun post trouvé !', 'count'=>0));
            return $this->getViewHandler()->handle($view);
        }
        $view = View::create()->setData(array('post' => $article));
        return $this->getViewHandler()->handle($view);
    }
}
