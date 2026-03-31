<?php

namespace App\Services;

use App\Models\Criteria;
use App\Models\Destinations;

class SawServices
{
    public function calculate()
    {
        $criterias = Criteria::all();
        $destinations = Destinations::with('destinationCriterias')->get();

        $matrix = [];

        // build decision matrix (value of each criteria for each destination)
        foreach ($destinations as $destination) {
            foreach ($criterias as $criteria) {

                $dc = $destination->destinationCriterias
                    ->firstWhere('criteria_id', $criteria->id);

                // default value is 5 (netral) if not set
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
                    $normalized[$destId][$criteria->id] = $max
                        ? $value / $max
                        : 0;
                } else {
                    $normalized[$destId][$criteria->id] = ($value != 0)
                        ? $min / $value
                        : 0;
                }
            }
        }

        // normalized weights
        $totalWeight = $criterias->sum('weight');

        $normalizedWeights = [];

        foreach ($criterias as $criteria) {
            $normalizedWeights[$criteria->id] = $totalWeight
                ? $criteria->weight / $totalWeight
                : 0;
        }

        // calculate scores (sum of normalized value * normalized weight)
        $scores = [];

        foreach ($normalized as $destId => $vals) {

            $total = 0;
            $details = [];

            foreach ($criterias as $criteria) {
                $weight = $normalizedWeights[$criteria->id];

                $total += ($vals[$criteria->id] ?? 0) * $weight;

                $details[$criteria->name] = [
                    'raw' => $matrix[$destId][$criteria->id] ?? 5,
                    'normalized' => $vals[$criteria->id],
                    'weight' => $weight,
                    'score' => $total,
                ];
            }

            // $scores[$destId] = $total;

            $scores[$destId] = [
                'total_score' => $total,
                'details' => $details,
            ];
        }

        // sort scores in descending order
        // arsort($scores);

        $scores = collect($scores)->sortByDesc('total_score')->toArray();

        return $scores;
    }
}