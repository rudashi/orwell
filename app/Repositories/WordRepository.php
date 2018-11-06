<?php

namespace App\Repositories;

use App\Models\Engine;
use App\Repositories\Contracts\WordInterface;

class WordRepository implements WordInterface
{

    /**
     * @var \Illuminate\Database\DatabaseManager
     */
    private $db;

    public function __construct()
    {
        $this->db = app('db');
    }

    public function anagram(string $letters) : \Illuminate\Support\Collection
    {
        $engine = new Engine($letters);
        $engine->validateCharacters();

        $words = $this->db
            ->table('words')
            ->select('word')
            ->selectRaw('
            (
                SELECT SUM(points)
                FROM ( SELECT regexp_split_to_table(words.word,E\'(?=.)\') the_word ) tab 
                LEFT JOIN alphas ON the_word = letter
             ) as points')
            ->where('characters', '<@', $engine->getSearchValue())
            ->whereRaw('char_length(word) <= ?', $engine->getCharactersCount())
            ->whereRaw('(SELECT count(*) FROM ( SELECT unnest(characters) EXCEPT ALL SELECT unnest(? :: CHAR []) ) extra) <= ?', [$engine->getSearchValue(), $engine->getWildcardsCount()])
            ->orderByRaw('char_length(word) DESC')
            ->orderByDesc('points')
            ->limit($engine->getLimit())
            ->get();

        if ($words->isEmpty()) {
            throw new \RuntimeException('Found nothing.');
        }

        return $words;
    }

    public function prepareInputSearch(string $input) : string
    {
        $input = urldecode($input);

        if ($this->validateLetters($input) === false) {
            throw new \RuntimeException('Bad search parameters.');
        }
        return $input;
    }

    private function validateLetters(string $letters) : bool
    {
        return preg_match('/^[A-ZĄĆĘŁŃÓŚŹŻ]*$/iu', $letters);
    }
}