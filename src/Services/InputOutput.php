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

    // Display a message as title.
    public function annonce(string $message): void
    {
        render("<div class='mx-1 p-1 mt-1 bg-yellow-600 text-black'>{$message}</div>");
    }

    // Display a message in case of right answer.
    public function right(string $message): void
    {
        render("<div class='mx-1 p-1 bg-green-600 font-bold'>{$message}</div>");
    }

    // Display a message in case of wrong answer.
    public function wrong(string $message): void
    {
        render("<div class='mx-1 p-1 bg-red-600 font-bold'>{$message}</div>");
    }

    // Display a final info message.
    public function result(string $message): void
    {
        render("<div class='mx-1 p-1 my-1 bg-blue-600 font-bold'>{$message}</div>");
    }
}
