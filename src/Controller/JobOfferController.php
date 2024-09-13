<?php

namespace App\Controller;

use App\Entity\Candidate;
use App\Entity\JobOffer;
use App\Form\CandidateFormType;
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
    private static int $PER_PAGE = 7;

    public function all(
        Request            $request,
        PaginatorInterface $paginator,
        JobOfferRepository $jobOfferRepository
    ): Response
    {

        $pagination =
            $paginator->paginate(
                $jobOfferRepository->paginator(),
                $request->query->getInt('page', $request->query->getInt('page', 1)),
                $request->query->getInt('perPage', self::$PER_PAGE)
            );

        return $this->render('job_offer/index.html.twig', [
            'pagination' => $pagination,
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
            'pagination' => $offers,
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

    public function apply(
        int                         $offerId,
        Request                     $request,
        EntityManagerInterface      $entityManager
    ): Response
    {
        $offer = $entityManager->getRepository(JobOffer::class)->find($offerId);

        $form = $this->createForm(CandidateFormType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $name = $form->get('name')->getData();
            $lastname = $form->get('lastname')->getData();
            $email = $form->get('email')->getData();
            $phoneNumber = $form->get('phoneNumber')->getData();
            $candidate = $entityManager->getRepository(Candidate::class)->findOneBy([
                'name' => $name,
                'lastname' => $lastname,
                'email' => $email,
                'phoneNumber' => $phoneNumber,
            ]);

            if (!$candidate) {
                $candidate = new Candidate();
            }

            $candidate->setName($name);
            $candidate->setLastname($lastname);
            $candidate->setEmail($email);
            $candidate->setPhoneNumber($phoneNumber);
            $candidate->addOffer($offer);
            $candidate->setUser($this->getUser());
            $entityManager->persist($candidate);
            $entityManager->flush();

            return $this->render('job_offer/success.html.twig');
        }

        return $this->render('job_offer/apply.html.twig', [
            'offer' => $offer,
            'candidateForm' => $form,
        ]);
    }
}
