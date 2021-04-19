<?php

namespace App\Service;

use App\Entity\Advisor;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;

class AdvisorService
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function createAdvisor(array $data)
    {
        $advisor = new Advisor();
        $this->setFields($advisor, $data);
        $this->entityManager->persist($advisor);
        $this->entityManager->flush();
        return $advisor;
    }

    public function updateAdvisor(Advisor $advisor, array $data)
    {
        $this->setFields($advisor, $data);
        $advisor->setAvailability($data['availability']);
        $this->entityManager->persist($advisor);
        $this->entityManager->flush();
    }

    private function setFields(Advisor $advisor, $data = [])
    {
        if (!(isset($data['name']) && isset($data['pricePerMinute']) && !empty($data['languages']))) {
            throw new BadRequestException('Mandatory fields weren\'t provided' );
        }
        $advisor->setName($data['name']);
        $advisor->setPricePerMinute($data['pricePerMinute']);
        $advisor->setLanguages($data['languages']);
        $advisor->setDescription($data['description'] ?? null);
        if (isset($data['image'])) {
            $advisor->setImage($data['image']);
        }
    }

    public function deleteAdvisor(Advisor $advisor)
    {
        $this->entityManager->remove($advisor);
        $this->entityManager->flush();
    }

}