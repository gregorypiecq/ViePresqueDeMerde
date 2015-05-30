<?php
namespace VDM\UpdatePostBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Question\Question;
use Symfony\Component\Console\Output\OutputInterface;
use VDM\ApiBundle\Entity\Article;

class UpdatePostCommand extends ContainerAwareCommand
{
    protected $_nbPost;
    protected $_cptPost = 0;
    protected $_postsAdd = 0;
    protected $_postsUpdate = 0;

    /**
     * Méthode de configuration de la commande
     * 
     * Cette méthode permet de configurer le nom et la description de la commande
     */
    protected function configure()
    {
        $this
            ->setName('vdm:update')
            ->setDescription('Récupérer les posts Vie de Merde');
    }
    /**
     * Méthode d'execution de la commande
     * 
     * Cette méthode va permettre l'execution de la commande vdm:update
     * Poser la question sur le nombre de posts que l'utilisateur souhaite récuperer
     * Traiter les erreurs de saisie
     * Executer la récupération des posts
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $helper = $this->getHelper('question');
        
        $question = new Question('Combien de posts souhaitez-vous récupérer ?','200');
        
        $question->setValidator(function ($answer) {
            if (!is_numeric($answer)) {
                throw new \RuntimeException(
                    'Vous devez saisir un nombre !'
                );
            } elseif(0 == $answer){
                throw new \RuntimeException(
                    'Vous devez saisir un nombre > 0 !'
                );
            }
            return $answer;
        });
        $question->setMaxAttempts(4);
        
        $this->_nbPost = $helper->ask($input, $output, $question);
        
        $now = new \DateTime();
        $output->writeln('<comment>Début de l\'execution : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
        
        $this->getPosts($input, $output);
        
        $output->writeln('<comment>Nombre d\'ajout : ' . $this->_postsAdd . ' ---</comment>');
        $output->writeln('<comment>Nombre de modifications : ' . $this->_postsUpdate . ' ---</comment>');
        
        $now = new \DateTime();
        $output->writeln('<comment>Fin de l\'executions : ' . $now->format('d-m-Y G:i:s') . ' ---</comment>');
    }
    /**
     * Méthode de récupération des posts
     * 
     * Cette méthode permet de recupérer et persister les posts
     * 
     * @param InputInterface $input
     * @param OutputInterface $output
     * @param int $nbPost
     */
    protected function getPosts(InputInterface $input, OutputInterface $output){
        
        // les pages du site de vie de merde sont composées de 13 posts par page il faut definir le nombre de pages à consulter
        $nbPage = ceil(($this->_nbPost/13));
        
        for($page=1;$page<=$nbPage;$page++){
            $this->getPostsByPage($page, $output);
        }
        
    }
    
    protected function getPostsByPage($pageNumber, OutputInterface $output) {
        
        if($pageNumber ==1){
            // lien feed VDM
            $link = "http://www.viedemerde.fr";
        } else {
            // lien feed VDM
            $link = "http://www.viedemerde.fr/?page=".$pageNumber; 
        }
        
        
        // initilisation de la librairie curl
        $curl = curl_init();
        
        // set de l'url du contenu à recuperer
        curl_setopt($curl, CURLOPT_URL, $link);
        // recuperation du contenu sous forme de chaine
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        // execution
        $datas= curl_exec ($curl);
        // fermeture de curl
        curl_close($curl);
        
        // creation de l'objet dom document
        $page = new \DOMDocument();
        // chagement du document sous forme de HTML
        @$page->loadHTML($datas);
        
        foreach($page->getElementsByTagName('div') as $div){
            // si le nombre de post recupérer n'est pas atteind
            if($this->_cptPost < $this->_nbPost) {
                // on recupere la prochaine div article
                if($this->isArticle($div)){
                    
                    $idVdm = $this->getIdVdm($div);
                    $content = $this->getArticleContent($div);
                    
                    $arrayDateUser = $this->getArticleDateUser($div);
                    // formatage de la date pour la base de données
                    $dateFr = $arrayDateUser['date'];
                    $date = \DateTime::createFromFormat('d/m/Y', $dateFr);
                    $date->format('Y-m-d');
                    
                    $user = $arrayDateUser['user'];
                    
                    $this->addArticle($content, $date, $user, $idVdm);
                    
                    $this->_cptPost++;
                }
            }
        }
    }
    /**
     * Méthode pour tester si la div recuperer est un article.
     * 
     * @param type $div
     * @return boolean
     */
    protected function isArticle($div){
        $isArticle = strstr($div->getAttribute('class'),'article');
        
        if($isArticle == false){
            return false;
        } else {
            return true;
        }
    }
    /**
     * Méthode pour récuperer l'id de l'article
     * 
     * @param type $div
     * @return type integer
     */
    protected function getIdVdm($div){
        $idVdm = $div->getAttribute('id');
        
        return $idVdm;
    }

    /**
     * Méthode pour récupérer le contenu de l'article
     * 
     * @param type $div
     * @return type string
     */
    protected function getArticleContent($div){
        $articleContent = $div->getElementsByTagName('p')->item(0)->nodeValue;
        
        return utf8_decode($articleContent);
    }
    /**
     * Méthode pour recupérer les information date et utilisateur de l'article
     * 
     * @param type $div
     * @return type array
     */
    protected function getArticleDateUser($div){
        $container = $div->getElementsByTagName('div')->item(0)->nodeValue;
        // recuperation de la chaine après "Le"             
        $arrayData = explode('Le', $container);
        // création du tableau avec les informations après la chaine "Le"
        $arrayData = explode(' ', $arrayData[1]);
        // la date se trouve à l'index 1 du tableau et le nom de l'utiliosateur se trouve à l'index 8
        $arrayInfo = array('date'=>$arrayData[1], 'user' => utf8_decode($arrayData[8]));
        
        return $arrayInfo;
    }
    /**
     * Méthode d'ajout de post
     * 
     * @param type $content contenu du post
     * @param type $date date du post
     * @param type $user auteur
     * @param type $idVdm idvdm du post
     */
    protected function addArticle($content, $date, $user, $idVdm){
       
        $em = $this->getContainer()->get('doctrine')->getEntityManager('default');
        
        $article = $em->getRepository('VDMApiBundle:Article')->findOneBy(array('idVdm'=>$idVdm));
        
        
        if(!$article) {
            $this->_postsAdd++;
            $article = new Article();
            $article->setIdVdm($idVdm);
        } else {
            $this->_postsUpdate++;
        }

        $article->setContent($content);
        $article->setDate($date);
        $article->setUser($user);
        
        $em->persist($article);
        $em->flush();
    }
}

