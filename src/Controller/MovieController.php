<?php

namespace App\Controller;

use App\Service\OmdbApi;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Finder\Exception\AccessDeniedException;
use Symfony\Component\HttpFoundation\File\Exception\AccessDeniedException as FileExceptionAccessDeniedException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Core\Exception\AccessDeniedException as ExceptionAccessDeniedException;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/movie', name: 'movie_')]
class MovieController extends AbstractController
{
    #[Route('/search', name: 'search')]
    public function search(Request $request, OmdbApi $omdbApi): Response
    {
        $keyword = $request->query->get('keyword', 'Harry Potter');
        $movies = $omdbApi->requestAllBySearch($keyword)['Search'];
        dump($keyword, $movies);

        return $this->render('movie/search.html.twig', [
            'movies' => $movies,
            'keyword' => $keyword,
        ]);
    }
    
    #[Route('/latest', name: 'latest')]
    //#[IsGranted('ROLE_ADMIN', statusCode: 400, message: 'Accès refusé ;(')]
    public function latest(): Response
    {
        if (!$this->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedHttpException('Dommage...');
        }
        
        return $this->render('movie/latest.html.twig');
    }
    
    #[Route('/{id}', name: 'show')]
    public function show(int $id = 1): Response
    {
        dump($id);
        
        return $this->render('movie/show.html.twig');
    }
}
