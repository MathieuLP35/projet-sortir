<?php

namespace App\Controller;

use App\Entity\City;
use App\Form\CityFilterType;
use App\Form\CityType;
use App\Form\EventFilterType;
use App\Repository\CityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/city')]
class CityController extends AbstractController
{
    #[Route('/', name: 'app_city_index', methods: ['GET', 'POST'])]
    public function index(CityRepository $cityRepository, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CityType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($form->getData());
            $entityManager->flush();

            return $this->redirectToRoute('app_city_index');
        }

        $data = [];
        $cities = $cityRepository->findByFilter($data);

        $formFilter = $this->createForm(CityFilterType::class, null, [
            'action' => $this->generateUrl('app_event_index'),
            'method' => 'GET'
        ]);

        $formFilter->handleRequest($request);

        if ($formFilter->isSubmitted()) {
            if ($formFilter->get('name')->getData()) {
                $data['name'] = $formFilter->get('name')->getData();
            }

            $cities = $cityRepository->findByFilter($data);
        }


        return $this->render('city/index.html.twig', [
            'form' => $form,
            'form_city_filter' => $formFilter,
            'cities' => $cities,
        ]);
    }

    #[Route('/{id}', name: 'app_city_show', methods: ['GET'])]
    public function show(City $city): Response
    {
        return $this->render('city/show.html.twig', [
            'city' => $city,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}/edit', name: 'app_city_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, City $city, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CityType::class, $city);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_city_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('city/edit.html.twig', [
            'city' => $city,
            'form' => $form,
        ]);
    }

    #[IsGranted('ROLE_ADMIN')]
    #[Route('/{id}', name: 'app_city_delete', methods: ['POST'])]
    public function delete(Request $request, City $city, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$city->getId(), $request->request->get('_token'))) {
            $entityManager->remove($city);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_city_index', [], Response::HTTP_SEE_OTHER);
    }
}
