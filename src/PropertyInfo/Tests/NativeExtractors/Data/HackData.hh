<?hh
//strict

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\NativeExtractors\Data;

/**
 *    This exemplifies many combinations of typed property definitions with getters and setters. It
  is inspected by Property, Getter and Setter extractors during tests.
 *
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class HackData
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

    private array<int, bool> $boolArrayInt = [];
    private array<int, int> $intArrayInt = [];
    private array<int, float> $floatArrayInt = [];
    private array<int, string> $stringArrayInt = [];
    private array<int, array> $arrayArrayInt = [];
    private array<int, \stdClass> $objectArrayInt = [];

    private ?Vector<bool> $boolVector;
    private ?Vector<int> $intVector;
    private ?Vector<float> $floatVector;
    private ?Vector<string> $stringVector;
    private ?Vector<array> $arrayVector;
    private ?Vector<\stdClass> $objectVector;

    public function __construct()
    {
        $this->object = new \stdClass();
    }


    public function getBool(): bool
    {
        return $this->bool;
    }
    public function setBool(bool $bool)
    {
        $this->bool = $bool;
    }

    public function getInt(): int
    {
        return $this->int;
    }
    public function setInt(int $int)
    {
        $this->int = $int;
    }

    public function getFloat(): float
    {
        return $this->float;
    }
    public function setFloat(float $float)
    {
        $this->float = $float;
    }

    public function getString(): string
    {
        return $this->string;
    }
    public function setString(string $string)
    {
        $this->string = $string;
    }

    public function getArray(): array
    {
        return $this->array;
    }
    public function setArray(array $array)
    {
        $this->array = $array;
    }

    public function getObject(): \stdClass
    {
        return $this->object;
    }
    public function setObject(\stdClass $object)
    {
        $this->object = $object;
    }

    public function getBoolNullable(): ?bool
    {
        return $this->boolNullable;
    }
    public function setBoolNullable(?bool $boolNullable)
    {
        $this->boolNullable = $boolNullable;
    }

    public function getIntNullable(): ?int
    {
        return $this->intNullable;
    }
    public function setIntNullable(?int $intNullable)
    {
        $this->intNullable = $intNullable;
    }

    public function getFloatNullable(): ?float
    {
        return $this->floatNullable;
    }
    public function setFloatNullable(?float $floatNullable)
    {
        $this->floatNullable = $floatNullable;
    }


    public function getStringNullable(): ?string
    {
        return $this->stringNullable;
    }
    public function setStringNullable(?string $stringNullable)
    {
        $this->stringNullable = $stringNullable;
    }

    public function getArrayNullable(): ?array
    {
        return $this->arrayNullable;
    }
    public function setArrayNullable(?array $arrayNullable)
    {
        $this->arrayNullable = $arrayNullable;
    }

    public function getObjectNullable(): ?\stdClass
    {
        return $this->objectNullable;
    }
    public function setObjectNullable(?\stdClass $objectNullable)
    {
        $this->objectNullable = $objectNullable;
    }


    public function getBoolArray(): array<bool>
    {
        return $this->boolArray;
    }
    public function setBoolArray(array<bool> $boolArray)
    {
        $this->boolArray = $boolArray;
    }

    public function getIntArray(): array<int>
    {
        return $this->intArray;
    }
    public function setIntArray(array<int> $intArray)
    {
        $this->intArray = $intArray;
    }

    public function getFloatArray(): array<float>
    {
        return $this->floatArray;
    }
    public function setFloatArray(array<float> $floatArray)
    {
        $this->floatArray = $floatArray;
    }


    public function getStringArray(): array<string>
    {
        return $this->stringArray;
    }
    public function setStringArray(array<string> $stringArray)
    {
        $this->stringArray = $stringArray;
    }

    public function getArrayArray(): array<array>
    {
        return $this->arrayArray;
    }

    public function setArrayArray(array<array> $arrayArray)
    {
        $this->arrayArray = $arrayArray;
    }

    public function getObjectArray(): array<\stdClass>
    {
        return $this->objectArray;
    }
    public function setObjectArray(array<\stdClass> $objectArray)
    {
        $this->objectArray = $objectArray;
    }


    public function getBoolArrayInt(): array<int, bool>
    {
        return $this->boolArrayInt;
    }
    public function setBoolArrayInt(array<int, bool> $boolArrayInt)
    {
        $this->boolArrayInt = $boolArrayInt;
    }

    public function getIntArrayInt(): array<int, int>
    {
        return $this->intArrayInt;
    }
    public function setIntArrayInt(array<int, int> $intArrayInt)
    {
        $this->intArrayInt = $intArrayInt;
    }

    public function getFloatArrayInt(): array<int, float>
    {
        return $this->floatArrayInt;
    }
    public function setFloatArrayInt(array<int, float> $floatArrayInt)
    {
        $this->floatArrayInt = $floatArrayInt;
    }


    public function getStringArrayInt(): array<int, string>
    {
        return $this->stringArrayInt;
    }
    public function setStringArrayInt(array<int, string> $stringArrayInt)
    {
        $this->stringArrayInt = $stringArrayInt;
    }

    public function getArrayArrayInt(): array<int, array>
    {
        return $this->arrayArrayInt;
    }

    public function setArrayArrayInt(array<int, array> $arrayArrayInt)
    {
        $this->arrayArrayInt = $arrayArrayInt;
    }

    public function getObjectArrayInt(): array<int, \stdClass>
    {
        return $this->objectArrayInt;
    }
    public function setObjectArrayInt(array<int, \stdClass> $objectArrayInt)
    {
        $this->objectArrayInt = $objectArrayInt;
    }


    public function getBoolVector(): ?Vector<bool>
    {
        return $this->boolVector;
    }
    public function setBoolVector(?Vector<bool> $boolVector)
    {
        $this->boolVector = $boolVector;
    }

    public function getIntVector(): ?Vector<int>
    {
        return $this->intVector;
    }
    public function setIntVector(?Vector<int> $intVector)
    {
        $this->intVector = $intVector;
    }

    public function getFloatVector(): ?Vector<float>
    {
        return $this->floatVector;
    }
    public function setFloatVector(?Vector<float> $floatVector)
    {
        $this->floatVector = $floatVector;
    }


    public function getStringVector(): ?Vector<string>
    {
        return $this->stringVector;
    }
    public function setStringVector(?Vector<string> $stringVector)
    {
        $this->stringVector = $stringVector;
    }

    public function getArrayVector(): ?Vector<array>
    {
        return $this->arrayVector;
    }
    public function setArrayVector(?Vector<array> $arrayVector)
    {
        $this->arrayVector = $arrayVector;
    }

    public function getObjectVector(): ?Vector<\stdClass>
    {
        return $this->objectVector;
    }
    public function setObjectVector(?Vector<\stdClass> $objectVector)
    {
        $this->objectVector = $objectVector;
    }
}
