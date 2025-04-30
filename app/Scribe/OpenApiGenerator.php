<?php

declare(strict_types=1);

namespace App\Scribe;

use Knuckles\Scribe\Writing\OpenApiSpecGenerators\OpenApiGenerator as BaseOpenApiGenerator;

/**
 * You can add your application additional Open API spec here.
 */
class OpenApiGenerator extends BaseOpenApiGenerator
{
    /**
     * Override the OpenAPI spec generation to merge in config overrides.
     */
    public function root(array $root, array $groupedEndpoints): array
    {
        $root = $this->appendSecurityPolicies($root);

        return $root;
    }

    protected function appendSecurityPolicies(array $root): array
    {
        // Example of policies
        $policies = [
            // 'components' => [
            //     'securitySchemas' => [
            //         'xsrfToken' => [
            //             'type' => 'apiKey',
            //             'name' => 'X-XSRF-TOKEN',
            //             'in' => 'header',
            //             'description' => 'XSRF token retrieved from cookie',
            //         ],
            //     ],
            // ],
            // 'security' => [
            //     ['xsrfToken' => []],
            // ],
        ];

        return static::mergeRecursiveDistinct($root, $policies);
    }

    /**
     * Recursively merge two arrays, with the second array overriding the first.
     */
    protected static function mergeRecursiveDistinct(array $array1, array $array2): array
    {
        foreach ($array2 as $key => $value) {
            if (is_array($value) && isset($array1[$key]) && is_array($array1[$key])) {
                $array1[$key] = self::mergeRecursiveDistinct($array1[$key], $value);
            } else {
                $array1[$key] = $value;
            }
        }

        return $array1;
    }
}
