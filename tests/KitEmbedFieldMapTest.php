<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\KitEmbedFieldMap;
use PHPUnit\Framework\TestCase;

final class KitEmbedFieldMapTest extends TestCase
{
    public function testFromSubscriberMapsEmbedInputNames(): void
    {
        $subscriber = [
            'name' => 'Jane',
            'email' => 'jane@example.com',
            'dob' => '03/25/1990',
            'gender' => 'Female',
            'card1Name' => 'The Fool',
            'card2Name' => 'The Magician',
            'card3Name' => 'The High Priestess',
            'loveReading' => 'Love text',
            'lifeReading' => 'Life text',
            'wealthReading' => 'Wealth text',
            'loveCardImage' => 'https://www.trustedtarot.com/img/cards/the-fool.png',
            'lifeCardImage' => 'https://www.trustedtarot.com/img/cards/the-magician.png',
            'wealthCardImage' => 'https://www.trustedtarot.com/img/cards/the-high-priestess.png',
            'sunSign' => 'Aries',
            'sunPersonalLife' => 'Personal',
            'sunProfession' => 'Profession',
            'sunHealth' => 'Health',
            'sunEmotions' => 'Emotions',
            'sunTravel' => 'Travel',
            'sunLuck' => 'Luck',
        ];

        $fields = KitEmbedFieldMap::fromSubscriber($subscriber);

        $this->assertSame('jane@example.com', $fields['email_address']);
        $this->assertSame('Jane', $fields['fields[first_name]']);
        $this->assertSame('25-Mar-1990', $fields['fields[date_of_birth]']);
        $this->assertSame('Female', $fields['fields[gender]']);
        $this->assertSame('The Fool', $fields['fields[love_card]']);
        $this->assertSame('The Magician', $fields['fields[life_card]']);
        $this->assertSame('The High Priestess', $fields['fields[wealth_card]']);
        $this->assertSame('Love text', $fields['fields[love_reading]']);
        $this->assertSame('Life text', $fields['fields[life_reading]']);
        $this->assertSame('Wealth text', $fields['fields[wealth_reading]']);
        $this->assertSame(
            'https://www.trustedtarot.com/img/cards/the-fool.png',
            $fields['fields[love_card_image]'],
        );
        $this->assertSame('Aries', $fields['fields[sun_sign]']);
        $this->assertSame('Luck', $fields['fields[sun_luck]']);
    }
}
