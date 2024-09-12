<?php

namespace App\Controller;

use App\Entity\JobOffer;
use App\Repository\JobOfferRepository;
use Doctrine\ORM\EntityManagerInterface;
use Elastica\Query;
use Elastica\Query\BoolQuery;
use Elastica\Query\MatchQuery;
use FOS\ElasticaBundle\Manager\RepositoryManagerInterface;
use Knp\Component\Pager\Pagination\PaginationInterface;
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

    public function search(
        Request                    $request,
        RepositoryManagerInterface $rm,
        PaginatorInterface         $paginator,
        EntityManagerInterface     $em,
    ): Response
    {
        $page = $request->query->getInt('page', 1);
        $limitPerPage = $request->query->getInt('perPage', self::$PER_PAGE);
        $searchString = $request->get('q');

        if ($searchString != null) {
            $searched = true;
            $offers = $this->createSearchPagination($rm, $paginator, $searchString, $page, $limitPerPage);
        } else {
            $searched = false;
            $offers = $paginator->paginate($em->getRepository(JobOffer::class)->findAll(), $page, $limitPerPage);
        }

        return $this->render('job_offer/index.html.twig', [
            'offers' => $offers,
            'searched' => $searched
        ]);
    }

    private function createSearchPagination(
        RepositoryManagerInterface $rm,
        PaginatorInterface         $paginator,
        string                     $searchString,
        int                        $currentPage,
        int                        $perPage
    ): PaginationInterface
    {
        $repository = $rm->getRepository(JobOffer::class);

        $titleQuery = (new MatchQuery())
            ->setFieldFuzziness('title', 'AUTO')
            ->setFieldQuery('title', $searchString);

        $boolQuery = (new BoolQuery())->addShould($titleQuery);

        $searchString = explode(' ', $searchString);

        foreach ($searchString as $part) {
            $nameQuery = (new MatchQuery())
                ->setFieldFuzziness('title', 'AUTO')
                ->setFieldQuery('title', $part);
            $boolQuery->addShould($nameQuery);
        }

        $boolQuery->setMinimumShouldMatch(1);
        $query = new Query();
        $query->setQuery($boolQuery);

        return $paginator->paginate(
            $repository->createPaginatorAdapter($query),
            $currentPage,
            $perPage
        );
    }

    public function apply(EntityManagerInterface $em, int $offerId): Response
    {
        $offer = $em->getRepository(JobOffer::class)->find($offerId);

        return $this->render('job_offer/apply.html.twig', [
            'offer' => $offer,
        ]);
    }
}
