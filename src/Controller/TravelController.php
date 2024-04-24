<?php

namespace App\Controller;

use App\Action\CalculateTravel;
use App\Service\TravelService;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation;
use Symfony\Component\Routing\Attribute\Route;

class TravelController extends AbstractController
{

    /**
     * @throws Exception
     */
    #[Route('/api/travel/calculate', methods: 'POST')]
    public function getResult(Request $request, TravelService $service): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $parameters = json_decode($request->getContent(), true);
        $travel = new CalculateTravel($parameters['basicPrice'],
        new \DateTimeImmutable($parameters['dateStart']),
            new \DateTimeImmutable($parameters['userBirthday']),
            new \DateTimeImmutable($parameters['datePay'])
        );
        $travel = $service->getDiscountedPrice($travel);
        return new JsonResponse($travel->getBasicPrice(), 200);
    }
}