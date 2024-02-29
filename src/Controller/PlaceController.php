<?php

namespace App\Controller;

use App\Entity\Place;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class PlaceController extends AbstractController
{

    private $validator;

    public function __construct(ValidatorInterface $validator)
    {
        $this->validator = $validator;
    }

    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    #[Route('/place/new-place', name: 'app_place_new', methods: ['GET', 'POST'])]
    public function newPlace(Request $request, EntityManagerInterface $entityManager): Response
    {
        $data = json_decode($request->getContent(), true);

        // Créer une nouvelle instance de Place avec les données du formulaire
        $place = new Place();
        $place->setName($data['name']);
        $place->setAddress($data['address']);
        $place->setLatitude($data['latitude']);
        $place->setLongitude($data['longitude']);

        // Valider et persister l'entité Place
        $errors = $this->validator->validate($place);

        if (count($errors) > 0) {
            // Gérer les erreurs de validation
            $response = ['success' => false, 'errors' => $errors];
        } else {
            $entityManager->persist($place);
            $entityManager->flush();

            // Envoyer une réponse réussie avec les données du lieu créé
            $response = ['success' => true, 'place' => $place];
        }
        return $this->json($response);
    }
}
