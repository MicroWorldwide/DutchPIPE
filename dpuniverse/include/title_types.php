<?php
/**
 * Constants for the title types system
 *
 * DutchPIPE version 0.2; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_include
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: title_types.php 238 2007-07-08 15:40:07Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpObject::setTitle(), DpObject::getTitle(),
 *             DpObject::setTitleType(), DpObject::getTitleType()
 */

/**
 * The title is indefinite, "a beer"
 *
 * This is the default title type for objects.
 *
 * @see        DPUNIVERSE_TITLE_TYPE_DEFINITE, DPUNIVERSE_TITLE_TYPE_NAME,
 *             DpObject::setTitle(), DpObject::getTitle(),
 *             DpObject::setTitleType(), DpObject::getTitleType()
 */
define('DPUNIVERSE_TITLE_TYPE_INDEFINITE', 1);

/**
 * The title is definite, "the hulk"
 *
 * @see        DPUNIVERSE_TITLE_TYPE_INDEFINITE, DPUNIVERSE_TITLE_TYPE_NAME,
 *             DpObject::setTitle(), DpObject::getTitle(),
 *             DpObject::setTitleType(), DpObject::getTitleType()
 */
define('DPUNIVERSE_TITLE_TYPE_DEFINITE', 2);

/**
 * The title is a name, "Lennert"
 *
 * @see        DPUNIVERSE_TITLE_TYPE_INDEFINITE, DPUNIVERSE_TITLE_TYPE_DEFINITE,
 *             DpObject::setTitle(), DpObject::getTitle(),
 *             DpObject::setTitleType(), DpObject::getTitleType()
 */
define('DPUNIVERSE_TITLE_TYPE_NAME', 3);

/**
 * The title is plural, "sweets"
 *
 * Not used yet.
 *
 * @ignore
 * @see        DPUNIVERSE_TITLE_TYPE_INDEFINITE, DPUNIVERSE_TITLE_TYPE_DEFINITE,
 *             DPUNIVERSE_TITLE_TYPE_NAME, DpObject::setTitle(),
 *             DpObject::getTitle(), DpObject::setTitleType(),
 *             DpObject::getTitleType()
 */
define('DPUNIVERSE_TITLE_TYPE_PLURAL', 4);
?>
