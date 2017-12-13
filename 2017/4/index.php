<?php
ini_set('display_errors', '1');

$input = file("input.txt", FILE_IGNORE_NEW_LINES);

$passphrase = new Passphrase($input);
echo "Part 1: " . $passphrase->getPassphraseValidCount();

$passphraseAnagram = new PassphraseAnagram($input);
echo "<br/>Part 2: " . $passphraseAnagram->getPassphraseValidCount();

class Passphrase
{
    protected $input;

    public function __construct(array $input)
    {
        $this->input = $input;
    }

    public function getPassphraseValidCount(): int
    {
        $count = 0;

        foreach ($this->input as $input) {
            $count += $this->hasDuplicateWord($input) ? 0 : 1;
        }

        return $count;
    }

    protected function hasDuplicateWord(string $input): bool
    {
        $words = explode(' ', $input);

        for ($i = 0; $i < sizeof($words); $i++) {
            $word = array_shift($words);

            if (in_array($word, $words)) {
                return true;
            }

            array_push($words, $word);
        }

        return false;
    }
}

class PassphraseAnagram extends Passphrase
{
    protected function hasDuplicateWord(string $input): bool
    {
        $input = $this->sortWordLettersAlphabetical($input);

        return parent::hasDuplicateWord($input);
    }

    private function sortWordLettersAlphabetical($input): string
    {
        $words = explode(' ', $input);
        $input = '';

        foreach ($words as $word) {
            $letters = str_split($word);
            sort($letters);
            $input .= implode($letters) . " ";
        }

        return $input;
    }
}
