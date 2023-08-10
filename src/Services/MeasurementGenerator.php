<?php

declare(strict_types=1);

namespace App\Services;

use App\Entity\Measurement;
use App\Entity\Module;
use App\Exceptions\TimeOutException;
use DateTimeImmutable;
use Doctrine\ORM\EntityManagerInterface;


readonly class MeasurementGenerator
{
    public function __construct(private EntityManagerInterface $em)
    {
    }

    /**
     * @throws TimeOutException
     * @throws \Exception
     */
    public function generateMeasurements(array $modules, DateTimeImmutable $endTime): void
    {
        $currentDateTime = new DateTimeImmutable();

        if ($currentDateTime->getTimestamp() >= $endTime->getTimestamp()) {
            throw new TimeOutException();
        }

        $randomModuleIndex = random_int(0, count($modules) - 1);

        $measurement = new Measurement();
        $measurement->setModule($modules[$randomModuleIndex]);
        $valueTrigger = random_int(0, 5) === 0;

        if ($valueTrigger) {
            $measurement->setValue(null);
        } else {
            $measurement->setValue(random_int(-10, 50));
        }

        $this->em->persist($measurement);
        $this->em->flush();

        sleep(random_int(0, 3));

        $modules = $this->em->getRepository(Module::class)->findAll();

        $this->generateMeasurements($modules, $endTime);
    }
}