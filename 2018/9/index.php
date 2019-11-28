<?php

$tardis = new Tardis();

echo "High Score: " . $tardis->getHighScore() . PHP_EOL;

class Tardis
{
    const TOTAL_MARBLES = 7072300;
//    const TOTAL_MARBLES = 25;
    const TOTAL_PLAYERS = 427;
//    const TOTAL_PLAYERS = 9;

    private $scores = [0];

    public function getHighScore(): int
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
                self::specialAddMarble($i, $queue, $currentPlayer);
            } else {
                self::normalAddMarble($i, $queue);
            }

            $currentPlayer = self::getNextPlayerNo($currentPlayer);

echo (($i / 7072300) * 100) . "\r";
        }
self::printQueue($queue);

        return max($this->scores);
    }

    private function normalAddMarble(int $marbleNumber, MarbleRing $queue): void
    {
        $queue->next();
        $queue->next();
        $queue->add($queue->key(), new Marble($marbleNumber));
    }

    private function specialAddMarble(int $marbleNumber, MarbleRing $queue, int $currentPlayer): void
    {
        self::addScore($currentPlayer, $marbleNumber);

        $queue->prev();
        $queue->prev();
        $queue->prev();
        $queue->prev();
        $queue->prev();
        $queue->prev();
        $queue->prev();

        self::addScore($currentPlayer, $queue->popCurrent()->getId());
    }

    private function addScore(int $currentPlayer, int $marbleNumber): void
    {
        if (!isset($this->scores[$currentPlayer])) {
            $this->scores[$currentPlayer] = 0;
        }

        $this->scores[$currentPlayer] += $marbleNumber;
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
    private $count = 0;

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

        ++$this->count;
        parent::add($index, $newval);
    }

    public function popCurrent(): Marble
    {
        --$this->count;
        $marble = self::offsetGet($this->index);
        self::offsetUnset($this->index);

        return $marble;
    }

    public function push($value)
    {
        ++$this->count;
        parent::push($value);
    }

    public function count(): int
    {
        return $this->count;
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
