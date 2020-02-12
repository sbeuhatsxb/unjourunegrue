<?php


namespace App\Controller;

use App\Service\ManagePictures;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


class HomeController extends AbstractController
{
    /**
     * @var ManagePictures
     */
    protected $managePictures;

    public function __construct(ManagePictures $managePictures)
    {
        $this->managePictures = $managePictures;
    }

    /**
     * @Route("/", name="home")
     */
    public function number()
    {

        $this->managePictures->pictureOfTheDayManager();

        $allowed = false;

        $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

        $user = $this->getUser();

        if($user->getAllowed() == true){
            $allowed = true;
        };

        return $this->render('base.html.twig', [
            'allowed' => $allowed,
        ]);
    }
}