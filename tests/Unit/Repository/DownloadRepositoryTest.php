<?php

namespace App\Tests\Unit\Repository;

use App\Entity\Download;
use App\Repository\DownloadRepository;
use DateTimeImmutable;
use Doctrine\ORM\AbstractQuery;
use Doctrine\ORM\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class DownloadRepositoryTest extends KernelTestCase
{
    protected DownloadRepository $repository;

    public function setUp(): void
    {
        parent::setUp();

        $this->repository = new DownloadRepository(
            $this->getContainer()->get(ManagerRegistry::class)
        );
    }

    /**
     * Test getDownloadsBetweenDatesByEpisode() returns an array of Downloads
     */
    public function testGetDownloadsBetweenDatesByEpisodeReturnsArrayOfDownloads(): void
    {
        // mock the Query object
        $query = $this->createMock(AbstractQuery::class);
        $query->expects($this->once())
            ->method('getResult')
            ->will($this->returnValue([new Download(), new Download()]));

        // mock the QueryBuilder
        $queryBuilder = $this->createMock(QueryBuilder::class);
        $queryBuilder->expects($this->once())->method('getQuery')->will($this->returnValue($query));
        $queryBuilder->method('andWhere')->will($this->returnSelf());
        $queryBuilder->method('setParameter')->will($this->returnSelf());

        // partial mock the DownloadRepository class
        $repository = $this->createPartialMock(DownloadRepository::class, ['createQueryBuilder']);
        $repository->method('createQueryBuilder')->will($this->returnValue($queryBuilder));

        $downloads = 
            $repository->getDownloadsBetweenDatesByEpisode(
                10, 
                new DateTimeImmutable(), 
                new DateTimeImmutable()
            );

        $this->assertIsArray($downloads);
        $this->assertInstanceOf(Download::class, array_pop($downloads));
    }
}
