<?php


namespace App\Service;


use App\Entity\Guest;
use App\Exception\GuestExistenceException;
use App\Repository\GuestRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class GuestService
{
    private GuestRepository $guestRepository;
    private EntityManagerInterface $em;
    public function __construct(GuestRepository $guestRepository, EntityManagerInterface $em)
    {
        $this->guestRepository = $guestRepository;
        $this->em = $em;
    }
    public function getGuest(string $hash): Guest {
        $guest = $this->guestRepository->findOneBy(['hash' => $hash]);
        if(!$guest)
            throw new GuestExistenceException(true);

        return $guest;
    }
    public function getGuestHash(string $ip): array {
        $guest = $this->guestRepository->findOneBy(['ip' => $ip]);
        $code = Response::HTTP_CREATED;
        if(!$guest) {
            $guest = (new Guest())->setIp($ip)->setHash(md5($ip));
            $this->em->persist($guest);
            $this->em->flush();
            $code = Response::HTTP_OK;
        }
        return ['hash' => $guest->getHash(), 'code' => $code];
    }
}
