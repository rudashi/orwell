<?php

namespace App\Repositories\Contracts;

interface WordInterface
{

    public function anagram(string $letters) : \Illuminate\Support\Collection;

    public function prepareInputSearch(string $input) : string;

}