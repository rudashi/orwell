<?php

namespace App\Http\Controllers;

use App\Repositories\WordRepository;

class WordsController extends Controller
{

    private $repository;

    public function __construct(WordRepository $repository)
    {
        $this->repository = $repository;
    }

    public function search(string $letters) : \Illuminate\Http\JsonResponse
    {
        try {
            $letters = $this->repository->prepareInputSearch($letters);
            $collection = $this->repository->anagram($letters, 25);

            return $this->response(
                collect([
                    'search' => $letters,
                    'words' => $collection
                ])
            );

        } catch (\Exception $e) {
            return $this->responseException($e);
        }

    }

    public function allWords(string $letters) : \Illuminate\Http\JsonResponse
    {
        try {
            $letters = $this->repository->prepareInputSearch($letters);
            $collection = $this->repository->anagram($letters);

            return response()->json($collection);

        } catch (\Exception $e) {
            return $this->responseException($e);
        }

    }

}
