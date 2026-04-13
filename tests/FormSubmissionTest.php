<?php

declare(strict_types=1);

namespace App\Tests;

use App\Domain\FormSubmission;
use PHPUnit\Framework\TestCase;

final class FormSubmissionTest extends TestCase
{
    /**
     * @return array<string, mixed>
     */
    private function validBody(): array
    {
        return [
            'name' => '  Jane  ',
            'email' => 'jane@example.com',
            'dob' => '03/25/1990',
            'gender' => 'Female',
            'card1' => 1,
            'card2' => 2,
            'card3' => 3,
            'card1Name' => 'The Fool',
            'card2Name' => 'The Magician',
            'card3Name' => 'The High Priestess',
        ];
    }

    public function testTryCreateTrimsStringsAndParsesCards(): void
    {
        $form = FormSubmission::tryCreate($this->validBody());
        $this->assertNotNull($form);
        $this->assertSame('Jane', $form->name);
        $this->assertSame('jane@example.com', $form->email);
        $this->assertSame('03/25/1990', $form->dob);
        $this->assertSame('Female', $form->gender);
        $this->assertSame(1, $form->card1);
        $this->assertSame(2, $form->card2);
        $this->assertSame(3, $form->card3);
        $this->assertSame('The Fool', $form->card1Name);
        $this->assertSame('The Magician', $form->card2Name);
        $this->assertSame('The High Priestess', $form->card3Name);
    }

    public function testTryCreateAcceptsNumericStringsForCardIds(): void
    {
        $body = $this->validBody();
        $body['card1'] = '10';
        $body['card2'] = '20';
        $body['card3'] = '30';

        $form = FormSubmission::tryCreate($body);
        $this->assertNotNull($form);
        $this->assertSame(10, $form->card1);
        $this->assertSame(20, $form->card2);
        $this->assertSame(30, $form->card3);
    }

    public function testTryCreateRejectsNumericName(): void
    {
        $body = $this->validBody();
        $body['name'] = 42;

        $this->assertNull(FormSubmission::tryCreate($body));
    }

    public function testTryCreateReturnsNullWhenNameMissing(): void
    {
        $body = $this->validBody();
        unset($body['name']);

        $this->assertNull(FormSubmission::tryCreate($body));
    }

    public function testTryCreateReturnsNullWhenNameEmpty(): void
    {
        $body = $this->validBody();
        $body['name'] = '   ';

        $this->assertNull(FormSubmission::tryCreate($body));
    }

    public function testTryCreateReturnsNullWhenEmailMissing(): void
    {
        $body = $this->validBody();
        unset($body['email']);

        $this->assertNull(FormSubmission::tryCreate($body));
    }

    public function testTryCreateReturnsNullWhenEmailInvalid(): void
    {
        $body = $this->validBody();
        $body['email'] = 'not-an-email';

        $this->assertNull(FormSubmission::tryCreate($body));
    }

    public function testTryCreateReturnsNullWhenNameTooLong(): void
    {
        $body = $this->validBody();
        $body['name'] = str_repeat('a', 121);

        $this->assertNull(FormSubmission::tryCreate($body));
    }

    public function testTryCreateReturnsNullWhenAnyCardMissing(): void
    {
        $base = $this->validBody();

        $missingCard1 = $base;
        unset($missingCard1['card1']);
        $this->assertNull(FormSubmission::tryCreate($missingCard1));

        $missingCard2 = $base;
        unset($missingCard2['card2']);
        $this->assertNull(FormSubmission::tryCreate($missingCard2));

        $missingCard3 = $base;
        unset($missingCard3['card3']);
        $this->assertNull(FormSubmission::tryCreate($missingCard3));
    }

    public function testTryCreateReturnsNullWhenDobInvalid(): void
    {
        $body = $this->validBody();
        $body['dob'] = '13/25/1990';

        $this->assertNull(FormSubmission::tryCreate($body));
    }

    public function testTryCreateReturnsNullWhenGenderUnsupported(): void
    {
        $body = $this->validBody();
        $body['gender'] = 'Other';

        $this->assertNull(FormSubmission::tryCreate($body));
    }

    public function testTryCreateReturnsNullWhenCardOutOfRange(): void
    {
        $body = $this->validBody();
        $body['card1'] = 0;

        $this->assertNull(FormSubmission::tryCreate($body));
    }

    public function testTryCreateReturnsNullWhenCardNotNumeric(): void
    {
        $body = $this->validBody();
        $body['card2'] = 'abc';

        $this->assertNull(FormSubmission::tryCreate($body));
    }
}
