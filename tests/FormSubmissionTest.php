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
            'gender' => 'f',
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
        $this->assertSame('f', $form->gender);
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

    public function testTryCreateTrimsNumericNameFields(): void
    {
        $body = $this->validBody();
        $body['name'] = 42;

        $form = FormSubmission::tryCreate($body);
        $this->assertNotNull($form);
        $this->assertSame('42', $form->name);
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

    public function testTryCreateAllowsEmptyOptionalStrings(): void
    {
        $body = $this->validBody();
        $body['dob'] = '';
        $body['gender'] = '';
        $body['card1Name'] = '';
        $body['card2Name'] = '';
        $body['card3Name'] = '';

        $form = FormSubmission::tryCreate($body);
        $this->assertNotNull($form);
        $this->assertSame('', $form->dob);
        $this->assertSame('', $form->gender);
    }
}
