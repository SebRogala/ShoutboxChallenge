<?php

namespace App\Controller;

use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(private UserService $userService)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request, HubInterface $hub): Response
    {
        $user = $this->userService->getOrCreateAnonUser(
            $request->server->get('REMOTE_ADDR'),
            $request->server->get('HTTP_USER_AGENT')
        );

        $newMessage = new Update(
            'newMessage',
            json_encode(['connected' => $user->getName()])
        );

        $hub->publish($newMessage);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }
}
