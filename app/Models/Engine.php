<?php

namespace App\Models;

class Engine
{

    /**
     * @var \Illuminate\Support\Collection
     */
    private $characters;

    private $charactersCount;

    private $wildcardsCount;

    private $searchValue;

    private $limit;

    public function __construct(string $characters, int $limit = 25)
    {
        $this->setCharacters(preg_split('//u', mb_strtolower($characters), -1, PREG_SPLIT_NO_EMPTY));

        $this->setWildcardsCount($this->countWildcards());

        $this->setSearchValue();

        $this->setLimit($limit);
    }

    public function validateCharacters() : bool
    {
        if ($this->getCharactersCount() < 2) {
            throw new \RuntimeException('Not enough characters for search.');
        }
        return true;
    }

    public function setCharacters(array $characters) : Engine
    {
        $this->characters = collect($characters)->mapInto(Alpha::class);

        $this->setCharactersCount();

        return $this;
    }

    public function setWildcardsCount(int $wildcardsCount) : Engine
    {
        $this->wildcardsCount = $wildcardsCount;

        return $this;
    }

    public function setLimit(int $limit) : Engine
    {
        $this->limit = $limit;

        return $this;
    }

    public function setCharactersCount(int $count = null) : Engine
    {
        $this->charactersCount = $count ?? $this->characters->count();

        return $this;
    }

    public function setSearchValue(\Illuminate\Support\Collection $characters = null) : Engine
    {
        $this->searchValue = $this->pgArray($characters ?? $this->characters);

        return $this;
    }

    public function getCharactersCount() : int
    {
        return $this->charactersCount;
    }

    public function getCharacters() : string
    {
        return $this->characters;
    }

    public function getWildcardsCount() : int
    {
        return $this->wildcardsCount;
    }

    public function getLimit() : int
    {
        return $this->limit;
    }

    public function getSearchValue() : string
    {
        return $this->searchValue;
    }

    public function pgArray(\Illuminate\Support\Collection $collection) : string
    {
        return '{' . $collection->map(function (Alpha $alpha) {
            return $alpha->isWildcard() ? null : $alpha->getCharacter();
        })->filter()->implode(',') .'}';
    }

    public function countWildcards() : int
    {
        return $this->characters->sum(function (Alpha $alpha) {
            return $alpha->isWildcard();
        });
    }

}
