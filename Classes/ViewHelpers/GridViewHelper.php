<?php
namespace FluidTYPO3\Flux\ViewHelpers;

/*
 * This file is part of the FluidTYPO3/Flux project under GPLv2 or later.
 *
 * For the full copyright and license information, please read the
 * LICENSE.md file that was distributed with this source code.
 */

use TYPO3Fluid\Fluid\Core\Rendering\RenderingContextInterface;

/**
 * Grid container ViewHelper.
 *
 * Use `<flux:grid.row>` with nested `<flux:grid.column>` tags
 * to define a tabular layout.
 *
 * The grid is then rendered automatically in the preview section
 * of the content element, or as page columns if used in page templates.
 *
 * For frontend rendering, use `flux:content.render`.
 *
 * ### Define Page and Content elements
 *
 * Name is used to identify columns and fetch e.g. translations from XLF files.
 *
 *     <flux:grid>
 *         <flux:grid.row>
 *             <flux:grid.column colPos="0" name="Main" colspan="3" style="width: 75%" />
 *             <flux:grid.column colPos="1" name="Secondary" colspan="1" style="width: 25%" />
 *         </flux:grid.row>
 *     </flux:grid>
 *
 * #### Rendering
 *
 *     <v:content.render column="0" />
 *     <v:content.render column="1" />
 */
class GridViewHelper extends AbstractFormViewHelper
{

    /**
     * Initialize
     * @return void
     */
    public function initializeArguments()
    {
        $this->registerArgument('name', 'string', 'Optional name of this grid - defaults to "grid"', false, 'grid');
        $this->registerArgument(
            'label',
            'string',
            'Optional label for this grid - defaults to an LLL value (reported if it is missing)'
        );
        $this->registerArgument(
            'variables',
            'array',
            'Freestyle variables which become assigned to the resulting Component - can then be read from that ' .
            'Component outside this Fluid template and in other templates using the Form object from this template',
            false,
            []
        );
        $this->registerArgument(
            'extensionName',
            'string',
            'If provided, enables overriding the extension context for this and all child nodes. The extension name ' .
            'is otherwise automatically detected from rendering context.'
        );
    }

    /**
     * @param array $arguments
     * @param \Closure $renderChildrenClosure
     * @param RenderingContextInterface $renderingContext
     * @return void
     */
    public static function renderStatic(
        array $arguments,
        \Closure $renderChildrenClosure,
        RenderingContextInterface $renderingContext
    ) {
        $container = static::getContainerFromRenderingContext($renderingContext);
        $grid = static::getGridFromRenderingContext($renderingContext, $arguments['name']);
        $grid->setLabel($arguments['label']);
        $grid->setVariables($arguments['variables']);
        $grid->setExtensionName(static::getExtensionNameFromRenderingContextOrArguments($renderingContext, $arguments));
        static::setContainerInRenderingContext($renderingContext, $grid);
        $renderChildrenClosure();
        static::setContainerInRenderingContext($renderingContext, $container);
    }
}
