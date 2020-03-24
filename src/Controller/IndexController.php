<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class IndexController extends AbstractController
{
    /**
     * @Route("/", name="app.index")
     */
    public function index()
    {
        return $this->render('index/index.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }


    /**
     * @Route("/remerciements", name="app.thanks")
     */
    public function thanks()
    {
        return $this->render('index/thanks.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    
    /**
     * @Route("/je-fais-un-don", name="app.donate")
     */
    public function donate()
    {
        return $this->render('index/donate.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
