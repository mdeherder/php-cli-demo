<?php

namespace Mdeherder\PhpCliDemo\Commands;

use Mdeherder\PhpCliDemo\Services\InputOutput;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

#[AsCommand(
    name: 'tdm',
    description: 'Table de Multiplication!',
    aliases: ['mult'],
)]
class Play extends Command
{
    /**
     * @var array<int, string> list of exclamations in case of error
     */
    protected const EXCLAM = ['Oh Non! c\'est', 'Oooups! c\'était', 'M\'enfin, c\'est', 'N\'importe quoi! c\'est'];
    protected const MAXLOOP = 20;

    /**
     * @var int number of questions already sent
     */
    protected static $loop = 0;

    /**
     * @var int number of correct answers
     */
    protected static $score = 0;

    /**
     * @var array<int, string> items already proposed
     */
    protected static $items = [];

    /**
     * @var int Current number of questions asked for this play
     */
    protected static $curloop = 10;

    /**
     * @var int start time
     */
    protected static $start_time;

    protected function configure(): void
    {
        $this
            ->addArgument('firstName', InputArgument::OPTIONAL, 'Prénom', null)
            ->addOption('max', 'm', InputOption::VALUE_REQUIRED, 'Nombre maximum d\'itération', 10)
        ;
    }

    /**
     * Execute the command.
     *
     * @return int 0 if everything went fine, or an exit code
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $term1 = rand(1, 9);
        $term2 = rand(2, 9);
        $result = $term1 * $term2;
        $item = strval($term1).'x'.strval($term2);
        if (in_array($item, self::$items)) {
            return $this->execute($input, $output);
        }
        self::$items[] = $item;
        self::$items[] = strval($term2).'x'.strval($term1);
        ++self::$loop;

        $io = new InputOutput($input, $output);

        if (1 === self::$loop) {
            /** @var ?string $firstName */
            $firstName = strval($input->getArgument('firstName'));
            $loop = intval($input->getOption('max'));
            if (0 === $loop) {
                $io->error('Invalid MAX value (0)');

                return Command::INVALID;
            }
            self::$curloop = ($loop > self::MAXLOOP) ? self::MAXLOOP : $loop;
            $io->annonce(sprintf('Bonjour %s! Exerçons-nous aux tables de multiplication.', $firstName ?? 'vous'));
            self::$start_time = time();
        }

        $answer = (int) $io->question(sprintf('Combien font %s x %s ', $term1, $term2));

        if ($answer === $result) {
            $io->right('Bravo !');
            ++self::$score;
        } else {
            $io->wrong(sprintf(self::EXCLAM[rand(0, count(self::EXCLAM) - 1)].'... %s', $result));
        }

        if (self::$loop < self::$curloop) {
            return $this->execute($input, $output);
        }
        $seconds = time() - self::$start_time;
        $percent = round((self::$score * 100) / self::$curloop, 2);
        $io->result(sprintf('Votre score est de %s sur %s (%s%%) en %s secondes', self::$score, self::$curloop, $percent, $seconds));

        return Command::SUCCESS;
    }
}
