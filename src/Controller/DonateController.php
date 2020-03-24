<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

    /**
     * @Route("/je-fais-un-don")
     */
class DonateController extends AbstractController
{
    /**
     * @Route("/soignants", name="app.donate.care")
     */
    public function care()
    {
        return $this->render('donate/care.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }

    /**
     * @Route("/demunis", name="app.donate.vulnerable")
     */
    public function vulnerable()
    {
        return $this->render('donate/vulnerable.html.twig', [
            'controller_name' => 'IndexController',
        ]);
    }
}
