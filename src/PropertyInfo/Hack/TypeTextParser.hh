<?hh

/*
 * (c) Kévin Dunglas <dunglas@gmail.com>
 *
 * This source file is subject to the MIT license that is bundled
 * with this source code in the file LICENSE.
 */

namespace PropertyInfo\PropertyInfo\Hack;

use PropertyInfo\Type;

/**
 * Builds a {@see Type} from a Hack type text.
 *
 * The Hack language has full type hinting (including scalars) and it is available for properties, getter return
 * types, and setter parameter types.
 *
 * Known limitation: when parsing type information we can correctly identify array<int, string> or Vector<stdClass>
 * but we do not (currently) parse recursively in depth so we cannot correctly identify array<int, array<string>>.
 *
 * @author Kévin Dunglas <dunglas@gmail.com>
 * @author Mihai Stancu <stancu.t.mihai@gmail.com>
 */
class TypeTextParser
{
    const TYPE_MAP = [
        'HH\\bool' => 'bool',
        'HH\\int' => 'int',
        'HH\\float' => 'float',
        'HH\\double' => 'float',
        'HH\\string' => 'string',
        'callable' => 'callable',
        'array' => 'array',
    ];

    public function parse(string $typeText): Type
    {
        if (!$typeText) {
            return;
        }

        if ('?' === $info[0]) {
            $typeText = substr($info, 1);
            $null = true;
        }



        if (isset($null)) {
            $types[] = new Type(Type::TYPE_NULL);
        }
    }
}
