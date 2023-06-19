<?php

namespace App\Services;

use App\Repository\CitationRepository;

class TheBestOne
{
    public function __construct(
        private CitationRepository $repository,
    ){}
    public function allOfFame(){
        $citations = $this->repository->findAll();
        $arrayLength = [];
        $final = [];
        foreach ($citations as $citation){
            $arrayLength[] = [
                'ammout' => count($citation->getUtilisateur()),
                'id' => $citation->getId()
            ];
        }
        arsort($arrayLength);
        $arrayLength = array_slice($arrayLength, 0, 3);
        for ($i = 0; $i < 3; $i++) {
            $quote = $this->repository->findById($arrayLength[$i]['id']);
            array_push($final, $quote);
        }
        return $final;
    }
}