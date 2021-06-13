<?php
// src/Controller/WordController.php
namespace App\Controller;

use App\Repository\UserRepository;
use App\Repository\WordRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;

class WordController extends AbstractController
{
    public function index(): Response
    {
        return $this->render('word/index.html.twig');
    }

    public function submit(Request $request, UserRepository $userRepository, WordRepository $wordRepository): Response
    {
        $user = $userRepository->findOneBy([ 'ip' => $request->getClientIp() ]) ?: $userRepository->createUser($request->getClientIp());

        $text = trim($request->get('text'));

        $results = $wordRepository->prepareResults($user, $text);

        $data = [
            'text' => $text,
            'results' => $results,
        ];

        return $this->render('word/results.html.twig', $data);
    }
}
