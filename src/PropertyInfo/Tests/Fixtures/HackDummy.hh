<?hh
//strict

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\Fixtures;

/**
 *    This exemplifies many combinations of typed property definitions with getters and setters. It
  is inspected by Property, Getter and Setter extractors during tests.
 *
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class HackDummy
{
    private bool $bool = true;
    private int $int = 0;
    private float $float = 0.0;
    private string $string = '';
    private array $array = [];
    private \stdClass $object;

    private ?bool $boolNullable;
    private ?int $intNullable;
    private ?float $floatNullable;
    private ?string $stringNullable;
    private ?array $arrayNullable;
    private ?\stdClass $objectNullable;

    private array<bool> $boolArray = [];
    private array<int> $intArray = [];
    private array<float> $floatArray = [];
    private array<string> $stringArray = [];
    private array<array> $arrayArray = [];
    private array<\stdClass> $objectArray = [];

    private array<string, bool> $boolArrayString = [];
    private array<string, int> $intArrayString = [];
    private array<string, float> $floatArrayString = [];
    private array<string, string> $stringArrayString = [];
    private array<string, array> $arrayArrayString = [];
    private array<string, \stdClass> $objectArrayString = [];

    private ?Vector<bool> $boolNullableVector;
    private ?Vector<int> $intNullableVector;
    private ?Vector<float> $floatNullableVector;
    private ?Vector<string> $stringNullableVector;
    private ?Vector<array> $arrayNullableVector;
    private ?Vector<\stdClass> $objectNullableVector;

    public function __construct()
    {
        $this->object = new \stdClass();
    }
}
