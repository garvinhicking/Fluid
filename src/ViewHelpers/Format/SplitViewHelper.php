<?php

declare(strict_types=1);

/*
 * This file belongs to the package "TYPO3 Fluid".
 * See LICENSE.txt that was shipped with this package.
 */

namespace TYPO3Fluid\Fluid\ViewHelpers\Format;

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;
use TYPO3Fluid\Fluid\Core\ViewHelper\AbstractViewHelper;
use TYPO3Fluid\Fluid\Core\ViewHelper\Traits\CompileWithContentArgumentAndRenderStatic;

final class SplitViewHelper extends AbstractViewHelper
{
    use CompileWithContentArgumentAndRenderStatic;

    /**
     * @var bool
     */
    protected $escapeChildren = false;

    public function initializeArguments(): void
    {
        $this->registerArgument('delimiter', 'mixed', 'Delimiter string to explode with');
        $this->registerArgument('value', 'string', 'The string to explode');
        $this->registerArgument('limit', 'int', 'If limit is positive, a maximum of $limit items will be returned. If limit is negative, all items except for the last $limit items will be returned. 0 will be treated as 1.', false, PHP_INT_MAX);
        $this->registerArgument('trim', 'bool', 'Trim items in array', false, false);
        $this->registerArgument('removeEmpty', 'bool', 'Remove empty items from array. Items containing only whitespace are considered empty as well.', false, false);
        $this->registerArgument('intval', 'bool', 'Convert items to integers', false, false);
    }

    public static function renderStatic(array $arguments, \Closure $renderChildrenClosure, RenderingContextInterface $renderingContext): array
    {
        $value = $renderChildrenClosure();
        $result = explode($arguments['delimiter'], $value);

        if ($arguments['trim']) {
            $result = array_map(trim(...), $result);
        }

        if ($arguments['removeEmpty']) {
            $result = array_values(array_filter($result, static fn(string $item): bool => $item !== ''));
        }

        if ($arguments['intval']) {
            $result = array_map(intval(...), $result);
        }

        $limit = $arguments['limit'];
        if ($limit < 0) {
            $result = array_slice($result, 0, $limit);
        } else {
            if ($limit === 0) {
                $limit = 1;
            }
            $tail = array_slice($result, $limit - 1);
            $result = array_slice($result, 0, $limit - 1);
            if ($tail) {
                $result[] = implode($arguments['delimiter'], $tail);
            }
        }

        return $result;
    }

    /**
     * Explicitly set argument name to be used as content.
     */
    public function resolveContentArgumentName(): string
    {
        return 'value';
    }
}
