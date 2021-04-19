<?php

namespace App\Tests;

use App\Service\AdvisorService;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class AdvisorServiceTest extends TestCase
{
    /** @var EntityManagerInterface | MockObject */
    private $em;

    /** @var AdvisorService */
    private $sut;

    public function setUp(): void
    {
        $this->initSut();
        parent::setUp();
    }

    /**
     *
     */
    public function testCreateAdvisorCallsEmFlush(){
        $this->em->expects($this->once())->method('flush');
        $this->sut->createAdvisor([
            'name' => 'Peter Pan',
            'pricePerMinute' => 10,
            'languages' => ['en','pt']
        ]);
    }

    /**
     *
     */
    public function testCreateAdvisorThrowsBadRequestWhenMissesParam(){
        $this->expectException(BadRequestException::class);
        $this->sut->createAdvisor([
            'name' => 'Peter Pan',
            'languages' => ['en','pt']
        ]);
    }

    /**
     * @return AdvisorService
     */
    private function initSut(){
        $this->em = $this->createMock(EntityManagerInterface::class);
        $this->sut = new AdvisorService($this->em);
    }
}