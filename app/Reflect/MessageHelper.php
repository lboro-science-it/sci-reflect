<?php

namespace App\Reflect;

class MessageHelper
{
    public static function getCompletionMessage($completion)
    {
        $compliments = [
            'Well done',
            'Great job',
            'Excellent',
            'Awesome',
            'Good work',
            'Great stuff',
            'Good stuff',
        ];
        $compliment = $compliments[array_rand($compliments)];

        if ($completion == 1) {
            return "$compliment, you've reflected on everything this round. ";
        } elseif ($completion >= 0.75) {
            return "$compliment, you're over three quarters of the way there. ";
        } elseif ($completion >= 0.5) {
            return "$compliment, you've passed the half way point. Keep it up! ";
        } elseif ($completion >= 0.25) {
            return "$compliment! You've reflected on over a quarter of the skills in this round. ";
        } elseif ($completion > 0) {
            return "Well done for getting started. Now keep going! ";
        } else {
            $intros = [
                "Feeling reflective? Let's get started. "
            ];
            return $intros[array_rand($intros)];
        }
    }

    public static function getPhilosophicalMessage()
    {
        $philosophies = [
            '“Once we accept our limits, we go beyond them.” ― Albert Einstein',
            '“To realize that you do not understand is a virtue; not to realize that you do not understand is a defect.” ― Lao Tzu'
        ];
    }

    public static function getTimeMessage($closeDate)
    {
        if (isset($closeDate)) {
            $close = strToTime($closeDate);
            $now = strToTime(date('Y-m-d H:i:s'));
            $diffSeconds = $close - $now;
            $diffDays = $diffSeconds / 60 / 60 / 24;

            if ($diffDays < 1) {
                $message = "Less than a day until this round closes! Make sure you save your responses. ";
            } elseif ($diffDays < 3) {
                $message = "Less than three days until the end of this round. Be sure to save your responses. ";
            } elseif ($diffDays < 7) {
                $message = "You have less than a week to finish reflecting. ";
            } elseif ($diffDays >= 7) {
                $message = "There's over a week to go, but don't delay. ";
            }

            return $message;
        }

        return '';
    }

}