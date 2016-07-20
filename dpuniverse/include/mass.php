<?php
/**
 * Constants for the mass system (weight & volume for objects)
 *
 * DutchPIPE version 0.1; PHP version 5
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
 * @version    Subversion: $Id$
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */

/**
 * Weight type - no weight system
 *
 * Used by {@link WEIGHT_TYPE}. Don't change this.
 *
 * @see        WEIGHT_TYPE, WEIGHT_TYPE_ABSTRACT, WEIGHT_TYPE_METRIC,
 *             WEIGHT_TYPE_USA
 */
define('WEIGHT_TYPE_NONE', 'none');

/**
 * Weight type modifier - abstract system in units (1, 2, 3, ...)
 *
 * Used by {@link WEIGHT_TYPE}. Don't change this.
 *
 * @see        WEIGHT_TYPE, WEIGHT_TYPE_NONE, WEIGHT_TYPE_METRIC,
 *             WEIGHT_TYPE_USA
 */
define('WEIGHT_TYPE_ABSTRACT', 'abstract');

/**
 * Weight type modifier - system in grams
 *
 * Used by {@link WEIGHT_TYPE}. Don't change this.
 *
 * @see        WEIGHT_TYPE, WEIGHT_TYPE_NONE, WEIGHT_TYPE_ABSTRACT,
 *             WEIGHT_TYPE_USA
 */
define('WEIGHT_TYPE_METRIC', 'metric');

/**
 * Weight type modifier - system in ounces
 *
 * Used by {@link WEIGHT_TYPE}. Don't change this.
 *
 * @see        WEIGHT_TYPE, WEIGHT_TYPE_NONE, WEIGHT_TYPE_ABSTRACT,
 *             WEIGHT_TYPE_METRIC
 */
define('WEIGHT_TYPE_USA', 'usa');

/**
 * Weight type
 *
 * @see        WEIGHT_TYPE, WEIGHT_TYPE_NONE, WEIGHT_TYPE_METRIC
 */
define('WEIGHT_TYPE', WEIGHT_TYPE_ABSTRACT);

/**
 * Volume type - no volume system
 *
 * Used by {@link VOLUME_TYPE}. Don't change this.
 *
 * @see        VOLUME_TYPE, VOLUME_TYPE_ABSTRACT, VOLUME_TYPE_METRIC,
 *             VOLUME_TYPE_USA
 */
define('VOLUME_TYPE_NONE', 'none');

/**
 * Volume type modifier - abstract system in units (1, 2, 3, ...)
 *
 * Used by {@link VOLUME_TYPE}. Don't change this.
 *
 * @see        VOLUME_TYPE, VOLUME_TYPE_NONE, VOLUME_TYPE_METRIC,
 *             VOLUME_TYPE_USA
 */
define('VOLUME_TYPE_ABSTRACT', 'abstract');

/**
 * Volume type modifier - system in cm2
 *
 * Used by {@link VOLUME_TYPE}. Don't change this.
 *
 * @see        VOLUME_TYPE, VOLUME_TYPE_NONE, VOLUME_TYPE_ABSTRACT,
 *             VOLUME_TYPE_USA
 */
define('VOLUME_TYPE_METRIC', 'metric');

/**
 * Volume type modifier - system in ounces
 *
 * Used by {@link VOLUME_TYPE}. Don't change this.
 *
 * @see        VOLUME_TYPE, VOLUME_TYPE_NONE, VOLUME_TYPE_ABSTRACT,
 *             VOLUME_TYPE_METRIC
 */
define('VOLUME_TYPE_USA', 'usa');

/**
 * Volume type
 *
 * @see        VOLUME_TYPE, VOLUME_TYPE_NONE, VOLUME_TYPE_METRIC
 */
define('VOLUME_TYPE', VOLUME_TYPE_ABSTRACT);
?>