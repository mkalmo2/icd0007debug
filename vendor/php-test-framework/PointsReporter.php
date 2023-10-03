<?php

namespace stf;

class PointsReporter implements ResultReporter {

    private array $scale;
    private string $format;

    public function __construct(array $scale, string $format) {
        $this->scale = $scale;
        $this->format = $format;
    }

    public function execute(int $passedMethodCount): string {
        $finalPoints = 0;

        foreach ($this->scale as $threshold => $points) {
            if ($passedMethodCount >= $threshold) {
                $finalPoints = $points;
            }
        }

        return sprintf($this->format, $finalPoints, $this->getMaxPoints());
    }

    private function getMaxPoints(): int {
        $maxPoints = 0;

        foreach ($this->scale as $threshold => $points) {
            if ($points >= $maxPoints) {
                $maxPoints = $points;
            }
        }

        return $maxPoints;
    }
}

