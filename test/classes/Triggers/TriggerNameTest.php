<?php

declare(strict_types=1);

namespace PhpMyAdmin\Tests\Triggers;

use PhpMyAdmin\Triggers\InvalidTriggerName;
use PhpMyAdmin\Triggers\TriggerName;
use PHPUnit\Framework\TestCase;

use function str_repeat;

/**
 * @covers \PhpMyAdmin\Triggers\InvalidTriggerName
 * @covers \PhpMyAdmin\Triggers\TriggerName
 */
final class TriggerNameTest extends TestCase
{
    /** @dataProvider providerForTestValidNames */
    public function testValidName(string $validName): void
    {
        $name = TriggerName::fromValue($validName);
        $this->assertEquals($validName, $name->getName());
        $this->assertEquals($validName, (string) $name);
    }

    /** @dataProvider providerForTestValidNames */
    public function testTryFromValueValidName(string $validName): void
    {
        $name = TriggerName::tryFromValue($validName);
        $this->assertNotNull($name);
        $this->assertEquals($validName, $name->getName());
        $this->assertEquals($validName, (string) $name);
    }

    /** @return iterable<int, string[]> */
    public static function providerForTestValidNames(): iterable
    {
        yield ['name'];
        yield ['0'];
        yield [str_repeat('a', 64)];
    }

    /** @dataProvider providerForTestInvalidNames */
    public function testInvalidNames(mixed $name, string $exceptionMessage): void
    {
        $this->assertNull(TriggerName::tryFromValue($name));
        $this->expectException(InvalidTriggerName::class);
        $this->expectExceptionMessage($exceptionMessage);
        TriggerName::fromValue($name);
    }

    /**
     * @return iterable<string, mixed[]>
     * @psalm-return iterable<string, array{mixed, non-empty-string}>
     */
    public static function providerForTestInvalidNames(): iterable
    {
        yield 'null' => [null, 'The trigger name must not be empty.'];
        yield 'integer' => [1, 'The trigger name must not be empty.'];
        yield 'array' => [['trigger_name'], 'The trigger name must not be empty.'];
        yield 'empty string' => ['', 'The trigger name must not be empty.'];
        yield 'too long name' => [str_repeat('a', 65), 'The trigger name cannot be longer than 64 characters.'];
        yield 'trailing space' => ['a ', 'The trigger name cannot end with a space character.'];
    }
}
