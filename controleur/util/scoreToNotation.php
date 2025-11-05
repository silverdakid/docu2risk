<?php

function scoreToNotation(int $note, string $balise)
{
    if ($note > 29) {
        $class = "redColor";
        $notation = "HIGH";
    } else if ($note > 19) {
        $class = "orange";
        $notation = "MEDIUM";
    } else {
        $class = "green";
        $notation = "LOW";
    }

    return "<$balise class='$class'>$notation</$balise>";
}
