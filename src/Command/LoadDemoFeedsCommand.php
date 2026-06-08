<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Feed;
use App\Repository\FeedRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\Yaml\Yaml;

/**
 * Loads the ux-calendar-bundle demo calendars into the Feed table.
 *
 * The demo ships with the bundle, so we read its config straight from vendor.
 * URLs there are escaped for Symfony container config (`%%` for a literal `%`),
 * so we undo that when storing them as plain feed URLs.
 */
#[AsCommand('app:load-demo-feeds', 'Load the ux-calendar-bundle demo calendars (from vendor) into the Feed table')]
final class LoadDemoFeedsCommand
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly FeedRepository $feedRepository,
        #[Autowire('%kernel.project_dir%')]
        private readonly string $projectDir,
    ) {
    }

    public function __invoke(SymfonyStyle $io): int
    {
        $path = $this->projectDir.'/vendor/survos/ux-calendar-bundle/demo/config/packages/survos_ux_calendar.yaml';
        if (!is_file($path)) {
            $io->error(sprintf('Demo config not found at %s — is survos/ux-calendar-bundle installed?', $path));

            return Command::FAILURE;
        }

        $calendars = Yaml::parseFile($path)['survos_ux_calendar']['calendars'] ?? [];
        if (!$calendars) {
            $io->warning('No calendars found in the demo config.');

            return Command::SUCCESS;
        }

        $created = 0;
        $updated = 0;
        foreach ($calendars as $key => $cal) {
            $url = str_replace('%%', '%', (string) ($cal['url'] ?? '')); // undo Symfony param escaping
            if ('' === $url) {
                continue;
            }

            $feed = $this->feedRepository->findOneBy(['url' => $url]) ?? new Feed();
            $isNew = null === $feed->getId();

            $feed->setUrl($url);
            $feed->title = $cal['label'] ?? $key;
            $feed->color = $cal['color'] ?? null;

            $this->em->persist($feed);
            $isNew ? $created++ : $updated++;
            $io->writeln(sprintf('  <comment>%s</comment> <info>%s</info>', $isNew ? '+' : '~', $cal['label'] ?? $key));
        }

        $this->em->flush();
        $io->success(sprintf('Demo feeds loaded: %d created, %d updated.', $created, $updated));

        return Command::SUCCESS;
    }
}
