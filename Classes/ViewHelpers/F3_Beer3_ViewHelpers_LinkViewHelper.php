<?php
declare(ENCODING = 'utf-8');
namespace F3::Beer3::ViewHelpers;

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
 * @package Beer3
 * @subpackage ViewHelpers
 * @version $Id:$
 */
/**
 * Link-generation view helper
 *
 * @package Beer3
 * @subpackage ViewHelpers
 * @version $Id:$
 * @license http://opensource.org/licenses/gpl-license.php GNU Public License, version 2
 * @scope prototype
 */
class LinkViewHelper extends F3::Beer3::Core::AbstractViewHelper {
	
	/**
	 * Initialize arguments
	 * 
	 * @return void
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 * @todo Implement support for controller and package arguments
	 * @todo let it inherit from TagBasedViewHelper
	 */
	public function initializeArguments() {
		$this->registerArgument('action', 'string', 'Name of action where the link points to', TRUE);
		$this->registerArgument('arguments', 'array', 'Associative array of all URL arguments which should be appended.');
	}
	
	/**
	 * Render the link.
	 * 
	 * @return string The rendered link
	 * @author Sebastian Kurfürst <sebastian@typo3.org>
	 */
	public function render() {
		$uriHelper = $this->variableContainer->get('view')->getViewHelper('F3::FLOW3::MVC::View::Helper::URIHelper');
		return $uriHelper->linkTo($this->renderChildren(), $this->arguments['action'], $this->arguments['arguments']);
	}
}


?>