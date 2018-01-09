<?php

ini_set('display_errors', '1');
$input = file("input.txt", FILE_IGNORE_NEW_LINES);

$hash = new Hash($input);

echo "Part1: " . $hash->getMultipleOfFirstTwo();
echo "<br/>Part2: ";

class Hash
{
    const LENGTH = 256;
    protected $string = [];
    protected $lengthSequence;
    protected $currentNode = 0;
    protected $skipSize = 0;

    public function __construct(array $input)
    {
        $this->lengthSequence = $input;

        $this->buildString();
        $this->processHash();

        //$this->printString();
    }

    public function getMultipleOfFirstTwo()
    {
        return $this->string[0] * $this->string[1];
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

        $this->currentNode = $this->currentPosition($span);

        ++$this->skipSize;
    }

    private function twistString(int $span): void
    {
        $pinchedString = $this->getPartOfString($span);

        $this->reinsertTwistedString(array_reverse($pinchedString));
    }

    private function getPartOfString($span): array
    {
        $partial = [];

        for ($i = 0; $i < $span; $i++) {
            $position = $this->currentPosition($i);
            $partial[] = $this->string[$position];
        }

        return $partial;
    }

    private function reinsertTwistedString(array $reversedArray)
    {
        for ($i = 0; $i < sizeof($reversedArray); $i++) {
            $position = $this->currentPosition($i);
            $this->string[$position] = $reversedArray[$i];
        }
    }

    private function currentPosition(int $modifier = 0, int $length = self::LENGTH): int
    {
        $nodePos = ($modifier + $this->currentNode + 1) % $length;

        return ($nodePos - 1) > -1 ? $nodePos - 1 : $length - 1;
    }

    private function printString(): void
    {
        foreach ($this->string as $node) {
            var_dump($node);
        }
    }
}