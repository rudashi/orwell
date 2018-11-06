<?php

namespace App\Models;

class Alpha
{

    protected $character;

    protected $points;

    public function __construct(string $character)
    {
        $this->character = $character;
    }

    public function getCharacter() : string
    {
        return $this->character;
    }

    public function getPoints() : string
    {
        return $this->points;
    }

    public function isWildcard() : bool
    {
        return ($this->getCharacter() === '*');
    }

}
