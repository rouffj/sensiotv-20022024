<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/register', name: 'register')]
    public function register(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $userPasswordHasher): Response
    {
        dump($request->attributes->get('_route'));
        $userForm = $this->createForm(UserType::class);
        $userForm->add('submit', SubmitType::class, ['label' => 'Create your account.']);
        $userForm->handleRequest($request);

        if ($userForm->isSubmitted() && $userForm->isValid()) {
            $user = $userForm->getData();

            $hashedPassword = $userPasswordHasher->hashPassword($user, $user->getPassword());
            $user->setPassword($hashedPassword);
            // Insert into DB user...
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('homepage');
        }
        dump($userForm->getData());

        return $this->render('user/register.html.twig', [
            'userForm' => $userForm->createView(),
        ]);
    }
    
    #[Route('/login', name: 'signin')]
    public function signin(): Response
    {
        return $this->render('user/signin.html.twig');
    }
}
