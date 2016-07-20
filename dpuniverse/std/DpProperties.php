<?php
/**
 * The DutchPIPE property and coinherit system which all objects extend on
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
 * @copyright  2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: DpProperties.php 252 2007-08-02 23:30:58Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */

/**
 * The DutchPIPE property and coinherit system which all objects extend on
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.1
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */
class DpProperties
{
    /**
     * Generic property array for dynamic, simple vars
     *
     * @var         type
     * @access      private
     */
    private $mProperties = array();

    /**
     * Coinherited objects (for simulated coinherits)
     */
    private $mCoinherits;

    /**
     * Sets the value of a DutchPIPE property using PHP member overloading
     *
     * @access     private
     * @param      string    $nm        name of property
     * @param      string    $val       value of property
     */
    public function __set($nm, $val)
    {
        //echo "__set($nm, $val) called... ";
        /*
         * If a special array is given as constructed by the new_dp_property()
         * method, initializes a new DutchPIPE property
         */
        if (is_array($val) && 4 === count($val)
                && 'new_dp_property' === $val[0]) {
            //echo "Initialized new property $nm.\n";

            if (!isset($this->mProperties[$nm]) &&
                    !is_null($this->mCoinherits)) {
                foreach ($this->mCoinherits as $coinherit) {
                    if (isset($coinherit->{$nm})) {
                        $coinherit->{'set' . ucfirst($nm)}($val);
                        return;
                    }
                }
            }

            $this->mProperties[$nm] = array($val[1], $val[2], $val[3]);
            return;
        }

        /*
         * If $nm is 'foo' and the method 'setFoo()' is defined,
         * $this->foo = ... will use that method to set the value;
         */
        if (method_exists($this, ($setter = 'set' . ucfirst($nm)))) {
            if (func_num_args() <= 2) {
                /*
                 * All member setters and most setter method calls will have
                 * only one argument and this is the fastest way to handle the
                 * call.
                 */
                $this->{$setter}($val);
            } else {
                /*
                 * Handle method setters with multiple arguments
                 */
                $args = func_get_args();
                call_user_func_array(array(&$this, $setter),
                    array_slice($args, 1));
            }

            return;
        }

        if (isset($this->mProperties[$nm])) {
            /* If no setter is defined, just stores the new value */
            if (is_null($this->mProperties[$nm][1])) {
                $this->mProperties[$nm][0] = $val;
                return;
            }
            /* If the setter is FALSE this property is read-only */
            if (FALSE === $this->mProperties[$nm][1]) {
                return;
            }

            /* Use the setter function */
            if (func_num_args() <= 2) {
                $this->{$this->mProperties[$nm][1]}($val);
            } else {
                $args = func_get_args();
                call_user_func_array(array(&$this, $this->mProperties[$nm][1]),
                    array_slice($args, 1));
            }
            return;
        }

        if (!is_null($this->mCoinherits)) {
                foreach ($this->mCoinherits as $coinherit) {
                    if (isset($coinherit->{$nm})) {
                    $coinherit->{$nm} = $val;
                    return;
                }
            }
        }

        // echo dptext("Invalid __set, %s not defined.\n", $nm);
        // Should throw error in future.
    }

    /**
     * Gets the value of a DutchPIPE property using PHP member overloading
     *
     * @access     private
     * @param      string    $nm        name of property
     * @return     mixed     value of property
     */
    public function __get($nm)
    {
        //echo "__get($nm) called\n";

        // Only find dp properties in main object, ordinary members are found
        // without using this method:
        if (isset($this->mProperties[$nm])) {
            if (method_exists($this, 'get' . ucfirst($nm))) {
                if (func_num_args() <= 1) {
                    $rval = $this->{'get' . ucfirst($nm)}();
                } else {
                    $args = func_get_args();
                    $rval = call_user_func_array(array(&$this, 'get' . ucfirst($nm)),
                        array_slice($args, 1));
                }
                return $rval;
            }

            if (is_null($this->mProperties[$nm][2])) {
                return is_array($this->mProperties[$nm][0])
                    ? (array)$this->mProperties[$nm][0]
                    : $this->mProperties[$nm][0];
            }

            if (func_num_args() <= 2) {
                $rval = $this->{$this->mProperties[$nm][2]}();
            } else {
                $args = func_get_args();
                $rval = call_user_func_array(array(&$this,
                    $this->mProperties[$nm][2]), array_slice($args, 1));
            }
            return $rval;
        }

        // Find both dp properties and ordinary members in coinherits:
        if (!is_null($this->mCoinherits)) {
            foreach ($this->mCoinherits as $coinherit) {
                if (isset($coinherit->{$nm})) {
                    return $coinherit->{$nm};
                }
            }
        }

        // echo dptext("Invalid __get, %s not defined.\n", $nm);
        // Should throw error in future.
        return NULL;
    }

    /**
     * Determines if a property is defined in this object using PHP member overloading
     *
     * @access     private
     * @param      string    $nm        name of property
     * @return     boolean   TRUE if property exists and not NULL, FALSE otherwise
     */
    public function __isset($nm)
    {
        //echo "__isset($nm) called...";

        // Only find dp properties in main object, ordinary members are found
        // without using this method:
        if (isset($this->mProperties[$nm])) {
            //echo "Found $nm\n";
            return TRUE;
        }

        // Find both dp properties and ordinary members in coinherits:
        if (!is_null($this->mCoinherits)) {
            foreach ($this->mCoinherits as $coinherit) {
                if (isset($coinherit->{$nm})) {
                    //echo "Found $nm in coinherit\n";
                    return TRUE;
                }
            }
        }
        //echo "NOT FOUND!\n";
        return FALSE;
    }

    /**
     * Unsets the given DutchPIPE property using PHP member overloading
     *
     * @access     private
     * @param      string    $nm        name of property
     */
    public function __unset($nm)
    {
        //echo "__unset($nm) called\n";

        // Only find dp properties in main object, ordinary members are found
        // without using this method:
        if (isset($this->mProperties[$nm])
                && FALSE !== $this->mProperties[$nm][1]) {
            unset($this->mProperties[$nm]);
            return;
        }

        // Find both dp properties and ordinary members in coinherits:
        if (!is_null($this->mCoinherits)) {
            foreach ($this->mCoinherits as $coinherit) {
                if (isset($coinherit->{$nm})) {
                    unset($coinherit->{$nm});
                    return;
                }
            }
        }
    }

    /**
     * Handles set and get methods for properties using PHP member overloading
     *
     * @access     private
     * @param      string    $nm        name of property
     */
    public function __call($name, $params)
    {
        //echo "__call($name, $params) called\n";
        //echo "params:\n"; print_r($params);
        if (strlen($name) > 3) {
            $part1 = substr($name, 0, 3);
            $part2 = strtolower(substr($name, 3, 1))
                . (strlen($name) == 4 ? '' : substr($name, 4));

                //echo $part1 . '---' . $part2 . "\n";

            if (isset($this->mProperties[$part2])) {
                if ('set' === $part1) {
                    if (count($params) <= 1) {
                        return $this->__set(strtolower(substr($name, 3, 1))
                            . substr($name, 4), array_pop($params));
                    }
                    call_user_func_array(array(&$this, '__set'),
                        array_merge(array(strtolower(substr($name, 3, 1))
                        . substr($name, 4)), $params));
                    return;
                }
                if ('get' === $part1) {
                    if (empty($params)) {
                        return $this->__get(strtolower(substr($name, 3, 1))
                            . substr($name, 4));
                    }
                    return call_user_func_array(array(&$this, '__get'),
                        array_merge(array(strtolower(substr($name, 3, 1))
                        . substr($name, 4)), $params));
                }
            }
        }

        // Find both dp properties and ordinary members in coinherits:
        if (!is_null($this->mCoinherits)) {
            foreach ($this->mCoinherits as $coinherit) {
                if ((isset($part1)
                        && method_exists($coinherit, 'isDpProperty')
                        && $coinherit->isDpProperty($part2))
                        || method_exists($coinherit, $name)) {
                    return call_user_func_array(array(&$coinherit, $name), $params);
                }
            }
        }

        //echo dptext("Invalid __call, method %s does not exist.\n", $name);
        return NULL;
    }

    /**
     * Determines if a property is defined in this object
     *
     * Same as isset($ob->{$nm}), except that this method returns TRUE
     * for properties set to NULL.
     *
     * @param      string    $nm        name of property
     * @return     boolean   TRUE if property exists, FALSE otherwise
     */
    final function isDpProperty($nm)
    {
          // First look for property in main object:
        if (isset($this->mProperties[$nm])) {
            return TRUE;
        }

        // ... then search for property in coinherits:
        if (!is_null($this->mCoinherits)) {
            foreach ($this->mCoinherits as $coinherit) {
                if (method_exists($coinherit, 'isDpProperty')
                        && $coinherit->isDpProperty($nm)) {
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    /**
     * Directly set the value of an existing DutchPIPE property
     *
     * @param      string    $nm        name of property
     * @param      string    $val       value of property
     */
    protected function setDpProperty($nm, $val)
    {
        if (isset($this->mProperties[$nm])) {
            $this->mProperties[$nm][0] = $val;
        }
    }

    /**
     * Directly retrieve the value of an existing DutchPIPE property
     *
     * @param      string    $nm        name of property
     * @return     mixed     value of property
     */
    protected function getDpProperty($nm)
    {
        return !isset($this->mProperties[$nm]) ? NULL
            : $this->mProperties[$nm][0];
    }

    /**
     * "Coinherit" a class located at the given pathname
     *
     * @param      string    $pathname  path to code from universe base path
     */
    function coinherit($pathname)
    {
        if (is_null($this->mCoinherits)) {
            $this->mCoinherits = array();
        } elseif (isset($this->mCoinherit[$pathname])) {
            return;
        }
        require_once(DPUNIVERSE_PREFIX_PATH . $pathname);
        $classname =  explode("/", $pathname);
        $classname = ucfirst(!strlen($classname[sizeof($classname) - 1])
            ? 'index' : substr($classname[sizeof($classname) - 1], 0, -4));
        $object = new $classname($this);

        $this->mCoinherits[$pathname] =& $object;
    }

    /**
     * Removes properties and coinherits
     *
     * Called by DpObject::removeDpObject, unsets all properties and coinherits.
     * This makes sure all object pointers are removed and object can be removed
     * from memory by PHP's cleanup mechanism.
     *
     * @access     private
     * @see        DpObject::removeDpObject
     */
    protected function removeDpProperties()
    {
        if (!is_null($this->mCoinherits)) {
            for ($i = 0, $sz = count($this->mCoinherits); $i < $sz; $i++) {
                $this->mCoinherits[$i] = NULL;
                unset($this->mCoinherits[$i]);
            }
        }
        for ($i = 0, $sz = count($this->mProperties); $i < $sz; $i++) {
            $this->mProperties[$i] = NULL;
            unset($this->mProperties[$i]);
        }
        $this->mCoinherits = NULL;
        unset($this->mCoinherits);
    }
}
