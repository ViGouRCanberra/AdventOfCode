<?php

ini_set('display_errors', '1');
$input = file("input.txt", FILE_IGNORE_NEW_LINES);

$insRead = new Hash($input);

echo "Part1: ";
echo "<br/>Part2: ";

class Hash
{
    const LENGTH = 6;
    protected $string = [];
    protected $lengthSequence;
    protected $currentNode = 0;
    protected $skipSize = 0;

    public function __construct(array $input)
    {
        $this->lengthSequence = $input;

        $this->buildString();
        $this->processHash();

        $this->printString();
    }

    private function buildString(): void
    {
        for ($i = 0; $i < self::LENGTH; $i++) {
            $this->string[] = $i;
        }
    }

    private function processHash(): void
    {
        foreach ($this->lengthSequence as $span) {
            $this->twistString((int) $span);

            $this->skipNodes((int) $span);
        }
    }

    private function skipNodes(int $span): void
    {
        $this->currentNode += $this->skipSize;
        $this->currentNode += $span;

        ++$this->skipSize;
    }

    private function twistString(int $param): void
    {

    }

    private function printString(): void
    {
        foreach ($this->string as $node) {
            var_dump($node);
        }
    }
}