<?php

ini_set('display_errors', '1');
$input = file("input.txt", FILE_IGNORE_NEW_LINES);

$insRead = new Hash($input);

echo "Part1: ";
echo "<br/>Part2: ";

class Hash
{
    const LENGTH = 6;
    protected $string;
    protected $lengthSequence;
    protected $currentLength;
    protected $skipSize;

    public function __construct(array $input)
    {
        $this->lengthSequence = $input;

        $this->buildString();
        $this->printString();
    }

    private function buildString(): void
    {
        for ($i = 0; $i < self::LENGTH; $i++) {
            $node = new Node($i);

            $prev = 0 !== $i ? $i-1 : self::LENGTH-1;
            $next = self::LENGTH !== ($i+1) ? $i+1 : 0;

            $node->setNextNode($next);
            $node->setPrevNode($prev);

            $this->string[] = $node;
        }
    }

    private function printString() {
        foreach ($this->string as $node) {
            var_dump($node);
        }
    }
}

class Node
{
    protected $prevNode;
    protected $value;
    protected $nextNode;

    public function __construct($value)
    {
        $this->value = $value;
    }

    public function getValue(): int
    {
        return $this->value;
    }

    public function getPrevNode(): int
    {
        return $this->prevNode;
    }

    public function setPrevNode($prevNode): void
    {
        $this->prevNode = $prevNode;
    }

    public function getNextNode(): int
    {
        return $this->nextNode;
    }

    public function setNextNode($nextNode): void
    {
        $this->nextNode = $nextNode;
    }
}