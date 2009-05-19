<?php
declare(ENCODING = 'utf-8');
namespace F3\Fluid\Core\Parser;

/*                                                                        *
 * This script is part of the TYPO3 project - inspiring people to share!  *
 *                                                                        *
 * TYPO3 is free software; you can redistribute it and/or modify it under *
 * the terms of the GNU General Public License version 2 as published by  *
 * the Free Software Foundation.                                          *
 *                                                                        *
 * This script is distributed in the hope that it will be useful, but     *
 * WITHOUT ANY WARRANTY; without even the implied warranty of MERCHAN-    *
 * TABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the GNU General      *
 * Public License for more details.                                       *
 *                                                                        */

/**
 * @package Fluid
 * @subpackage Core
 * @version $Id$
 */

/**
 * Stores all information relevant for one parsing pass - that is, the root node,
 * and the current stack of open nodes (nodeStack) and a variable container used for PostParseFacets.
 *
 * @package Fluid
 * @subpackage Core
 * @version $Id$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 * @scope prototype
 * @internal
 */
class ParsingState implements \F3\Fluid\Core\Parser\ParsedTemplateInterface {

	/**
	 * Root node reference
	 * @var \F3\Fluid\Core\Parser\SyntaxTree\RootNode
	 */
	protected $rootNode;

	/**
	 * Array of node references currently open.
	 * @var array
	 */
	protected $nodeStack = array();

	/**
	 * Variable container where ViewHelpers implementing the PostParseFacet can store things in.
	 * @var \F3\Fluid\Core\ViewHelper\VariableContainer
	 */
	protected $variableContainer;

	/**
	 * Injects a variable container. ViewHelpers implementing the PostParse Facet can store information inside this variableContainer.
	 *
	 * @param \F3\Fluid\Core\ViewHelper\VariableContainer $variableContainer
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function injectVariableContainer(\F3\Fluid\Core\ViewHelper\VariableContainer $variableContainer) {
		$this->variableContainer = $variableContainer;
	}

	/**
	 * Set root node of this parsing state
	 *
	 * @param \F3\Fluid\Core\Parser\SyntaxTree\AbstractNode $rootNode
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function setRootNode(\F3\Fluid\Core\Parser\SyntaxTree\AbstractNode $rootNode) {
		$this->rootNode = $rootNode;
	}

	/**
	 * Get root node of this parsing state.
	 *
	 * @return \F3\Fluid\Core\Parser\SyntaxTree\AbstractNode The root node
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function getRootNode() {
		return $this->rootNode;
	}

	/**
	 * Render the parsed template with a variable container and a ViewHelper context
	 *
	 * @param F3\Fluid\Core\ViewHelper\VariableContainer $variableContainer The variable container having the containing the variables which can be used in the template
	 * @param F3\Fluid\Core\ViewHelper\ViewHelperContext $viewHelperContext The ViewHelperContext which carries important configuration for the ViewHelper
	 * @return Rendered string
	 * @internal
	 */
	public function render(\F3\Fluid\Core\ViewHelper\VariableContainer $variableContainer, \F3\Fluid\Core\ViewHelper\ViewHelperContext $viewHelperContext) {
		$this->rootNode->setVariableContainer($variableContainer);
		$this->rootNode->setViewHelperContext($viewHelperContext);
		return $this->rootNode->evaluate();
	}

	/**
	 * Push a node to the node stack. The node stack holds all currently open templating tags.
	 *
	 * @param \F3\Fluid\Core\Parser\SyntaxTree\AbstractNode $node Node to push to node stack
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function pushNodeToStack(\F3\Fluid\Core\Parser\SyntaxTree\AbstractNode $node) {
		array_push($this->nodeStack, $node);
	}

	/**
	 * Get the top stack element, without removing it.
	 *
	 * @return \F3\Fluid\Core\Parser\SyntaxTree\AbstractNode the top stack element.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function getNodeFromStack() {
		return $this->nodeStack[count($this->nodeStack)-1];
	}

	/**
	 * Pop the top stack element (=remove it) and return it back.
	 *
	 * @return \F3\Fluid\Core\Parser\SyntaxTree\AbstractNode the top stack element, which was removed.
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function popNodeFromStack() {
		return array_pop($this->nodeStack);
	}

	/**
	 * Count the size of the node stack
	 *
	 * @return integer Number of elements on the node stack (i.e. number of currently open Fluid tags)
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 */
	public function countNodeStack() {
		return count($this->nodeStack);
	}

	/**
	 * Returns a variable container which will be then passed to the postParseFacet.
	 *
	 * @return \F3\Fluid\Core\ViewHelper\VariableContainer The variable container or NULL if none has been set yet
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @internal
	 * @todo Rename to getPostParseVariableContainer
	 */
	public function getVariableContainer() {
		return $this->variableContainer;
	}
}
?>