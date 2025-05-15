<?php

/**
 * Generate a consistent color from text input
 * 
 * @param string $text
 * @return string HSL color
 */
function colorFromText($text) {
    $hash = 0;
    for ($i = 0; $i < strlen($text); $i++) {
        $hash = ord($text[$i]) + (($hash << 5) - $hash);
    }
    $hue = $hash % 360;
    return "hsl({$hue}, 70%, 80%)";
} 