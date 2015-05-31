<?php

namespace VDM\ApiBundle\Controller;

use FOS\RestBundle\Controller\FOSRestController;
use FOS\RestBundle\View\View;
use Symfony\Component\HttpFoundation\Request;

class PostsController extends FOSRestController
{
    /**
     * Methode pour recuperer les posts
     * 
     * En fonction des parametre de requete, recherche en fonction de l'auteur, 
     * a partir d'une date, avant une date, entre deux dates et tous les parametre en meme temps
     * 
     * A partir de l'url /api/posts(?author=&form=&to=)
     * 
     * @param Request $request
     * @return type
     */
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
            return $this->handleView($view);
        }
        
        
        $view = View::create()->setData(array('posts' => $articles, 'count'=>count($articles)));
        return $this->handleView($view);
    }
    /**
     * Methode pour récuperer un post par son id
     * 
     * Url /api/posts/id
     * 
     * @param type $id
     * @return type
     */
    public function getPostAction($id){
        $article = $this->getDoctrine()->getManager()->getRepository('VDMApiBundle:Article')->findById($id);
        if (!$article) {
            $view = View::create()->setData(array('message' => 'Aucun post trouvé !', 'count'=>0));
            return $this->handleView($view);
        }
        $view = View::create()->setData(array('post' => $article));
        return $this->handleView($view);
    }
}
