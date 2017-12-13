<?php

ini_set('display_errors', '1');
$input = file("input.txt", FILE_IGNORE_NEW_LINES);

$map = new Map($input);
echo "Part 1: " . $map->findBottomNode();
echo "<br />Part 2: ";
$map->findWeightDiffInUnbalanced();

class Map
{
    protected $originalInput;
    protected $input;
    protected $topNode;
    public function __construct(array $input)
    {
        $this->input = $this->convertInputToObjects($input);
        $this->originalInput = $this->convertInputToObjects($input);

        $this->findParents();
        $this->calcAllWeights();
    }

    public function findBottomNode(): string
    {
        /** @var Program $program */
        foreach ($this->input as $program) {
            if (empty($program->parent)) {
                $this->topNode = $program->name;
                return $program->name;
            }
        }
        return 'Not found';
    }

    public function findWeightDiffInUnbalanced(): int
    {
        $checked = [];

        /** @var Program $program */
        foreach ($this->input as $program) {
            if (!in_array($program->name, $checked)) {
                $checked = $this->getWeightDifference($program, $checked);
            }
        }

        return 0;
    }

    private function getWeightDifference($program, $checked): array
    {
        if (!is_null($program->parent)) {
            $siblings = $this->input[$program->parent]->children;
            $weights = [];
            $names = [];

            foreach ($siblings as $sibling) {
                $weights[] = $this->input[$sibling]->weight;
                $names[] = $this->input[$sibling]->name;

                $checked[] = $sibling;
            }

            $max = max($weights);
            $min = min($weights);

            if ($max !== $min) {
                $tooHeavy = $names[array_search($max, $weights)];
                $weightDiff = $max - $min;

                echo $this->originalInput[$tooHeavy]->weight - $weightDiff . ", ";
            }
        }

        return $checked;
    }

    private function calcAllWeights(): void
    {
        $input = $this->input;
        $done = [];

        /** @var Program $node */
        foreach ($input as $node) {
            if (!in_array($node->name, $done) && empty($node->children)) {
                $done = $this->getNodeWeight($node, $done);
            }
        }
    }

    private function convertInputToObjects(array $input): array
    {
        $output = [];

        foreach ($input as $params) {
            $programAndChildren = explode('->', $params, 2);
            $programParams = explode(' ', $programAndChildren[0]);

            $program = new Program(trim($programParams[0]), $programParams[1]);

            if (!empty($programAndChildren[1])) {
                $untrimmedChildren = explode(', ', $programAndChildren[1]);
                foreach ($untrimmedChildren as $child) {
                    $program->children[] = trim($child);
                }
            }

            $output[$program->name] = $program;
        }

        return $output;
    }

    private function findParents(): void
    {
        $input = $this->input;

        /** @var Program $program */
        foreach ($input as $program) {
            if (!empty($program->children)) {
                foreach ($program->children as $childName) {
                    $this->input[$childName]->parent = $program->name;
                    unset($input[$childName]);
                }
            }
        }
    }

    private function getNodeWeight(Program $node, array $inputDone): array
    {
        if (!is_null($node->parent)) {
            $siblings = $this->input[$node->parent]->children;

            foreach ($siblings as $program) {
                $this->input[$node->parent]->weight += $this->input[$program]->weight;
                //I love you...<3
                //I am the conductor of the POOP train!!!

                $inputDone[] = $program;
            }

            if ($this->topNode !== $this->input[$node->parent]->name && !in_array($this->input[$node->parent]->name, $inputDone)) {
                $this->getNodeWeight($this->input[$node->parent], $inputDone);
            }
        }

        return $inputDone;
    }
}

class Program
{
    public $name;

    public $weight;

    public $children;

    public $parent;

    public function __construct($name, $weight)
    {
        $this->name = $name;
        $this->weight = (int) str_replace(['(', ')'], '', $weight);
    }
}
