<?php

namespace Mdeherder\PhpCliDemo\Services;

use Symfony\Component\Console\Style\SymfonyStyle;

class InputOutput extends SymfonyStyle
{
    /**
     * Ask a question and return the answer.
     */
    public function question(string $question): string
    {
        return strval($this->ask($question));
    }

    /**
     * Display a message in case of right answer.
     */
    public function right(string $message): void
    {
        $this->block($message, null, 'fg=black;bg=green', ' ', true);
    }

    /**
     * Display a message in case of wrong answer.
     */
    public function wrong(string $message): void
    {
        $this->block($message, null, 'fg=white;bg=red', ' ', true);
    }

    /**
     * Display a info message.
     */
    public function result(string $message): void
    {
        $this->block($message, null, 'fg=white;bg=blue', ' ', true);
    }
}
