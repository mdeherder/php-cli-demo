<?php

namespace Mdeherder\PhpCliDemo\Services;

use Symfony\Component\Console\Style\SymfonyStyle;
use function Termwind\render;

class InputOutput extends SymfonyStyle
{
    /**
     * Ask a question and return the answer.
     */
    public function question(string $question): string
    {
        return strval($this->ask($question));
    }

    // Display a message in case of right answer.
    public function annonce(string $message): void
    {
        render('<div class="px-2">&nbsp;<div class="p-1 bg-yellow-600">'.$message.'</div></div>');
    }

    // Display a message in case of right answer.
    public function right(string $message): void
    {
        render('<div class="px-2"><div class="p-1 bg-green-900">'.$message.'</div></div>');
    }

    // Display a message in case of wrong answer.
    public function wrong(string $message): void
    {
        render('<div class="px-2"><div class="p-1 bg-red-600">'.$message.'</div></div>');
    }

    // Display a info message.
    public function result(string $message): void
    {
        render('<div class="px-2">&nbsp;<div class="p-1 bg-blue-600">'.$message.'</div></div><div>&nbsp;</div>');
        render('<span>&nbsp;</span>');
        //     $this->block($message, null, 'fg=white;bg=blue', ' ', true);
    }
}
