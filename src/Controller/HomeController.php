<?php

namespace App\Controller;

use App\Entity\AnonUser;
use App\Service\MessageService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mercure\HubInterface;
use Symfony\Component\Mercure\Update;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(private UserService $userService, private MessageService $messageService)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(Request $request, HubInterface $hub): Response
    {
        $user = $this->getAnonUser($request);

        $newMessage = new Update(
            'newMessage',
            json_encode(['connected' => $user->getName()])
        );

        $hub->publish($newMessage);

        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
        ]);
    }

    #[Route('/message', name: 'app_new_message', methods: 'POST')]
    public function newMessage(Request $request, HubInterface $hub): Response
    {
        $user = $this->getAnonUser($request);
        $content = $request->getPayload()->get('content');

        $this->messageService->handleNewMessage($user, $content);

        $newMessage = new Update(
            'newMessage',
            json_encode($content)
        );

        $hub->publish($newMessage);

        return new JsonResponse($content, Response::HTTP_CREATED);
    }

    private function getAnonUser(Request $request): AnonUser
    {
        return $this->userService->getOrCreateAnonUser(
            $request->server->get('REMOTE_ADDR'),
            $request->server->get('HTTP_USER_AGENT')
        );
    }
}
