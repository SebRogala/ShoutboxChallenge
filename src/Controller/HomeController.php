<?php

namespace App\Controller;

use App\DTO\DTOHelperTrait;
use App\Entity\AnonUser;
use App\Service\MessageService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    use DTOHelperTrait;

    public function __construct(private UserService $userService, private MessageService $messageService)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(int $maxMessagesToShow): Response
    {
        $messages = $this->messageService->getInitialMessages();

        return $this->render('home/index.html.twig', [
            'messages' => $this->collectionToArray($messages),
            'maxMessagesToShow' => $maxMessagesToShow
        ]);
    }

    #[Route('/message', name: 'app_new_message', methods: 'POST')]
    public function newMessage(Request $request): Response
    {
        $user = $this->getAnonUser($request);
        $content = $request->getPayload()->get('content');

        $this->messageService->handleNewMessage($user, $content);

        return new JsonResponse('', Response::HTTP_CREATED);
    }

    private function getAnonUser(Request $request): AnonUser
    {
        return $this->userService->getOrCreateAnonUser(
            $request->server->get('REMOTE_ADDR'),
            $request->server->get('HTTP_USER_AGENT')
        );
    }
}
