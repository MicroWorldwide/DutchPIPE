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
 * @version    Subversion: $Id: DpDrink.php 2 2006-05-16 00:20:42Z ls $
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
        $this->addId('bottle');
        $this->addProperty('is_full');
        $this->addProperty('is_drink');
        $this->setEmptyTitle("empty bottle");
        $this->setEmptyIds(array('bottle', 'empty bottle'));
        $this->addAction('drink', 'drink', 'actionDrink', DP_ACTION_OPERANT_MENU, DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_ALL);
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
            $this->setTitleImg($this->mEmptyTitleImg);
            $this->setBody($this->mEmptyBody);
            $this->removeProperty('is_full');
        } else {
            $this->setIds($this->mOrigIds);
            $this->setTitle($this->mOrigTitle);
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
            $user->setActionFailure('What do you want to drink?<br />');
            return FALSE;
        }

        if (FALSE !== ($env = $this->getEnvironment())
                && $env->isPresent($noun) !== $this) {
            $user->setActionFailure("You can't drink: $noun<br />");
            return FALSE;
        }

        if ($env !== $user) {
            $user->tell('You must pick it up first.<br />');
            return TRUE;
        }
        if (FALSE === $this->getProperty('is_full')) {
            $user->tell($this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)
                . ' is empty.<br />');
            return TRUE;
        }

        $this->mOrigIds = $this->getIds();
        $this->mOrigTitle = $this->getTitle();
        $this->mOrigTitleImg = $this->getTitleImg();
        $this->mOrigBody = $this->getBody();


        if (FALSE !== ($env = $user->getEnvironment())) {
            $env->tell($user->getTitle() . ' drinks '
                . $this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE) . '.<br />',
                $user);
            $env->tell('<window autoclose="2500" styleclass="dpwindow_drink">'
                . '<h1>BUUUUUUUUUUUUUURRRP!</h1></window>', $user);
        }

        $user->tell('You drink '
            . $this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE) . '.<br />');
        $user->tell('<window autoclose="2500" styleclass="dpwindow_drink">'
            . '<h1>BUUUUUUUUUUUUUURRRP!</h1></window>');
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
            get_current_dpuser()->tell('You would spill it out.<br />');
        }
        return TRUE;
    }
}

?>
