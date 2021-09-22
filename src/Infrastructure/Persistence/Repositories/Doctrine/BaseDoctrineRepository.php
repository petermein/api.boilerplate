<?php

declare(strict_types=1);

namespace Api\Infrastructure\Persistence\Repositories\Doctrine;

use Doctrine\Common\Persistence\ObjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;

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
     * @param EntityManagerInterface $entityManager
     * @param EntityRepository $genericRepository
     */
    public function __construct(EntityManagerInterface $entityManager, EntityRepository $genericRepository)
    {
        $this->genericRepository = $genericRepository;
        $this->entityManager = $entityManager;
    }
}
