<?php

declare(strict_types=1);

namespace Api\Infrastructure\Persistence\Repositories\Doctrine;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;

abstract class BaseDoctrineRepository
{
    /**
     * @var ObjectRepository
     */
    protected $genericRepository;
    /**
     * @var EntityManagerInterface
     */
    protected EntityManagerInterface $entityManager;

    /**
     * BaseDoctrineRepository constructor.
     * @param EntityManagerInterface $entityManager
     * @param ObjectRepository $genericRepository
     */
    public function __construct(EntityManagerInterface $entityManager, ObjectRepository $genericRepository)
    {
        $this->genericRepository = $genericRepository;
        $this->entityManager = $entityManager;
    }
}
