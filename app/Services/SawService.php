<?php

namespace App\Services;

use App\Models\Criteria;
use App\Models\Destinations;
use App\Models\Rankings;

class SawService
{
    public function calculate()
    {
        $criterias = Criteria::all();
        $destinations = Destinations::with('destinationCriterias')->get();

        $matrix = [];

        // build decision matrix (nilai raw tiap criteria per destination)
        foreach ($destinations as $destination) {
            foreach ($criterias as $criteria) {
                $dc = $destination->destinationCriterias
                    ->firstWhere('criteria_id', $criteria->id);
                $matrix[$destination->id][$criteria->id] = $dc->value ?? 5;
            }
        }

        // normalization (benefit: value / max, cost: min / value)
        $normalized = [];
        foreach ($criterias as $criteria) {
            $values = collect($matrix)->pluck($criteria->id);
            $max = $values->max();
            $min = $values->min();

            foreach ($matrix as $destId => $vals) {
                $value = $vals[$criteria->id];
                if ($criteria->type === 'benefit') {
                    $normalized[$destId][$criteria->id] = $max ? $value / $max : 0;
                } else {
                    $normalized[$destId][$criteria->id] = ($value != 0) ? $min / $value : 0;
                }
            }
        }

        // normalize weights
        $totalWeight = $criterias->sum('weight');
        $normalizedWeights = [];
        foreach ($criterias as $criteria) {
            $normalizedWeights[$criteria->id] = $totalWeight ? $criteria->weight / $totalWeight : 0;
        }

        // calculate scores per criteria & total score
        $scores = [];
        foreach ($normalized as $destId => $vals) {
            $details = [];
            foreach ($criterias as $criteria) {
                $weight = $normalizedWeights[$criteria->id];
                $details[$criteria->name] = [
                    'raw' => $matrix[$destId][$criteria->id] ?? 5,
                    'normalized' => round($vals[$criteria->id], 5),
                    'weight' => round($weight, 5),
                    'score' => round(($vals[$criteria->id] * $weight), 5), // per criteria
                ];
            }

            $totalScore = collect($details)->sum(fn($d) => $d['score']);
            $scores[$destId] = [
                'total_score' => round($totalScore, 5),
                'details' => $details,
            ];
        }

        // urutkan descending by total score
        $scores = collect($scores)->sortByDesc('total_score')->values()->all();

        // simpan ke database dengan rank
        foreach ($scores as $index => $score) {
            Rankings::updateOrCreate(
                ['destination_id' => $destinations[$index]->id],
                [
                    'score' => $score['total_score'],
                    'details' => $score['details'],
                    'rank' => $index + 1,
                ]
            );
        }

        return $scores;
    }
}