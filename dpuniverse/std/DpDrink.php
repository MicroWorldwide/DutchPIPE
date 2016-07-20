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
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: DpDrink.php 22 2006-05-30 20:40:55Z ls $
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
     * @var         string    Title used for empty drink
     * @access      private
     */
     private $mEmptyTitle;

    /**
     * @var         string    Definite title used for empty drink
     * @access      private
     */
     private $mEmptyTitleDefinite;

    /**
     * @var         string    Indefinite title used for empty drink
     * @access      private
     */
     private $mEmptyTitleIndefinite;

    /**
     * @var         string    Title image used for empty drink
     * @access      private
     */
    private $mEmptyTitleImg;

    /**
     * @var         mixed     Body used for empty drink
     * @access      private
     */
    private $mEmptyBody;

    /**
     * @var         array     Ids used for empty drink
     * @access      private
     */
    private $mEmptyIds;

    /**
     * @var         string    Original title so drink can be refilled
     * @access      private
     */
    private $mOrigTitle;

    /**
     * @var         string    Original definite title so drink can be refilled
     * @access      private
     */
    private $mOrigTitleDefinite;

    /**
     * @var         string    Original indefinite title so drink can be refilled
     * @access      private
     */
    private $mOrigTitleIndefinite;

    /**
     * @var         string    Original title image so drink can be refilled
     * @access      private
     */
    private $mOrigTitleImg;

    /**
     * @var         mixed     Original title so drink can be refilled
     * @access      private
     */
    private $mOrigBody;

    /**
     * @var         array     Original ids so drink can be refilled
     * @access      private
     */
    private $mOrigIds;

    function createDpObject()
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

        if (method_exists($this, 'createDpDrink')) {
            $this->createDpDrink();
        }
    }

    function isId($id)
    {
        return strlen($id)
            && ((FALSE === $this->getProperty('is_full')
            && isset($this->mEmptyIds[$id]))
                // || $str == 'bottle'
                || DpObject::isId($id));
    }

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

    public function setEmptyIds($str)
    {
        $this->mEmptyIds = $str;
    }

    public function getEmptyIds()
    {
        return $this->mEmptyIds;
    }

    public function setEmptyTitle($str)
    {
        $this->mEmptyTitle = $str;
    }

    public function getEmptyTitle()
    {
        return $this->mEmptyTitle;
    }

    public function setEmptyTitleDefinite($str)
    {
        $this->mEmptyTitleDefinite = $str;
    }

    public function getEmptyTitleDefinite()
    {
        return $this->mEmptyTitleDefinite;
    }

    public function setEmptyTitleIndefinite($str)
    {
        $this->mEmptyTitleIndefinite = $str;
    }

    public function getEmptyTitleIndefinite()
    {
        return $this->mEmptyTitleIndefinite;
    }

    public function setEmptyTitleImg($str)
    {
        $this->mEmptyTitleImg = $str;
    }

    public function getEmptyTitleImg()
    {
        return $this->mEmptyTitleImg;
    }

    public function setEmptyBody($str)
    {
        $this->mEmptyBody = $str;
    }

    public function getEmptyBody()
    {
        return $this->mEmptyBody;
    }

    public function setMessage($str)
    {
        $this->message = $str;
    }

    public function getMessage()
    {
        return $this->message;
    }

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
     * Prevents insertion into containers, not used yet.
     *
     * @return  boolean     TRUE if this drink can't be inserted, FALSE to allow
     *                      insertion
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
