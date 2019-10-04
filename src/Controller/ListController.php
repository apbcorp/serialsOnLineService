<?php

namespace App\Controller;

use App\Dictionary\MainDictionary;
use App\Formatter\FormatterRegistry;
use App\Formatter\SerialListFormatter;
use App\Repository\RatingRepository;
use App\Repository\SerialRepository;
use Symfony\Component\HttpFoundation\JsonResponse;

class ListController
{
    const LIMIT = 12;

    /**
     * @var RatingRepository
     */
    private $serialRepository;

    /**
     * @var FormatterRegistry
     */
    private $registry;

    public function __construct(SerialRepository $repository, FormatterRegistry $registry)
    {
        $this->serialRepository = $repository;
        $this->registry = $registry;
    }

    public function topAction()
    {
        $serials = $this->serialRepository->findBy([], ['id' => MainDictionary::ORDERING_ASC], self::LIMIT);

        return new JsonResponse($this->registry->getByName(SerialListFormatter::NAME)->format($serials));
    }
}