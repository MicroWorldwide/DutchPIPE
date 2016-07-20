<?php
/**
 * A common drink which can be turned into beers, wine, etc.
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
 * @version    Subversion: $Id: DpDrink.php 252 2007-08-02 23:30:58Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpObject
 */

/**
 * Builts upon the standard DpObject class
 */
inherit(DPUNIVERSE_STD_PATH . 'DpObject.php');

/**
 * A common drink which can be turned into beers, wine, etc.
 *
 * Creates the following DutchPIPE properties:<br />
 *
 * - boolean <b>isFull</b> - Set to TRUE
 * - boolean <b>isDrink</b> - Set to TRUE
 * - boolean <b>isNoSell</b> - Set to TRUE
 * - string <b>emptyTitle</b> - Title for empty drink
 * - string <b>emptyTitleDefinite</b> - Definite title for empty drink
 * - string <b>emptyTitleIndefinite</b> - Indefinite title for empty drink
 * - string <b>emptyTitleImg</b> - Image for empty drink
 * - array <b>emptyIds</b> - Id strings for empty drink
 * - array <b>origIds</b> - Id strings for filled drink
 * - string <b>origTitle</b> - Title for filled drink
 * - string <b>origTitleImg</b> - Image for filled drink
 * - string <b>origBody</b> - Body for filled drink
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.1
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpObject
 */
final class DpDrink extends DpObject
{
    /**
     * Creates this drink object
     *
     * Called by DpDrink when this object is created. Adds standard actions
     * which can be performed on this object.
     *
     * Calls {@link createDpDrink()} in the inheriting class.
     *
     * @access     private
     * @see        createDpDrink()
     */
    final function createDpObject()
    {
        $this->addId(dptext('bottle'));

        $this->isFull = new_dp_property(TRUE);
        $this->isDrink = new_dp_property(TRUE);
        $this->isNoSell = new_dp_property(TRUE);
        $this->emptyTitle = new_dp_property(dptext('empty bottle'));
        $this->emptyTitleDefinite = new_dp_property(dptext('the empty bottle'));
        $this->emptyTitleIndefinite = new_dp_property(
            dptext('an empty bottle'));
        $this->emptyTitleImg = new_dp_property(NULL);
        $this->emptyIds =
            new_dp_property(explode('#', dptext('bottle#empty bottle')));
        $this->emptyBody = new_dp_property($this->emptyTitleIndefinite);

        $this->origIds = new_dp_property(NULL);
        $this->origTitle = new_dp_property(NULL);
        $this->origTitleImg = new_dp_property(NULL);
        $this->origBody = new_dp_property(NULL);

        $this->addAction(dptext('drink'), dptext('drink'), 'actionDrink',
            DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
            DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_ALL);

        if (WEIGHT_TYPE_NONE !== WEIGHT_TYPE) {
            $this->coinherit(DPUNIVERSE_STD_PATH . 'mass.php');
            if (WEIGHT_TYPE_ABSTRACT === WEIGHT_TYPE) {
                $this->weight = 1;
            } elseif (WEIGHT_TYPE_METRIC === WEIGHT_TYPE) {
                $this->weight = 100; /* Grams */
            } elseif (WEIGHT_TYPE_USA === WEIGHT_TYPE) {
                $this->weight = 3.5; /* Ounces */
            }
        }

        if (VOLUME_TYPE_NONE !== VOLUME_TYPE) {
            $this->coinherit(DPUNIVERSE_STD_PATH . 'mass.php');
            if (VOLUME_TYPE_ABSTRACT === VOLUME_TYPE) {
                $this->volume = 1;
            } elseif (VOLUME_TYPE_METRIC === VOLUME_TYPE) {
                $this->volume = 33;
            } elseif (VOLUME_TYPE_USA === VOLUME_TYPE) {
                $this->volume = 11.3;
            }
        }

        $this->value = 10;
        $this->createDpDrink();
    }

    /**
     * Sets this drink object up at the time it is created
     *
     * An empty function which can be redefined by the drink object extending on
     * DpDrink. When the object is created, it has no title, HTML body, et
     * cetera, so in this method methods like {@link DpObject::setTitle()} are
     * called.
     *
     * @see        resetDpDrink()
     */
    function createDpDrink()
    {
    }

    /**
     * Resets this drink object
     *
     * Called by DpObject at regular intervals as defined in dpuniverse-ini.php.
     * Calls the method 'resetDpDrink' in this drink object. You can redefine
     * that function to periodically do stuff such as alter the state of this
     * drink object.
     *
     * @access     private
     * @see        resetDpDrink()
     */
    final function resetDpObject()
    {
        $this->resetDpDrink();
    }

    /**
     * Resets this drink object
     *
     * Called by this drink object at regular intervals as defined in
     * dpuniverse-ini.php. An empty function which can be redefined by the
     * drink object extending on DpDrink. To be used to periodically do stuff
     * such as alter the state of the drink object.
     *
     * @see        createDpDrink()
     */
    function resetDpDrink()
    {
    }


    /**
     * Fill or empty the drink
     *
     * Adjusts the drink's appearance and behaviour. Without an argument,
     * fills the drink. Drink objects are filled by default after being created.
     *
     * Drink objects which are filled have the property "isFull" set to TRUE in
     * them.
     *
     * @param      string    $isFull     TRUE to fill, FALSE to empty drink
     */
    public function setFull($isFull = TRUE)
    {
        if (FALSE === $isFull) {
            $this->setIds($this->emptyIds);
            $this->setTitle($this->emptyTitle);
            $this->setTitleDefinite($this->emptyTitleDefinite);
            $this->setTitleIndefinite($this->emptyTitleIndefinite);
            $this->setTitleImg($this->emptyTitleImg);
            $this->setBody($this->emptyBody);
            $this->isFull = FALSE;
            $this->isNoSell = FALSE;
        } else {
            $this->setIds($this->origIds);
            $this->setTitle($this->origTitle);
            $this->setTitleDefinite($this->origTitleDefinite);
            $this->setTitleIndefinite($this->origTitleIndefinite);
            $this->setTitleImg($this->origTitleImg);
            $this->setBody($this->origBody);
            $this->isFull = TRUE;
            $this->isNoSell = TRUE;
        }
    }

    /**
     * Makes a living object drink this object
     *
     * Drinks the drink if $noun is a valid id for this drink and the drink is
     * "full".
     *
     * @param   string  $verb       the action, "drink"
     * @param   string  $noun       what to drink, could be empty
     * @return  boolean TRUE for action completed, FALSE otherwise
     */
    public function actionDrink($verb, $noun)
    {
        $user = get_current_dpobject();
        if (empty($noun)) {
            $user->setActionFailure(dptext('What do you want to drink?<br />'));
            return FALSE;
        }

        if (FALSE !== ($env = $this->getEnvironment())
                && $env->isPresent($noun) !== $this) {
            $user->setActionFailure(sprintf(dptext("You can't drink: %s<br />"),
                $noun));
            return FALSE;
        }

        if ($env !== $user) {
            $user->tell(dptext('You must pick it up first.<br />'));
            return TRUE;
        }
        if (FALSE === $this->isFull) {
            $user->tell(ucfirst(sprintf(dptext('%s is empty.<br />'),
                $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))));
            return TRUE;
        }

        $this->origIds = $this->getIds();
        $this->origTitle = $this->getTitle();
        $this->origTitleImg = $this->getTitleImg();
        $this->origBody = $this->getBody();

        if (FALSE !== ($env = $user->getEnvironment())) {
            $env->tell(ucfirst(sprintf(dptext('%s drinks %s.<br />'),
                $user->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                $this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))),
                $user);
            $env->tell('<window autoclose="2500" styleclass="dpwindow_drink">'
                . '<h1>' . dptext('BUUUUUUUUUUUUUURRRP!') . '</h1></window>',
                $user);
        }

        $user->tell(sprintf(dptext('You drink %s.<br />'),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE)));
        $user->tell('<window autoclose="2500" styleclass="dpwindow_drink">'
            . '<h1>' . dptext('BUUUUUUUUUUUUUURRRP!') . '</h1></window>');
        $this->setFull(FALSE);
        $env->tell(array('abstract' =>
            '<changeDpElement id="'
            . $this->getUniqueId() . '">'
            . $this->getAppearance(1, FALSE) . '</changeDpElement>',
            'graphical' => '<changeDpElement id="'
            . $this->getUniqueId() . '">'
            . $this->getAppearance(1, FALSE, $this, 'graphical')
            . '</changeDpElement>'));
        return TRUE;
    }

    /**
     * Prevents insertion into containers, not used yet
     *
     * @return  boolean     TRUE if this drink can't be inserted, FALSE to allow
     *                      insertion
     * @ignore
     */
    function preventInsert()
    {
        if (FALSE === $this->isFull) {
            return FALSE;
        }

        if (get_current_dpobject()) {
            get_current_dpobject()->tell(
                dptext('You would spill it out.<br />'));
        }
        return TRUE;
    }
}

?>
