<?php

$tardis = new Tardis();

echo "High Score: " . $tardis->getHighScore() . PHP_EOL;

class Tardis
{
    const TOTAL_MARBLES = 22;
    const TOTAL_PLAYERS = 9;

    public function getHighScore(): string
    {
        $currentPlayer = 1;
        $queue = new MarbleRing();
        $queue->add(0, new Marble(0));
        $queue->add(1, new Marble(2));
        $queue->add(2, new Marble(1));
        $queue->add(3, new Marble(3));
        $queue->next();
        $queue->next();
        $queue->next();

        for ($i = 4; self::TOTAL_MARBLES >= $i; ++$i) {
            if (0 === $i % 23) {
                self::specialAddMarble($i, $queue);
            } else {
                self::normalAddMarble($i, $queue);
            }

            $currentPlayer = self::getNextPlayerNo($currentPlayer);
        }
self::printQueue($queue);

        return 'poot';
    }

    private function normalAddMarble(int $marbleNumber, MarbleRing $queue): void
    {
        $queue->next();
        $queue->next();
        $queue->add($queue->key(), new Marble($marbleNumber));
    }

    private function specialAddMarble(int $marbleNumber, MarbleRing $queue): void
    {

    }

    private function getNextPlayerNo(int $currentNo): int
    {
        return ++$currentNo > self::TOTAL_PLAYERS ? 1 : $currentNo;
    }

    private function printQueue(MarbleRing $queue): void
    {
        for ($i = 0; $i < $queue->count(); ++$i) {
            if ($i === $queue->key()) {
                echo '(';
            }

            echo $queue->offsetGet($i)->getId();

            if ($i === $queue->key()) {
                echo '), ';
            } else {
                echo ', ';
            }
        }

        echo PHP_EOL;
    }
}

class MarbleRing extends SplDoublyLinkedList
{
    private $index = 0;

    public function next(): void
    {
        if (self::count() === self::key() + 1) {
            $this->index = 0;

            return;
        }

        ++$this->index;
    }

    public function prev(): void
    {
        if (0 === self::key()) {
            $this->index = self::count() - 1;

            return;
        }

        --$this->index;
    }

    public function key(): int
    {
        return $this->index;
    }

    public function add($index, $newval): void
    {
        if (0 === $index) {
            $this->index = self::count();
            self::push($newval);

            return;
        }

        parent::add($index, $newval);
    }
}

class Marble
{
    private $id;

    public function __construct(int $id)
    {
        $this->id = $id;
    }

    public function getId()
    {
        return $this->id;
    }
}
