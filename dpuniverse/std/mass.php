<?php
/**
 * Coinherit for adding mass (volume and weight) abstraction to an object
 *
 * DutchPIPE version 0.3; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: mass.php 252 2007-08-02 23:30:58Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */

/**
 * The DutchPIPE property and coinherit system which all objects extend on
 */
inherit(DPUNIVERSE_STD_PATH . 'DpProperties.php');

/**
 * Gets mass constants
 */
inherit(DPUNIVERSE_INCLUDE_PATH . 'mass.php');

/**
 * Coinherit for adding mass (volume and weight) abstraction to an object
 *
 * Creates the following DutchPIPE properties, depending on settings:<br />
 *
 * - int|float <b>weight</b> - Weight of object without inventory
 * - int|float <b>totalWeight</b> - Weight including inventory, cannot be set
 * - int|float <b>volume</b> - Volume of object
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.1
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */
class Mass extends DpProperties
{
   /**
     * Mother object which is using this coinherit object
     *
     * @var        object
     * @access     private
     */
    private $mrMotherObject;

    /**
     * Directly set the value of an existing DutchPIPE property
     *
     * @param      object    &$motherObject  Mother object which is using this coinherit object
     * @access     private
     */
    final function __construct(&$motherObject)
    {
        $this->mrMotherObject = $motherObject;

        if (WEIGHT_TYPE_NONE !== WEIGHT_TYPE) {
            $this->weight = new_dp_property(0, '_setWeight');
            $this->totalWeight = new_dp_property(0, NULL, '_getTotalWeight');
        }

        if (VOLUME_TYPE_NONE !== VOLUME_TYPE) {
            $this->volume = new_dp_property(0, '_setVolume');
        }
    }

    /**
     * Sets weight using member overloading if giving value if a valid weight
     *
     * @param      int|float $weight    Weight of this object
     * @access     private
     */
    protected function _setWeight($val)
    {
        if ((is_integer($val) || is_float($val)) && $val >= 0) {
            $this->setDpProperty('weight', $val);
        }
    }

    /**
     * Sets volume using member overloading if giving value if a valid weight
     *
     * @param      int|float $weight    Volume of this object
     * @access     private
     */
    protected function _setVolume($val)
    {
        if ((is_integer($val) || is_float($val)) && $val >= 0) {
            $this->setDpProperty('volume', $val);
        }
    }

    /**
     * Gets total weight - weight of object including weight of inventory
     *
     * @return     int|float Weight including inventory
     * @access     private
     */
    protected function _getTotalWeight()
    {
        /* Calculates our own weight plus everything we carry/contain */
        $weight = $this->getDpProperty('weight');

        $inv = $this->mrMotherObject->getInventory();
        foreach ($inv as &$ob) {
            $tmp = $ob->weight;
            if (is_int($tmp)) {
                $weight += $tmp;
            }
        }

        return $weight;
    }
}
?>

