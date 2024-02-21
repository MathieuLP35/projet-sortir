<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/profile', name: 'app_user_profile')]
    public function index(EntityManagerInterface $entityManager, Request $request): Response
    {
        /** @var $user User */
        $user = $this->getUser();
        dump($user);

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Gestion du téléchargement de l'image de profil
            $profilePictureFile = $form->get('profilePictureFile')->getData();

            if ($profilePictureFile instanceof UploadedFile) {
                $newFilename = $user->getId() . '-' . uniqid() . '.' . $profilePictureFile->guessExtension();

                try {
                    $profilePictureFile->move(
                        $this->getParameter('profile_pictures_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                    // Gérer les erreurs de téléchargement ici, par exemple, en affichant un message flash
                    $this->addFlash('error', 'Erreur lors du téléchargement de la photo de profil.');
                    return $this->redirectToRoute('app_user_profile');
                }

                $user->setProfilePicture($newFilename);
            }
            
            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour');

            return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/profile.html.twig', [
            'form' => $form,
        ]);
    }
}
