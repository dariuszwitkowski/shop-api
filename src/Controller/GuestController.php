<?php

namespace App\Controller;

use App\Service\GuestService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class GuestController extends AbstractController
{
    private GuestService $guestService;
    public function __construct(GuestService $guestService)
    {
        $this->guestService = $guestService;
    }
    /**
     * @Route("/api/guest/get-hash", name="guest_get-hash" , methods={"GET"})
     * @param Request $request
     * @return JsonResponse
     */
    public function getHash(Request $request): JsonResponse
    {
        $ip = $request->query->get("ip");
        $data = $this->guestService->getGuestHash($ip);
        return $this->json($data['hash'], $data['code']);
    }
}
