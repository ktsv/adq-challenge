<?php


namespace App\Service;


use App\Entity\Advisor;
use Doctrine\ORM\EntityManagerInterface;

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
        $advisor->setName($data['name']);
        $advisor->setPricePerMinute($data['pricePerMinute']);
        $advisor->setLanguages($data['languages'] ?? null);
        $advisor->setDescription($data['description'] ?? null);
        if ($data['image']){
            $advisor->setImage($data['image']);
        }
    }

    public function deleteAdvisor(Advisor $advisor)
    {
        $this->entityManager->remove($advisor);
        $this->entityManager->flush();
    }

}