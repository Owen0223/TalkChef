<?php

function gcd($a, $b)
{
    return ($b == 0) ? $a : gcd($b, $a % $b);
}



function decimalToFraction($decimal)
{
    $wholenumber = floor($decimal);
    $fraction = $decimal - $wholenumber;

    if (floor($decimal) == $decimal) {
        return $decimal;
    }

    $tolerance = 1.e-2;
    $numerator = 1;
    $denominator = 1;
    $approximation = $numerator / $denominator;

    while (abs($fraction - $approximation) > $tolerance) {
        if ($approximation < $fraction) {
            $numerator++;
        } else {
            $denominator++;
            $numerator = round($fraction * $denominator);
        }

        $approximation = $numerator / $denominator;
    }

    $gcdValue = gcd($numerator, $denominator);
    $numerator /= $gcdValue;
    $denominator /= $gcdValue;

    if ($wholenumber == 0) {
        return "$numerator/$denominator";
    } else {
        return "$wholenumber $numerator/$denominator";
    }
}