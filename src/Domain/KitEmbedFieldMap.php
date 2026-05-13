<?php

declare(strict_types=1);

namespace App\Domain;

/**
 * Maps Kit subscriber data to hidden embed form input names.
 */
final class KitEmbedFieldMap
{
    /**
     * @param array{
     *   name: string,
     *   email: string,
     *   dob: string,
     *   gender: string,
     *   card1Name: string,
     *   card2Name: string,
     *   card3Name: string,
     *   loveReading: string,
     *   lifeReading: string,
     *   wealthReading: string,
     *   loveCardImage: string,
     *   lifeCardImage: string,
     *   wealthCardImage: string,
     *   sunSign: string,
     *   sunPersonalLife: string,
     *   sunProfession: string,
     *   sunHealth: string,
     *   sunEmotions: string,
     *   sunTravel: string,
     *   sunLuck: string,
     * } $subscriber
     *
     * @return array<string, string>
     */
    public static function fromSubscriber(array $subscriber): array
    {
        return [
            'email_address' => $subscriber['email'],
            'fields[first_name]' => $subscriber['name'],
            'fields[date_of_birth]' => KitEmbedDobFormatter::apiToKitEmbed($subscriber['dob']),
            'fields[gender]' => $subscriber['gender'],
            'fields[love_card]' => $subscriber['card1Name'],
            'fields[life_card]' => $subscriber['card2Name'],
            'fields[wealth_card]' => $subscriber['card3Name'],
            'fields[love_reading]' => $subscriber['loveReading'],
            'fields[life_reading]' => $subscriber['lifeReading'],
            'fields[wealth_reading]' => $subscriber['wealthReading'],
            'fields[love_card_image]' => $subscriber['loveCardImage'],
            'fields[life_card_image]' => $subscriber['lifeCardImage'],
            'fields[wealth_card_image]' => $subscriber['wealthCardImage'],
            'fields[sun_sign]' => $subscriber['sunSign'],
            'fields[sun_personal_life]' => $subscriber['sunPersonalLife'],
            'fields[sun_profession]' => $subscriber['sunProfession'],
            'fields[sun_health]' => $subscriber['sunHealth'],
            'fields[sun_emotions]' => $subscriber['sunEmotions'],
            'fields[sun_travel]' => $subscriber['sunTravel'],
            'fields[sun_luck]' => $subscriber['sunLuck'],
        ];
    }
}
