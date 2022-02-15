<?php

namespace Mdeherder\PhpCliDemo\Commands;

use Mdeherder\PhpCliDemo\Services\InputOutput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class Play extends Command
{
    /**
     * The name of the command (the part after "bin/demo").
     *
     * @var string
     */
    protected static $defaultName = 'tdm';

    /**
     * The command description shown when running "php bin/demo list".
     *
     * @var string
     */
    protected static $defaultDescription = 'Table de Multiplication!';

    /**
     * Number of questions already sent.
     *
     * @var int
     */
    protected static $loop = 0;

    /**
     * Number of correct answers.
     *
     * @var int
     */
    protected static $score = 0;

    /**
     * Max number of questions for this play.
     *
     * @var int
     */
    private $maxloop = 10;

    /**
     * Start Time.
     *
     * @var int
     */
    private $start_time;

    /**
     * Exclamation.
     *
     * @var array<int, string>
     */
    private $exclam = ['Oh Non! c\'est', 'Oooups! c\'était', 'M\'enfin, c\'est', 'N\'importe quoi! c\'est'];

    public function __construct()
    {
        $this->start_time = time();
        parent::__construct();
    }

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
        ++self::$loop;

        $io = new InputOutput($input, $output);

        if (1 === self::$loop) {
            /** @var ?string $firstName */
            $firstName = strval($input->getArgument('firstName'));
            $io->annonce(sprintf('Bonjour %s! Exerçons-nous aux tables de multiplication.', $firstName ?? 'vous'));
            $this->maxloop = intval($input->getOption('max'));
        }

        $answer = (int) $io->question(sprintf('Combien font %s x %s ', $term1, $term2));

        if ($answer === $result) {
            $io->right('Bravo !');
            ++self::$score;
        } else {
            $io->wrong(sprintf($this->exclam[rand(0, count($this->exclam) - 1)].'... %s', $result));
        }

        if (self::$loop < $this->maxloop) {
            return $this->execute($input, $output);
        }
        $seconds = time() - $this->start_time;
        $percent = round((self::$score * 100) / $this->maxloop, 2);
        $io->result(sprintf('Votre score est de %s sur %s (%s%%) en %s secondes', self::$score, $this->maxloop, $percent, $seconds));

        return Command::SUCCESS;
    }
}
