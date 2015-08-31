<?hh

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\Tests\NativeExtractors\DataProviders;

use PropertyInfo\Extractors\PropertyExtractor;
use PropertyInfo\Extractors\SetterExtractor;
use PropertyInfo\Extractors\GetterExtractor;
use PropertyInfo\Tests\NativeExtractors\Data\HackData;

/**
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class HackDataProvider
{
    /**
     * @return array
     */
    public function extractorsDataProvider()
    {
        $properties = [
            ['name' => 'bool', 'type' => 'bool', 'class' => null],
            ['name' => 'int', 'type' => 'int', 'class' => null],
            ['name' => 'float', 'type' => 'float', 'class' => null],
            ['name' => 'string', 'type' => 'string', 'class' => null],
            ['name' => 'array', 'type' => 'array', 'class' => null],
            ['name' => 'object', 'type' => 'object', 'class' => 'stdClass'],
        ];

        $extra = $properties;
        foreach ($properties as $property) {
            $property['name'] = $property['name'] . 'Nullable';
            $extra[] = $property;
        }
        foreach ($properties as $property) {
            $property['name'] = $property['name'] . 'Array';
            $property['collection'] = true;
            $property['collectionType']['type'] = $property['type'];
            $property['collectionType']['class'] = $property['class'];
            $property['type'] = 'array';
            $property['class'] = null;
            $extra[] = $property;
        }
        foreach ($properties as $property) {
            $property['name'] = $property['name'] . 'ArrayInt';
            $property['collection'] = true;
            $property['collectionType']['type'] = $property['type'];
            $property['collectionType']['class'] = $property['class'];
            $property['type'] = 'array';
            $property['class'] = null;
            $extra[] = $property;
        }
        foreach ($properties as $property) {
            $property['name'] = $property['name'] . 'Vector';
            $property['collection'] = true;
            $property['collectionType']['type'] = $property['type'];
            $property['collectionType']['class'] = $property['class'];
            $property['class'] = 'HH\\Vector';
            $property['type'] = 'object';
            $extra[] = $property;
        }
        $properties = $extra;

        $cases = [
            [HackData::class, PropertyExtractor::class, $properties],
            [HackData::class, GetterExtractor::class, $properties],
            [HackData::class, SetterExtractor::class, $properties],
        ];

        return $cases;
    }
}
