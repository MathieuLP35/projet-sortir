<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;


class UserController extends AbstractController
{
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/profile', name: 'app_user_profile')]
    public function profile(EntityManagerInterface $entityManager, Request $request): Response
    {
        $user = $this->getUser();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $profilePictureFile = $form->get('profilePictureFile')->getData();

            if ($profilePictureFile instanceof UploadedFile) {
                // Générez un nom unique pour le fichier
                $newFilename = $user->getId() . '-' . uniqid() . '.' . $profilePictureFile->guessExtension();

                // Déplacez le fichier vers le répertoire configuré
                try {
                    $profilePictureFile->move(
                        $this->getParameter('kernel.project_dir') . '/public/uploads/user_profile_pictures',
                        $newFilename
                    );
                    // Mettez à jour le chemin du fichier dans l'entité User
                    $user->setProfilePicture($newFilename);
                } catch (FileException $e) {
                    dd($e);
                    $this->addFlash('error', 'Erreur lors du téléchargement de la photo de profil.');
                    return $this->redirectToRoute('app_user_profile');
                }

                // Mettez à jour le chemin du fichier dans l'entité User
                $user->setProfilePicture($newFilename);
            }

            $entityManager->persist($user);
            $entityManager->flush();

            $this->addFlash('success', 'Profil mis à jour');

            return $this->redirectToRoute('app_user_profile', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/profile.html.twig', [
            'form' => $form,
            'user' => $user,
        ]);
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/profile/{id}', name: 'app_participant_profile')]
    public function show(int $id, UserRepository $user): Response
    {

        $participantProfil = $user->find($id);



        return $this->render('user/participant.html.twig', [
            'participantProfil' => $participantProfil,
        ]);
    }
}
