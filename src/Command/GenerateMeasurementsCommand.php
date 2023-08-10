<?php

declare(strict_types=1);

namespace App\Command;

use App\Exceptions\TimeOutException;
use App\Repository\ModuleRepository;
use App\Services\MeasurementGenerator;
use DateTimeImmutable;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(name: 'webreathe:generate-measurements')]
class GenerateMeasurementsCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private readonly ModuleRepository     $moduleRepository,
        private readonly MeasurementGenerator $generator
    )
    {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        $startTime = new DateTimeImmutable();
        $endTime = $startTime->modify('600 seconds');
        $modules = $this->moduleRepository->findAll();

        if (count($modules) > 0) {
            try {
                $this->generator->generateMeasurements($modules, $endTime);
            } catch (TimeOutException $exception) {
                $output->writeln($exception->getMessage());
                return Command::SUCCESS;
            } catch (\Exception $exception) {
                $output->writeln($exception->getMessage());
                return Command::FAILURE;
            }

            return Command::SUCCESS;
        }

        $output->writeln('Data was not generated. Create at least one module.');

        return Command::SUCCESS;
    }
}
