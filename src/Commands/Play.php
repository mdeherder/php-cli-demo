<?php

namespace Mdeherder\PhpCliDemo\Commands;

use Mdeherder\PhpCliDemo\Services\InputOutput;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Play extends Command
{
    public const MAXLOOP = 2;

    /**
     * The name of the command (the part after "bin/demo").
     *
     * @var string
     */
    protected static $defaultName = 'play';

    /**
     * The command description shown when running "php bin/demo list".
     *
     * @var string
     */
    protected static $defaultDescription = 'Play the game!';

    /**
     * Number of answer already sent.
     *
     * @var int
     */
    protected static $loop = 0;

    /**
     * Maximum of answer.
     *
     * @var int
     */
    protected static $maxloop = 2;

    /**
     * Numbrer of correct response.
     *
     * @var int
     */
    protected static $score = 0;

    /**
     * Start Time.
     *
     * @var int
     */
    private $start_time;

    public function __construct()
    {
        $this->start_time = time();
        parent::__construct();
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

        $answer = (int) $io->question(sprintf('Combien font %s x %s ?', $term1, $term2));

        if ($answer === $result) {
            $io->right('Bravo!');
            ++self::$score;
        } else {
            $io->wrong(sprintf('Oh non! c\'était %s', $result));
        }

        if (self::$loop < self::MAXLOOP) {
            return $this->execute($input, $output);
        }
        $seconds = time() - $this->start_time;
        $io->result(sprintf('Votre score est de %s sur %s en %s secondes', self::$score, self::MAXLOOP, $seconds));

        return Command::SUCCESS;
    }
}
