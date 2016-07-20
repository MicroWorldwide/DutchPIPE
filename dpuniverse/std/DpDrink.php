<?php
/**
 * A common drink which can be turned into beers, wine, etc.
 *
 * DutchPIPE version 0.1; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: DpDrink.php 45 2006-06-20 12:38:26Z ls $
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
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: @package_version@
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        DpObject
 */
final class DpDrink extends DpObject
{
    /**
     * Title used for empty drink
     *
     * @var         string
     * @access      private
     */
     private $mEmptyTitle;

    /**
     * Definite title used for empty drink
     *
     * @var         string
     * @access      private
     */
     private $mEmptyTitleDefinite;

    /**
     * Indefinite title used for empty drink
     *
     * @var         string
     * @access      private
     */
     private $mEmptyTitleIndefinite;

    /**
     * Title image used for empty drink
     *
     * @var         string
     * @access      private
     */
    private $mEmptyTitleImg;

    /**
     * Body used for empty drink
     *
     * @var         mixed
     * @access      private
     */
    private $mEmptyBody;

    /**
     * Ids used for empty drink
     *
     * @var         array
     * @access      private
     */
    private $mEmptyIds;

    /**
     * Original title so drink can be refilled
     *
     * @var         string
     * @access      private
     */
    private $mOrigTitle;

    /**
     * Original definite title so drink can be refilled
     *
     * @var         string
     * @access      private
     */
    private $mOrigTitleDefinite;

    /**
     * Original indefinite title so drink can be refilled
     *
     * @var         string
     * @access      private
     */
    private $mOrigTitleIndefinite;

    /**
     * Original title image so drink can be refilled
     *
     * @var         string
     * @access      private
     */
    private $mOrigTitleImg;

    /**
     * Original title so drink can be refilled
     *
     * @var         mixed
     * @access      private
     */
    private $mOrigBody;

    /**
     * Original ids so drink can be refilled
     *
     * @var         array
     * @access      private
     */
    private $mOrigIds;

    /**
     * Creates this drink object
     *
     * Called by DpDrink when this object is created. Adds standard actions
     * which can be performed on this object.
     *
     * Calls {@link createDpDrink()} in the inheriting class.
     *
     * Adds "is_full" and "is_drink" properties to this object, both set to
     * TRUE.
     *
     * @access     private
     * @see        createDpDrink()
     */
    final function createDpObject()
    {
        $this->addId(dptext('bottle'));
        $this->addProperty('is_full');
        $this->addProperty('is_drink');
        $this->setEmptyTitle(dptext('empty bottle'));
        $this->setEmptyTitleDefinite(dptext('the empty bottle'));
        $this->setEmptyTitleIndefinite(dptext('an empty bottle'));
        $this->setEmptyIds(explode('#', dptext('bottle#empty bottle')));
        $this->addAction(dptext('drink'), dptext('drink'), 'actionDrink',
            DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF,
            DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_ALL);

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
     * Checks if the given id is a valid id for this object
     *
     * @param      string    $id         name string to check
     * @return     bool      TRUE if the id is valid, FALSE otherwise
     */
    function isId($id)
    {
        return strlen($id)
            && ((FALSE === $this->getProperty('is_full')
            && isset($this->mEmptyIds[$id]))
                // || $str == 'bottle'
                || DpObject::isId($id));
    }

    /**
     * Fill or empty the drink
     *
     * Adjusts the drink's appearance and behaviour. Without an argument,
     * fills the drink. Drink objects are filled by default after being created.
     *
     * Drink objects which are filled have the property "is_full" set to TRUE in
     * them.
     *
     * @param      string    $isFull     TRUE to fill, FALSE to empty drink
     */
    public function setFull($isFull = TRUE)
    {
        if (FALSE === $isFull) {
            $this->setIds($this->mEmptyIds);
            $this->setTitle($this->mEmptyTitle);
            $this->setTitleDefinite($this->mEmptyTitleDefinite);
            $this->setTitleIndefinite($this->mEmptyTitleIndefinite);
            $this->setTitleImg($this->mEmptyTitleImg);
            $this->setBody($this->mEmptyBody);
            $this->removeProperty('is_full');
        } else {
            $this->setIds($this->mOrigIds);
            $this->setTitle($this->mOrigTitle);
            $this->setTitleDefinite($this->mOrigTitleDefinite);
            $this->setTitleIndefinite($this->mOrigTitleIndefinite);
            $this->setTitleImg($this->mOrigTitleImg);
            $this->setBody($this->mOrigBody);
            $this->addProperty('is_full');
        }
    }

    /**
     * Sets an array of names used to refer to this drink when it is empty
     *
     * Overwrites previous set ids. Ids are case insensitive and all turned
     * into lowercase.
     *
     * @param      array     $ids        array of name strings
     * @see        getEmptyIds(), DpObject::setIds(), DpObject::isId()
     */
    public function setEmptyIds($ids)
    {
        $this->mEmptyIds = $ids;
    }

    /**
     * Gets the array of ids for this drink when it is empty
     *
     * @return     array     array of name strings
     * @see        setEmptyIds(), DpObject::getIds(), DpObject::isId()
     */
    public function getEmptyIds()
    {
        return $this->mEmptyIds;
    }

    /**
     * Sets the title for this drink when it is empty
     *
     * @param      string    $title      short description, "beer"
     * @see        DpObject::setTitle()
     */
    public function setEmptyTitle($title)
    {
        $this->mEmptyTitle = $title;
    }

    /**
     * Gets the drinks's title when it is empty
     *
     * Gets the title as set with setEmptyTitle, for example "beer".
     *
     * @return     string    the drink's title when it is empty
     * @see        DpObject::getTitle()
     */
    public function getEmptyTitle()
    {
        return $this->mEmptyTitle;
    }

    /**
     * Sets the definite title for this drink when it is empty, "the beer"
     *
     * Must be called after setEmptyTitle, which resets the definite title.
     *
     * @param      string    $title      short definite description, "the beer"
     * @see        DpObject::setTitleDefinite()
     */
    public function setEmptyTitleDefinite($title)
    {
        $this->mEmptyTitleDefinite = $title;
    }

    /**
     * Gets the definite title for this drink when it is empty, "the beer"
     *
     * @return     string    the drink's definite titled when it is empty
     * @see        DpObject::getTitleDefinite()
     */
    public function getEmptyTitleDefinite()
    {
        return $this->mEmptyTitleDefinite;
    }

    /**
     * Sets the indefinite title for this drink when it is empty, "a beer"
     *
     * Must be called after setEmptyTitle, which reset the indefinite title.
     *
     * @param      string    $title      short indefinite description, "a beer"
     * @see        DpObject::setTitleIndefinite()
     */
    public function setEmptyTitleIndefinite($title)
    {
        $this->mEmptyTitleIndefinite = $title;
    }

    /**
     * Gets the indefinite title for this drink when it is empty, "a beer"
     *
     * @return     string    the drink's indefinite title when it is empty
     * @see        DpObject::getTitleIndefinite()
     */
    public function getEmptyTitleIndefinite()
    {
        return $this->mEmptyTitleIndefinite;
    }

    /**
     * Sets URL for the image representing this drink when it is empty
     *
     * @param      string    $titleImg   URL for image when this drink is empty
     * @see        DpObject::setTitleImg()
     */
    public function setEmptyTitleImg($titleImg)
    {
        $this->mEmptyTitleImg = $titleImg;
    }

    /**
     * Gets URL for the image representing this drink when it is empty
     *
     * Returns NULL if no image was set for this drink when it is empty.
     *
     * @return     string    URL for the image representing this drink when it
     *                       is empty or NULL
     * @see        DpObject::getTitleImg()
     */
    public function getEmptyTitleImg()
    {
        return $this->mEmptyTitleImg;
    }

    /**
     * Sets the HTML content of this drink when it is empty
     *
     * @param       string    $body       content data
     * @see         getEmptyBody, DpObject::setBody(), DpObject::getBody()
     */
    public function setEmptyBody($body)
    {
        $this->mEmptyBody = $body;
    }

    /**
     * Gets the HTML content of this drink when it is empty
     *
     * @return     string    HTML content of this drink when it is empty
     * @see        setEmptyBody(), DpObject::getBody(), DpObject::setBody()
     */
    public function getEmptyBody()
    {
        return $this->mEmptyBody;
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
        $user = get_current_dpuser();
        if (empty($noun)) {
            $user->setActionFailure(dptext('What do you want to drink?<br />'));
            return FALSE;
        }

        if (FALSE !== ($env = $this->getEnvironment())
                && $env->isPresent($noun) !== $this) {
            $user->setActionFailure(sprintf(dptext('You can\'t drink: %s<br />'),
                $noun));
            return FALSE;
        }

        if ($env !== $user) {
            $user->tell(dptext('You must pick it up first.<br />'));
            return TRUE;
        }
        if (FALSE === $this->getProperty('is_full')) {
            $user->tell(ucfirst(sprintf(dptext('%s is empty.<br />'),
                $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))));
            return TRUE;
        }

        $this->mOrigIds = $this->getIds();
        $this->mOrigTitle = $this->getTitle();
        $this->mOrigTitleImg = $this->getTitleImg();
        $this->mOrigBody = $this->getBody();

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
        $env->tell('<changeDpElement id="' . $this->getUniqueId()
            . '">' . $this->mEmptyTitle . '</changeDpElement>');
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
        if (FALSE === $this->getProperty('is_full')) {
            return FALSE;
        }

        if (get_current_dpuser()) {
            get_current_dpuser()->tell(dptext('You would spill it out.<br />'));
        }
        return TRUE;
    }
}

?>
