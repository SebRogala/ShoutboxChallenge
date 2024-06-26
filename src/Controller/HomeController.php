<?php

namespace App\Controller;

use App\Entity\AnonUser;
use App\Form\NewMessageFileType;
use App\Form\NewMessageType;
use App\Service\DtoSerializer;
use App\Service\MessageService;
use App\Service\UserService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class HomeController extends AbstractController
{
    public function __construct(private UserService $userService, private MessageService $messageService)
    {
    }

    #[Route('/', name: 'app_home')]
    public function index(int $maxMessagesToShow, DtoSerializer $serializer): Response
    {
        $messages = $this->messageService->getInitialMessages();

        return $this->render('home/index.html.twig', [
            'messages' => $serializer->toArray($messages),
            'maxMessagesToShow' => $maxMessagesToShow,
        ]);
    }

    #[Route('/message', name: 'app_new_message', methods: 'POST')]
    public function newMessage(Request $request): Response
    {
        $user = $this->getAnonUser($request);

        $form = $this->createForm(NewMessageType::class);
        $form->submit($request->getPayload()->all());

        if (!$form->isSubmitted() || !$form->isValid()) {
            return new JsonResponse((string)$form->getErrors(true), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->messageService->handleNewMessage($user, $form->getData()['content']);

        return new JsonResponse('', Response::HTTP_CREATED);
    }

    #[Route('/image', name: 'app_new_image', methods: 'POST')]
    public function newImage(Request $request, string $messageFileUploadDir): Response
    {
        $user = $this->getAnonUser($request);

        $form = $this->createForm(NewMessageFileType::class);
        $form->submit($request->files->all());

        if (!$form->isSubmitted() || !$form->isValid()) {
            return new JsonResponse((string)$form->getErrors(true), Response::HTTP_UNPROCESSABLE_ENTITY);
        }

        $this->messageService->handleNewImage($user, $form->getData()['image'], $messageFileUploadDir);

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
