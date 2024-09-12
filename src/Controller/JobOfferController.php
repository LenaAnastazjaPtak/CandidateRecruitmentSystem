<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class JobOfferController extends AbstractController
{
    private static int $PER_PAGE = 2;

    public function all(
        Request            $request,
        PaginatorInterface $paginator,
        JobOfferRepository $jobOfferRepository
    ): Response
    {

        $offers =
            $paginator->paginate(
                $jobOfferRepository->paginator(),
                $request->query->getInt('page', $request->query->getInt('page', 1)),
                $request->query->getInt('perPage', self::$PER_PAGE)
            );

        return $this->render('job_offer/index.html.twig', [
            'offers' => $offers,
        ]);
    }

    public function apply(EntityManagerInterface $em, int $offerId): Response
    {
        $offer = $em->getRepository(JobOffer::class)->find($offerId);

        return $this->render('job_offer/apply.html.twig', [
            'offer' => $offer,
        ]);
    }
}
