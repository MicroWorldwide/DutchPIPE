<?php
/**
 * The standard object which is built upon by all other objects
 *
 * DutchPIPE version 0.4; PHP version 5
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
 * @version    Subversion: $Id: DpObject.php 291 2007-08-22 20:42:12Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */

/**
 * The DutchPIPE property and coinherit system which all objects extend on
 */
inherit(DPUNIVERSE_STD_PATH . 'DpProperties.php');

/**
 * Gets moving constants
 */
inherit(DPUNIVERSE_INCLUDE_PATH . 'move.php');

/**
 * Gets events constants
 */
inherit(DPUNIVERSE_INCLUDE_PATH . 'events.php');

/**
 * Gets action constants
 */
inherit(DPUNIVERSE_INCLUDE_PATH . 'actions.php');

/**
 * Gets title type constants
 */
inherit(DPUNIVERSE_INCLUDE_PATH . 'title_types.php');

/**
 * Gets mass constants
 */
inherit(DPUNIVERSE_INCLUDE_PATH . 'mass.php');

/**
 * The standard object which is built upon by all other objects
 *
 * Creates the following DutchPIPE properties:<br />
 *
 * - string <b>uniqueId</b> - Unique Id for this instance, for example
 *   'object_436'
 * - integer <b>resetTime</b> - UNIX timestamp of next reset
 * - string <b>location</b> - Location in dpuniverse, for example
 *   '/page/manual.php'
 * - string <b>sublocation</b> - Optional sublocation, for example
 *   'introduction'
 * - int|float <b>credits</b> - Credits contained, integer or float depending
 *   on type, initially 0
 * - string <b>template</b> - Optional replacement for dpdefault.tpl,
 *   absolute path on server
 * - string <b>title</b> - Title for this object, "beer", used for object
 *   labels, etc.
 * - string <b>titleDefinite</b> - Definite title for this object, "the beer"
 * - string <b>titleIndefinite</b> - Indefinite title for this object, "a beer"
 * - string <b>titleType</b> - Type of this object's title
 * - string <b>titleImg</b> - URL for the avatar or other image representing
 *   this object
 * - boolean <b>isDraggable</b> - Can we be dragged on the screen by a given
 *   user? Experimental
 * - string <b>body</b> - HTML content of this object
 * - integer <b>creationTime</b> - UNIX timestamp when this object instance was
 *   created
 * - integer <b>lastEventTime</b> - UNIX timestamp of last event, used by
 *   cleanup mechanism
 * - string <b>navigationTrailHtml</b> - HTML with a navigation trail for this
 *   page
 * - int|float <b>value</b> - Monetary value, integer or float depending on
 *   type, initially 0
 * - boolean <b>isRemoved</b> - TRUE if removed from universe but not destructed
 *   yet by PHP
 * - integer <b>lastActionTime</b> - UNIX timestamp of last action performed by
 *   this object
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: 0.2.1
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */
class DpObject extends DpProperties
{
    /**
     * Public ids for this object, 'beer', 'cool beer'
     *
     * @var        array
     * @access     private
     */
    private $mIds = array();

    /**
     * Actions defined by this object, see addAction
     *
     * @var        array
     * @access     private
     */
    private $mActions = array();

    /**
     * Aliases used for actions, for example 'exa' for 'examine'
     *
     * @var        array
     * @access     private
     */
    private $mActionAliases = array();

    /**
     * Image map areas, enables actions to be defined on areas of images
     *
     * @var        array
     * @access     private
     */
    private $mMapAreas = array();

    /**
     * Clickable actions on definable areas of images
     *
     * @var        array
     * @access     private
     */
    private $mMapAreaActions = array();

    /**
     * 'Items' on this object which can be examined
     *
     * @var        array
     * @access     private
     */
    private $mItems = array();

    /**
     * Aliases used for items
     *
     * @var        array
     * @access     private
     */
    private $mItemAliases = array();

    /**
     * Objects to be checked for presence in our inventory during reset
     *
     * @var        array
     * @access     private
     */
    private $mCheckPresentObjects = array();

    /**
     * Array of strings with method names which the DutchPIPE client may call
     *
     * @var        array
     * @access     private
     * @see        addValidClientCall, removeValidClientCall, isValidClientCall
     * @since      DutchPIPE 0.4.0
     */
    private $mValidClientCalls = array();

    /**
     * Creates this object
     *
     * Called by the universe object when the object is created.
     * Calls {@link createDpObject()} in the inheriting class.
     *
     * :WARNING: Use get_current_dpuniverse()->newDpObject only to create
     * objects, do not use 'new'.
     *
     * :WARNING: It is unlikely you need to call this function directly
     *
     * @access     private
     * @param      string    $unique_id   A unique id for this object
     * @param      int       $reset_time  The UNIX reet time for this object
     * @param      string    $location    Location, e.g. /page/cms.php
     * @param      string    $sublocation Sublocation, e.g. 96
     * @see        __construct2, createDpObject
     */
    final function __construct($unique_id, $reset_time, $location,
            $sublocation = FALSE)
    {
        /* This method may only be called once, at creation time */
        if (isset($this->creationTime)) {
            return;
        }

        /*
         * Sets a unique internal id for this object
         *
         * This method is called by __construct with a string such as
         * 'object_628' to set a unique id. This id is used in HTML output as
         * the id of the DIV element spanning this object's HTML appearance, by
         * the AJAX engine to operate on the object, et cetera.
         *
         * WARNING: Don't call this method. Only __construct should call it.
         */
        $this->uniqueId = new_dp_property('object_' . $unique_id);

        $this->resetTime = new_dp_property($reset_time);
        $this->location = new_dp_property($location);

        if (FALSE !== $sublocation) {
            $this->sublocation = new_dp_property($sublocation);
        }

        /* Standard setup calls to set some default values */

        $this->credits = new_dp_property(0);

        /*
         * Path to non-default XHTML template file for this object, if any
         */
        $this->template = new_dp_property(NULL);

        $this->addId(dp_text('object'));

        /*
         * Title, 'Cool, fresh beer'
         */
        $this->title = new_dp_property(NULL);

        /*
         * Definite title, 'The cool, fresh beer'
         */
        $this->titleDefinite = new_dp_property(NULL);

        /*
         * Indefinite title, 'A cool, fresh beer'
         */
        $this->titleIndefinite = new_dp_property(NULL);

        /*
         * Type of title, 'A beer', 'The beer', etc.
         */
        $this->titleType = new_dp_property(DPUNIVERSE_TITLE_TYPE_INDEFINITE);

        /*
         * Path to the avatar or object image
         */
        $this->titleImg = new_dp_property(DPUNIVERSE_IMAGE_URL . 'object.gif');
        $this->isDraggable = new_dp_property(TRUE);

        /*
         * The long description or page content
         */
        $this->body = new_dp_property(
            dp_text('You see nothing special.<br />'));

        $this->creationTime = new_dp_property(time(), FALSE);
        $this->lastEventTime = new_dp_property(time());

        $this->navigationTrailHtml = new_dp_property(NULL, FALSE);
        $this->value = new_dp_property(0);

        $this->addValidClientCall('getActionsMenu');
        $this->addValidClientCall('reportMove');
    }

    /**
     * Creates this object, continued
     *
     * Called by the universe object when the object is created.
     * Calls {@link createDpObject()} in the inheriting class.
     *
     * :WARNING: It is unlikely you need to call this function directly
     *
     * @access     private
     * @see        __construct, createDpObject
     */
    final function __construct2()
    {
        /* Call CreateDpObjects for objects that extend on this object */
        $this->createDpObject();

        if (!isset($this->title)) {
            $this->title = dp_text('initialized object');
            $this->titleDefinite = dp_text('the initialized object');
            $this->titleIndefinite = dp_text('an initialized object');
        }

        $this->resetDpObject();
    }

    /**
     * Sets this object up at the time it is created
     *
     * An empty function which can be redefined by the class extending on
     * DpObject. When the object is created, it has no title, HTML body, etc.,
     * so here methods such as $this->setTitle() are called.
     * Building blocks extending on DpObject may define their own create
     * function. For example, DpPage defines createDpPage.
     *
     * @see        resetDpObject
     */
    function createDpObject()
    {
    }

    /**
     * Resets this object
     *
     * Called by the universe object at regular intervals as defined in
     * {@link dpuniverse-ini.php}. Calls the method 'resetDpObject' in this
     * object. You can redefine that function to periodically do stuff such as
     * alter the state of this object.
     *
     * @access     private
     * @see        resetDpObject
     */
    final function __reset()
    {
        $this->resetDpObject();
    }

    /**
     * Resets this object
     *
     * Called by this object at regular intervals as defined in
     * dpuniverse-ini.php. An empty function which can be redefined by the
     * class extending on DpObject. To be used to periodically do stuff such as
     * alter the state of this object.
     *
     * @see        createDpObject
     */
    function resetDpObject()
    {
    }

    /**
     * Called by PHP when this object is destroyed, handles events
     *
     * Called by PHP when this object is destroyed. removeDpObject should be
     * called in this object to remove it from the universe. Don't use
     * unset to remove objects. On a PHP level, __destruct will then be called
     * in this object.
     *
     * Calls the event method in this object and its environment, if any, when
     * defined, using constants from events.php.
     *
     * Triggers the EVENT_LEFT_INV event in the environment of this object with
     * "from" ($this) and "to" (0) parameters:
     * $environment->event(EVENT_LEFT_INV, $this, 0);
     *
     * Triggers the EVENT_CHANGED_ENV event in this object with "from" (the
     * environment) and "to" (0) parameters:
     * $this->event(EVENT_CHANGED_ENV, $environment, 0);
     *
     * If the object had no environment, the above two events are not called,
     * instead the EVENT_CHANGED_ENV event is triggered in this object with only
     * a "from" (0) parameter:
     * $this->event(EVENT_CHANGED_ENV, 0);
     *
     * Last, the EVENT_DESTROYING_OBJ event is triggered in this object:
     * $this->event(EVENT_DESTROYING_OBJ);
     *
     * @see        removeDpObject, events.php
     */
    function __destruct()
    {
        if (!isset($this->isRemoved) || TRUE !== $this->isRemoved) {
            $this->removeDpObject();
        }

        echo sprintf(dp_text("__destruct() called in object %s (%s).\n"),
            $this->uniqueId,
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE));
    }

    /**
     * Removes this object from the universe
     *
     * The object is destroyed and no longer part of the universe. Don't use
     * unset to remove objects. Removes the object from browsers viewing the
     * same environment. On a PHP level, __destruct will then be called in this
     * object.
     *
     * @see        __destruct
     */
    function removeDpObject()
    {
        echo dp_text("removeDpObject() called in object " . $this->getTitle()
            . ".\n");

        foreach ($this->mCheckPresentObjects as $key => &$ob) {
            unset($this->mCheckPresentObjects[$key]);
        }

        $this->mCheckPresentObjects = array();
        $this->mActions = array();
        $this->mActionAliases = array();

        $env = $this;
        while (FALSE !== $env->getEnvironment()) {
            $env = $env->getEnvironment();
        }
        $env->tell('<removeDpElement id="' . $this->uniqueId
            . '">&#160;</removeDpElement>');

        $this->isRemoved = new_dp_property(TRUE);

        if (FALSE !== ($env = $this->getEnvironment())) {
            $env->event(EVENT_LEFT_INV, $this, 0);
            $this->event(EVENT_CHANGED_ENV, $env, 0);
        }
        else {
            $this->event(EVENT_CHANGED_ENV, 0);
        }

        $this->event(EVENT_DESTROYING_OBJ);

        /* :TODO: Move all users somewhere so they don't get destroyed */
        $inv = $this->getInventory();

        foreach ($inv as &$ob) {
            if ($ob->isUser) {
                $ob->moveDpObject(DPUNIVERSE_PAGE_PATH . 'index.php');
            } else {
                $ob->removeDpobject();
            }
        }
        get_current_dpuniverse()->removeDpObject($this);
        DpProperties::removeDpProperties();
        unset($this);
    }

    /**
     * Moves this object into the inventory of another object
     *
     * @param   mixed   &$target_ob path or object to move into to
     * @param   boolean $simple     skip some checks
     * @return  int     TRUE for success, an error code for failure
     */
    function moveDpObject(&$target_ob, $simple = FALSE)
    {
        /* Retrieve the target object, or fail */
        if ((!is_string($target_ob) && !is_object($target_ob))
                || (is_string($target_ob)
                && !($target_ob =
                    get_current_dpuniverse()->getDpObject($target_ob)))) {
            return E_MOVEOBJECT_BADDEST;
        }
        if (isset($this->isLiving) && TRUE === $this->isLiving) {
            $this->lastActionTime = !isset($this->lastActionTime)
                ? new_dp_property(time()) : time();
        }

        $curr_env = $this->getEnvironment();

        /* Do more checking? */
        if (FALSE === $simple) {
            /* Checks if the living carrying this object may not drop it: */
            if (FALSE !== $curr_env && $curr_env->isLiving
                    && (bool)$curr_env->noDrop) {
                return E_MOVEOBJECT_NODROP;
            }

            /* Checks if target is neither a page nor a living */
            if (FALSE !== $target_ob->getEnvironment() &&
                    FALSE === $target_ob->isLiving) {
                /*
                 * Checks if the object may be inserted into containers, for
                 * instance, drinks could disallow this, to prevent 'spilling':
                 */
                if (method_exists($this, 'preventInsert')
                        && (bool)$this->preventInsert()) {
                    return E_MOVEOBJECT_NOSRCINS;
                }

                /* Checks if the target is a container, or fail */
                if (FALSE === ((bool)$target_ob->isContainer)) {
                    return E_MOVEOBJECT_NODSTINS;
                }
            }

            /* Checks if the object may be taken by livings */
            if ($target_ob->isLiving && (bool)$this->noTake) {
                return E_MOVEOBJECT_NOGET;
            }

            if (WEIGHT_TYPE_NONE !== WEIGHT_TYPE) {
                if (isset($this->weight) && isset($target_ob->maxWeightCarry)
                        && $this->weight > 0
                        && $target_ob->weightCarry + $this->weight >
                        $target_ob->maxWeightCarry) {
                    return E_MOVEOBJECT_HEAVY;
                }
            }
            if (VOLUME_TYPE_NONE !== VOLUME_TYPE) {
                if (isset($this->volume) && isset($target_ob->maxVolumeCarry)
                        && $this->volume > 0
                        && $target_ob->volumeCarry + $this->volume >
                        $target_ob->maxVolumeCarry) {
                    return E_MOVEOBJECT_VOLUME;
                }
            }
        }

        /*
         * If we're moving to a new environment, check for user defined
         * functions to prevent movement
         */
        if ($curr_env !== $target_ob) {
            if (method_exists($this, 'preventMove') && ($move_err =
                    $this->preventMove($curr_env, $target_ob))) {
                return $move_err;
            }
            if (!is_null($curr_env)) {
                if (method_exists($this, 'preventLeave') && ($move_err =
                        (int)$curr_env->preventLeave($this, $target_ob))) {
                    return $move_err;
                }
            }
            if (method_exists($this, 'preventEnter') && ($move_err =
                    (int)$target_ob->preventEnter($this, $curr_env))) {
                return $move_err;
            }
        }

        /* Move the object */
        get_current_dpuniverse()->moveDpObject($this, $target_ob);

        /* Post-movement stuff */
        if ($curr_env !== $target_ob) {
            $this->event(EVENT_CHANGED_ENV, $curr_env, $target_ob);
            if ($this->isRemoved) {
                return E_MOVEOBJECT_REMOVED;
            }
            if (FALSE !== ($curr_env)) {
                $old_page = $curr_env;
                while (FALSE !== $old_page->getEnvironment()) {
                    $old_page = $old_page->getEnvironment();
                }
                $curr_env->event(EVENT_LEFT_INV, $this, $target_ob);
            }
            $new_page = $target_ob;
            while (FALSE !== $new_page->getEnvironment()) {
                $new_page = $new_page->getEnvironment();
            }

            $abstract_class = FALSE === $target_ob->getEnvironment()
                ? 'dpobject' : 'dpobject2';

            if (isset($old_page) && $old_page === $new_page) {
                $new_page->tell(array(
                    'abstract' => '<moveDpElement id="'
                        . $this->uniqueId
                        . '" where="' . $target_ob->uniqueId
                        . '" class="' . $abstract_class
                        . '">&#160;</moveDpElement>'));
                if ($target_ob === $new_page) {
                    $new_page->tell(array(
                        'graphical' => '<addDpElement id="'
                            . $this->uniqueId . '" where="'
                            . $target_ob->uniqueId
                            . '" class="title_img draggable">'
                            . $this->getAppearance(1, FALSE, $new_page,
                            'graphical') . '</addDpElement>'));
                } else {
                    $new_page->tell(array(
                        'graphical' => '<removeDpElement id="'
                        . $this->uniqueId
                        . '">&#160;</removeDpElement>'));
                }
            }
            else {
                if (isset($old_page)) {
                    $old_page->tell('<removeDpElement id="'
                        . $this->uniqueId
                        . '">&#160;</removeDpElement>');
                }
                if ($target_ob === $new_page) {
                    $new_page->tell(array('abstract' => '<addDpElement id="'
                        . $this->uniqueId . '" where="'
                        . $target_ob->uniqueId . '" class="'
                        . $abstract_class . '">' . $this->getAppearance(1,
                        FALSE, $new_page, 'abstract') . '</addDpElement>',
                        'graphical' => '<addDpElement id="'
                        . $this->uniqueId . '" where="'
                        . $target_ob->uniqueId
                        . '" class="title_img draggable">'
                        . $this->getAppearance(1, FALSE, $new_page, 'graphical')
                        . '</addDpElement>'), $this);
                }
            }
            $target_ob->event(EVENT_ENTERED_INV, $this, $curr_env);
            $dest_all = $target_ob->getInventory();
            foreach ($dest_all as &$ob) {
                $ob->event(EVENT_ENTERED_ENV, $this, $curr_env);
            }
        }

        if (!isset($this->_GET) || !isset($this->_GET['getdivs'])) {
            $body = isset($this->_GET) && isset($this->_GET['ajax'])
                /* Back/forward button is used */
                ? $target_ob->getAppearanceInventory(0, TRUE, NULL,
                    $this->displayMode)
                : $target_ob->getAppearance(0, TRUE, NULL,
                    $this->displayMode);
            if (FALSE !== $body) {
                $template_path = is_null($target_ob->template) ? ''
                    : ' template="' . $target_ob->template . '"';
                $this->tell('<div id="'
                    . (isset($this->_GET) && isset($this->_GET['ajax'])
                    ? 'dpinventory' . '"' : 'dppage' . '"' . $template_path)
                    . '>' . $body . '</div>');
                $this->tell('<inputpersistent persistent="'
                    . (!$this->inputPersistent ? 'off' : $this->inputPersistent)
                    . '">&nbsp;</inputpersistent>');

                if ($this->isUser && ($type = $target_ob->isMovingArea)) {
                    if (!isset($this->_GET) || !isset($this->_GET['ajax'])) {
                        $this->tell('<script type="text/javascript" src="'
                            . DPUNIVERSE_WWW_URL
                            . 'interface/iutil.js"></script>');
                        $this->tell('<script type="text/javascript" src="'
                            . DPUNIVERSE_WWW_URL
                            . 'interface/idrag.js"></script>');
                    }
                    $containment = $type === 1 ? "containment : 'parent',\n"
                        : '';
                    $cssfix = $type == 1 ? '.dpinventory, .dpinventory2'
                        : '.dpinventory';
                    $this->tell("<script>
function init_drag() {
    if (\$.iDrag == undefined) return;
    \$('div.draggable').DraggableDestroy();
    \$('div.draggable').Draggable({
        handle: 'img.draggable',
        zIndex: 1000,
        ghosting: true,
        opacity: 0.7,{$containment}
        onChange : function() { stopdrag(this) }
    });
    \$('{$cssfix}').css('position', 'relative');
    \$('{$cssfix}').css('overflow', 'hidden');
}
\$(function(){init_drag();});</script>\n");
                }
            }
        }

        return TRUE;
    }

    /**
     * Checks if an object is present in this object's inventory
     *
     * If $what is a string, searches for an object with that id. If $what is an
     * object, searches for that object. Searches are done in the inventory of
     * this object.
     *
     * @param      mixed     $what       string (id) or object to search for
     * @return     boolean   TRUE if $what is in our inventory, FALSE otherwise
     */
    function isPresent($what)
    {
        return get_current_dpuniverse()->isPresent($what, $this);
    }

    /**
     * Makes sure an object is present in this object's inventory
     *
     * Replenishes this object's inventory up to $number instances of $what.
     * This method is usually called from the reset method of pages and npc's.
     *
     * If $what is a string, searches for an object with that id. If $what is an
     * object, searches for that object. Searches are done in the inventory of
     * this object.
     *
     * A $number can be given to search for more objects instead of just one.
     * For example, it could check for the presence of 5 roses, and if there
     * are only 3, create another 2.
     *
     * If $mustBeHere is FALSE (it is TRUE by default), it will not replenish
     * objects which are still in another part of the universe, instead of
     * having been destroyed. For example, the 2 missing roses could have been
     * taken by a user who is now on another page, and with this argument set
     * to FALSE, no extra objects will be created.
     *
     * If $mustBeHere is TRUE, $moveHere can be set to TRUE (it is FALSE by
     * default) to move objects which still exist but are located in another
     * part of the universe, back to this object, its original location.
     *
     * @param      mixed     $what       string (id) or object to search for
     * @param      int       $number     number of such objects to search for
     * @param      boolean   $mustBeHere search only here for object?
     * @param      boolean   $moveHere   replenish from objects elsewhere?
     */
    function makePresent($what, $number = 1, $mustBeHere = TRUE,
            $moveHere = FALSE)
    {
        $found = 0;
        if (0 < count($this->mCheckPresentObjects)) {
            $keys = array_keys($this->mCheckPresentObjects);
            foreach ($keys as $key) {
                if (!isset($this->mCheckPresentObjects[$key])) {
                    continue;
                }
                if (empty($this->mCheckPresentObjects[$key])
                        || $this->mCheckPresentObjects[$key]->isRemoved) {
                    unset($this->mCheckPresentObjects[$key]);
                    continue;
                }
                $ob = $this->mCheckPresentObjects[$key];
                if ((is_object($what) && $ob === $what)
                        || (is_string($what) && $ob->location === $what)) {
                    if ($mustBeHere) {
                        if (!$this->isPresent($ob)) {
                            if ($moveHere) {
                                $ob->moveDpObject($this);
                                if (++$found === $number) {
                                    return;
                                }
                            }
                        } elseif (++$found === $number) {
                            return;
                        }
                    }
                    elseif (++$found === $number) {
                        return;
                    }
                }
            }
        }
        while ($found < $number) {
            $ob = get_current_dpuniverse()->newDpObject($what);
            $ob->moveDpObject($this);
            $this->mCheckPresentObjects[] =& $ob;
            $found++;
        }
    }

    /**
     * Gets the object reference to the environment of this object
     *
     * @return     mixed     object reference or FALSE for no environment
     */
    function &getEnvironment()
    {
        return get_current_dpuniverse()->getEnvironment($this);
    }

    /**
     * Gets an array with object references to all objects in our inventory
     *
     * If this object contains no other objects, an empty array is returned.
     *
     * @return     array     object references to objects in our inventory
     */
    function &getInventory()
    {
        return get_current_dpuniverse()->getInventory($this);
    }

    /**
     * Calls the given method after the given number of seconds
     *
     * Use this to perform delayed method calls in this object. Note that
     * functions such as get_current_dpuser() can be totally different when the
     * method is called. Also note that the actual delay is not exact science.
     *
     * @param      string    $method     name of method to call in this object
     * @param      int       $secs       delay in seconds
     */
    function setTimeout($method, $secs)
    {
        get_current_dpuniverse()->setTimeout($this, $method, $secs);
    }

    /**
     * Tells data (message, window, location, ...) to this object
     *
     * Tells a message to this object, for instance a chat line. Handled by
     * by default.
     *
     * @param      string    $data      message string
     */
    function tell($data)
    {
    }

    /**
     * Sets an array of names used to refer to this object
     *
     * Overwrites previous set ids. Ids are case insensitive and all turned
     * into lowercase.
     *
     * @param      array     $ids        array of name strings
     * @see        addId(), removeId(), getIds(), isId()
     */
    function setIds($ids)
    {
        $this->mIds = array();
        foreach ($ids as $id) {
            $this->mIds[dp_strtolower($id)] = TRUE;
        }
    }

    /**
     * Adds one or more ids for this object
     *
     * A single id can be added at a time, or an array with ids can be given,
     * or multiple arguments can be given (strings or array of strings).
     *
     * @param      string|array  $id     name string or array of name strings
     * @see        setIds(), removeId(), getIds(), isId()
     */
    function addId($id)
    {
        if ($sz = func_num_args()) {
            for ($i = 0; $i < $sz; $i++) {
                $ids = func_get_arg($i);
                if (FALSE === is_array($ids)) {
                    if (dp_strlen($ids)) {
                        $this->mIds[dp_strtolower($ids)] = TRUE;
                    }
                } else {
                    foreach ($ids as $id) {
                        if (dp_strlen($id)) {
                            $this->mIds[dp_strtolower($id)] = TRUE;
                        }
                    }
                }
            }
        }
    }

    /**
     * Removes one or more ids for this object
     *
     * A single id can be removed at a time, or an array with ids can be given,
     * or multiple arguments can be given (strings or array of strings).
     *
     * @param      string|array  $id     name string or array of name strings
     * @see        setIds(), addId(), getIds(), isId()
     */
    function removeId($id)
    {
        if ($sz = func_num_args()) {
            for ($i = 0; $i < $sz && sizeof($this->mIds); $i++) {
                $ids = func_get_arg($i);
                if (FALSE === is_array($ids)) {
                    if (dp_strlen($ids) && isset($this->mIds[$ids])) {
                        unset($this->mIds[$ids]);
                    }
                } else {
                    foreach ($ids as $id) {
                        if (dp_strlen($id) && isset($this->mIds[$id])) {
                            unset($this->mIds[$id]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Gets the array of ids for this object, or an empty array for no ids
     *
     * @return     array     array of name strings
     * @see        setIds(), addId(), removeId(), isId()
     */
    function getIds()
    {
        return $this->mIds;
    }

    /**
     * Checks if the given id is a valid id for this object
     *
     * For example, a barkeeper object was set up like this:
     * $this->addId('barkeeper');
     * Now isId('barkeeper') called in this object will return TRUE, but
     * 'the barkeeper' and 'a barkeeper' will also return TRUE.
     * The disable the last behaviour, set $checkWithArticle to FALSE.
     *
     * @param      string    $id                name string to check
     * @param      string    $checkWithArticle  also check ids with articles
     * @return     boolean   TRUE if the id is valid, FALSE otherwise
     * @see        setIds(), addId(), removeId(), getIds()
     */
    function isId($id, $checkWithArticle = TRUE)
    {
        $id = trim($id);
        return dp_strlen($id) && (isset($this->mIds[$id = dp_strtolower($id)])
            || $id == dp_strtolower($this->getTitle()))
            || $id == $this->uniqueId
            || ($checkWithArticle && $this->_isIdWithArticle($id));
    }

    /**
     * Checks if the given id is a valid id when articles are stripped off
     *
     * Strips off 'a', 'an' and 'the' (for English), and checks is the remainder
     * is a valid id for this object.
     *
     * @access     private
     * @param      string    $id                name string to check
     * @return     boolean   TRUE if the id is valid, FALSE otherwise
     * @see        isId()
     */
    private function _isIdWithArticle($id)
    {
        $articles = explode('#', dp_text('a#an#the'));
        $space_pos = dp_strpos($id, ' ');
        if (FALSE === $space_pos) {
            return FALSE;
        }

        $first_word = dp_substr($id, 0, $space_pos);
        $rest = dp_substr($id, $space_pos + 1);
        return in_array($first_word, $articles) && $this->isId($rest, FALSE);
    }

    /**
     * Sets the title for this object, "beer", used for object labels, etc.
     *
     * This title is used for object labels, page titles, messages, et cetera.
     * Objects are visualized using two mechanisms: the object's "title" and
     * the object's "body". The title is used when the object is nearby, for
     * instance in the environment of a user that sees it. The body is used
     * when we're in the object (a page) or examining the object. In other
     * words, the object's title is a very abstract way to describe the object
     * when looking at it from a greater distance.
     *
     * In plain language, this function controls the avatar or object image,
     * and the label under it.
     *
     * The $title should be a short description like "beer" without "a" or "the"
     * in front.
     *
     * The $title_img should be a URL to the image shown to represent this
     * object.
     *
     * The second argument, $type, should be a constant as defined in
     * include/title_types.php and is used by the framework to, for example,
     * construct lines such as "Lennert takes a cool, fresh beer" instead of
     * "A Lennert takes a cool fresh, beer" or "The Lennert takes a cool, fresh
     * beer".
     *
     * DPUNIVERSE_TITLE_TYPE_INDEFINITE - the title is indefinite, "a beer"
     * DPUNIVERSE_TITLE_TYPE_DEFINITE - the title is definite, "the hulk"
     * DPUNIVERSE_TITLE_TYPE_NAME - the title is a name, "Lennert"
     * DPUNIVERSE_TITLE_TYPE_PLURAL - the title is plural, "sweets" (not yet
     * implemented)
     *
     * @param      string    $title      short description, "beer"
     * @param      string    $titleType  noun type, use the constants above
     * @param      string    $titleImg   URL to avatar or object image
     */
    public function setTitle($title, $titleType = FALSE, $titleImg = FALSE)
    {
        $this->setDpProperty('title', $title);
        $this->titleDefinite = NULL;
        $this->titleIndefinite = NULL;

        if (FALSE !== $titleType) {
            $this->titleType = $titleType;
        }

        if (FALSE !== $titleImg) {
            $this->titleImg = $titleImg;
        }
    }

    /**
     * Gets the object's title with optional prefixes such as 'a' and 'the'
     *
     * Gets the title as set with setTitle, for example "barkeeper" if no $type
     * is given.
     *
     * If $type is DPUNIVERSE_TITLE_TYPE_INDEFINITE, "a ..." or "an ..." is
     * put in front if the object's title type has been set to indefinite. So
     * for a barkeeper this returns "a barkeeper", for me it returns "Lennert".
     *
     * If $type is DPUNIVERSE_TITLE_TYPE_DEFINITE, "the" is put in front if the
     * object's title type is not DPUNIVERSE_TITLE_TYPE_NAME. So for a barkeeper
     * this returns "the barkeeper", for me it returns "Lennert".
     *
     * @param      string    $type       noun type, use the constants above
     * @return     string    the object's title
     */
    public function getTitle($type = NULL)
    {
        switch ($type) {
        case DPUNIVERSE_TITLE_TYPE_INDEFINITE:
            switch ($this->getTitleType()) {
            case DPUNIVERSE_TITLE_TYPE_DEFINITE:
                return !is_null($this->titleDefinite) ? $this->titleDefinite
                    : sprintf(dp_text('the %s'), $this->getDpProperty('title'));
            case DPUNIVERSE_TITLE_TYPE_NAME: case DPUNIVERSE_TITLE_TYPE_PLURAL:
                return $this->getDpProperty('title');
            default:
                if (!is_null($this->titleIndefinite)) {
                    return $this->titleIndefinite;
                }
                $title = $this->getDpProperty('title');
                return (FALSE === dp_strpos(dp_text('aeioux'),
                    dp_strtolower($title{0}))
                    ? sprintf(dp_text('a %s'), $title)
                    : sprintf(dp_text('an %s'), $title));
            }
        case DPUNIVERSE_TITLE_TYPE_DEFINITE:
            switch ($this->getTitleType()) {
            case DPUNIVERSE_TITLE_TYPE_NAME:
                return $this->getDpProperty('title');
            default:
                return !is_null($this->titleDefinite) ? $this->titleDefinite
                    : sprintf(dp_text('the %s'), $this->getDpProperty('title'));
            }
        default:
            return $this->getDpProperty('title');
        }
    }

    /**
     * Sets the HTML content of this object
     *
     * If this is a page, this defines the page content. For other objects, it
     * defines what you see when examining the object or moving into the object
     * (which makes the object behave like a page).
     *
     * When a single argument is given, sets the HTML content to the given text.
     *
     * Pairs of arguments can be given to set the content in other ways, with
     * the second argument the type, and the first the data (what the data is
     * depends on the type).
     *
     * Types are:
     * string (default, raw data)
     * file (read content of given filename)
     *
     * Examples:
     * $this->setBody('Hello world');
     * $this->setBody('helloworld.html', 'file');
     * $this->setBody('Prefix', 'string', 'helloworld.html', 'file', 'Postfix',
     *     'string')
     *
     * When a file is read, the following strings are replaced by their
     * corresponding constants:
     * {DPSERVER_CLIENT_URL}, {DPUNIVERSE_PAGE_PATH} and {DPUNIVERSE_IMAGE_URL}
     *
     * Instead of text or a path, a method to call whenever the body property is
     * requested can be given using an array two elements: an object and a
     * method name. Depending on the type, the method should return text or a
     * path.
     *
     * @param       string    $body       content data or method
     * @param       string    $type       content type
     * @see         getBody
     */
    public function setBody($body, $type = 'text')
    {
        $num_args = func_num_args();
        if (1 == $num_args) {
            $this->setDpProperty('body', $body);
            return;
        }
        $tmp = array();
        for ($i = 0; $i < $num_args; $i += 2) {
            $tmp[] = array(func_get_arg($i), func_get_arg($i + 1));
        }
        $this->setDpProperty('body', $tmp);
    }

    /**
     * Gets the HTML content of this object
     *
     * @return     string    HTML content of this object
     * @see        setBody
     */
    public function getBody()
    {
        $body = $this->getDpProperty('body');
        parse_dp_callable($body, $this);

        if (!is_array($body)) {
            return $this->getMapAreaHtml() . "\n" . $body;
        }

        $rval = '';

        foreach ($body as &$b) {
            $data = $b[0];
            parse_dp_callable($data, $this);
            $type = $b[1];
            if ($type === 'text') {
                $rval .= $data;
            }
            elseif ($type === 'file') {
                $tmp = dp_file_get_contents($data);

                if (FALSE !== $tmp) {
                    $rval .= str_replace(array('{DPSERVER_CLIENT_URL}',
                        '{DPUNIVERSE_PAGE_PATH}', '{DPUNIVERSE_IMAGE_URL}'),
                        array(DPSERVER_CLIENT_URL, DPUNIVERSE_PAGE_PATH,
                        DPUNIVERSE_IMAGE_URL), $tmp);
                }
            } elseif ($type === 'url') {
                /* Experimental, ignore */
                $rval .= $this->getBodyUrl($data);
            }
        }
        return $this->getMapAreaHtml() . "\n" . $rval;
    }

    /**
     * Gets the HTML content of this object
     *
     * Experimental, ignore.
     *
     * @access     private
     * @param      string    $url             location of contents
     * @return     string    HTML content of this object
     */
    private function _getBodyUrl($url)
    {
        echo dp_text("Getting mailman contents\n");

        inherit(DPUNIVERSE_STD_PATH . 'mailman.php');

        $config = array
        (
            'url_var_name'             => 'q',
            'flags_var_name'           => 'hl',
            'get_form_name'            => '__script_get_form',
            'proxy_url_form_name'      => 'poxy_url_form',
            'proxy_settings_form_name' => 'poxy_settings_form',
            'max_file_size'            => -1
        );
        $flags = '0011100000';
        $PHProxy =  new PHProxy($config, $flags);
        $PHProxy->start_transfer($data);
        $tmp = $PHProxy->return_response();

        /*
        $fp = fopen($data, "rb");

        if (!$fp) {
           return false;
        }

        $tmp = "";
        while (! feof($fp)) {
           $tmp .= fread($fp, 4096);
        }

        $meta_data = stream_get_meta_data($fp);
        fclose($fp);
        foreach($meta_data['wrapper_data'] as $response) {
            $tmp .= htmlentities($response) . '<br />';
        }
        */
        $pos1 = stripos($tmp, '<title>');
        $pos2 = stripos($tmp, '</title>');
        $len = dp_strlen($tmp);
        if (FALSE !== $pos1 && FALSE !== $pos2) {
            $this->setTitle($tmp2 = dp_substr($tmp, $pos1 + 7, $pos2 -
                ($pos1 + 7)));
            $this->setNavigationTrail(array(DPUNIVERSE_NAVLOGO, ''),
                $tmp2);
        }
        $tmp = str_replace(array('/mailman2/', '="100%"'),
            array('/mailman/', '="90%"'), $tmp);
        $rval .= $tmp;
    }

    /**
     * Gets the HTML "appearance" of this object
     *
     * Gets HTML to represent the object to another objects. That is, other
     * objects call this method in order to "see" it, and HTML is returned. How
     * an object is seen depends on how the object is related to the object that
     * is viewing it in terms of "physical" location.
     *
     * In other words, a level of 0 means this object is seen by something in
     * its inventory (a user sees a page). Level 1 means this object is seen by
     * an object in its environment (a user sees another user). Level 2 means
     * this object is in the inventory of the object that is seeing it.
     *
     * @param      int       $level           level of visibility
     * @param      boolean   $include_div     include div with id around HTML?
     * @param      object    $from            experimental
     * @param      string    $displayMode     'abstract' or 'graphical'
     * @param      boolean   $displayTitlebar display title bar for pages?
     * @param      boolean   $elementId       to be used as html element id
     * @return     string    HTML "appearance" of this object
     * @see        getAppearanceTitle
     */
    public function getAppearance($level = 0, $include_div = TRUE,
            $from = NULL, $displayMode = 'abstract',
            $displayTitlebar = TRUE, $elementId = 'dppage')
    {
        $user = get_current_dpobject();
        if (empty($user)) {
            $user = get_current_dpuser();
        }
        $body = $inventory = '';
        $titlebar = '';
        if (0 === $level) {
            if (TRUE === $displayTitlebar) {
                $template_path = is_null($this->template) ? ''
                    : ' template="' . $this->template . '"';
                $navtrail = $this->getNavigationTrailHtml();
                if (FALSE === $user) {
                    $titlebar = $navtrail;
                } else {
                    $login_link = !isset($user->isRegistered)
                        || TRUE !== $user->isRegistered
                        ? '<a href="' . DPSERVER_CLIENT_URL . '?location='
                        . DPUNIVERSE_PAGE_PATH. 'login.php" style='
                        . '"padding-left: 4px">' . dp_text('Login/register')
                        . '</a>'
                        : '<a href="' . DPSERVER_CLIENT_URL . '?location='
                        . DPUNIVERSE_PAGE_PATH . 'login.php&amp;act=logout" '
                        . 'style="padding-left: 4px">' . dp_text('Logout')
                        . '</a>';
                    $bottom = dp_text('Go to Bottom');
                    $titlebar = '<div id="titlebarleft">' .
                        $navtrail . '</div><div id='
                        . '"titlebarright">&#160;<div id="dploginout">'
                        .sprintf(dp_text('Welcome %s'), '<span id="username">'
                        . $user->getTitle() . '</span>')
                        . ' <span id="loginlink">'
                        . $login_link . '</span>&#160;&#160;&#160;&#160;'
                        . '<img id="butbottom" src="' . DPUNIVERSE_IMAGE_URL
                        . 'bottom.gif" '
                        . 'align="absbottom" width="11" height="11" border="0" '
                        . 'alt="' . $bottom . '" title="' . $bottom . '" '
                        . 'onClick="_gel(\'dpaction\').focus(); '
                        . 'scroll(0, 999999)" /></div></div>';
                }
                $titlebar = '<div id="titlebar">' . $titlebar . '</div>';
            } else {
                $template_path = '';
            }

            $body = '<div id="' . $elementId . '"' . $template_path
                . '><div id="' . $elementId . '_inner1">' . $titlebar
                . '<div class="' . $elementId . '_inner2" id="' . $elementId
                . '_inner2">' . '<div id="dppage_body">'
                . ($displayTitlebar === -1 ? '' : $this->getBody()
                . '</div><br />');

            $inventory = $this->getAppearanceInventory($level, $include_div,
                $from, $displayMode, $displayTitlebar, $elementId);

            if (isset($this->isLiving) && TRUE === $this->isLiving) {
                $reguser_age = $inactive_time = $session_age = '';
                if ($displayTitlebar !== -1) {
                    if (isset($this->isUser) && TRUE === $this->isUser) {
                        $session_age = (get_current_dpobject()
                            && get_current_dpobject() === $this
                            ? (!isset($this->isRegistered)
                                || TRUE !== $this->isRegistered
                                ? dp_text("You have been %s on this site.",
                                $this->sessionAge)
                                : dp_text("This session, you have been %s on this site.",
                                $this->sessionAge))
                            : (!isset($this->isRegistered)
                                || TRUE !== $this->isRegistered ? ucfirst(
                                dp_text("%s has been %s on this site.",
                                $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                                $this->sessionAge))
                                : dp_text("This session, %s has been %s on this site.",
                                $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                                $this->sessionAge))) . '<br /><br />';
                    }

                    $reguser_age = !isset($this->isRegistered)
                        || TRUE !== $this->isRegistered
                        ? '' : (get_current_dpobject()
                        && get_current_dpobject() === $this ?
                        dp_text("In total, you have been %s on this site.",
                        $this->age)
                        : dp_text("In total, %s has been %s on this site.",
                        $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                        $this->age)) . '<br /><br />';

                    $inactive_time = !isset($this->isInactive)
                        || TRUE !== $this->isInactive ? ''
                        : dp_text('%s has been inactive for %s.',
                        $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                        $this->inactive) . '<br /><br />';
                }

                return $body . $session_age . $reguser_age . $inactive_time .
                    (get_current_dpobject() && get_current_dpobject() === $this
                    ? dp_text('You are carrying:')
                    : ucfirst(dp_text('%s is carrying:',
                        $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))))
                    . '<br />'
                    . ($inventory == '' ? dp_text('Nothing') : $inventory)
                    . '</div></div></div>';
            }
            $body .= $inventory . '</div></div></div>';
            return $body;
        } elseif (1 === $level) {
            $status = !isset($this->status) || FALSE === $this->status
                ? '' : ' (' . $this->status . ')';

            if (is_null($from)) {
                $from = $user;
            }

            if ($displayMode === 'graphical' && isset($this->titleImg)) {
                $title_img = $this->getAppearanceTitle($user);

                return FALSE === $include_div ? $title_img
                    : '<div id="' . $this->uniqueId . '" '
                    . 'class="title_img' . ($from !== $this ? '' : '_me')
                    . ' draggable">' . $title_img . '</div>';
            }

            $body = $from === $this ? '<span class="me">'
                . ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))
                . $status . '</span>'
                : ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))
                . $status;

            $inv = $this->getInventory();
            foreach ($inv as &$ob) {
                $inventory .= $ob->getAppearance($level + 1, $include_div,
                    $from, $displayMode);
            }

            return FALSE === $include_div ? $body . $inventory
                : '<div id="' . $this->uniqueId
                . '" class="dpobject" onClick="get_actions(\''
                . $this->uniqueId . '\')">'
                . $body . $inventory . '</div>';
        } elseif (2 === $level) {
            $status = !isset($this->status) || FALSE === $this->status
                ? '' : ' (' . $this->status . ')';
            return FALSE === $include_div
                ? ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))
                . $status
                : '<div id="' . $this->uniqueId
                . '" class="dpobject2" onClick="get_actions(\''
                . $this->uniqueId . '\')">'
                . ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))
                . $status . '</div>';
        }
        return $body . (0 === dp_strlen($inventory) ? "" : $inventory)
            . '</div></div></div>';
    }

    /**
     * Gets the graphical appearance in HTML for this object
     *
     * Returns the HTML with the image and the title of the object. The method
     * filterAppearance($level, &$from, $appearance, &$user) is called in the
     * environments of the object if defined. $level is 1, $from is an array
     * starting with this object, followed by elements defining a path to the
     * current object being called, $appearance the default appearance HTML and
     * $user the user for which we're getting the appearance of the object. If
     * you define it, it should return a string with the HTML for the
     * appearance, just like getAppearanceTitle does itself.
     *
     * @param      object    &$user        performer of actions
     * @return     string    the graphical appearance in HTML for this object
     * @see        getAppearanceTitle
     * @since      DutchPIPE 0.3.0
     */
    function getAppearanceTitle(&$user)
    {

        $status = !isset($this->status) || FALSE === $this->status
            ? '' : ' (' . $this->status . ')';
        $title_pre = $title_post = '';
        $title_img_class = "dpimage";
        if ($this->isDraggable($user)) {
            $title_img_class .= ' draggable';
        }
        if ($this->isInactive) {
            $title_img_class .= ' dpinactive';
            $title_pre = '<span class="dpinactive_txt">';
            $title_post = '</span>';
        }
        $alt = dp_text('Click me!');

        $img_title = '<img src="' . $this->titleImg . '" '
            . 'border="0" class="' . $title_img_class . '" '
            //. 'onMD="init_drag(\'' . $this->uniqueId . '\', event)" '
            . 'onClick="get_actions(\'' . $this->uniqueId . '\', event)" '
            . 'alt="' . $alt . '" title="' . $alt . '" /> <br />' . $title_pre
            . ucfirst($this->getTitle( DPUNIVERSE_TITLE_TYPE_INDEFINITE))
            . $status . $title_post;

        $env =& $this;
        $from = array();
        do {
            $from[] = $env;
            if (method_exists($env, 'filterAppearance')) {
                $img_title = $env->filterAppearance(1, $from, $img_title,
                    $user);
            }

            $env = $env->getEnvironment();
        } while ($env);

        return $img_title;
    }

    /**
     * Gets the HTML "appearance" of all objects in this object's inventory
     *
     * Gets HTML to represents the all objects in this object's inventory using
     * getAppearance.
     *
     * @param      int       $level           level of visibility of this object
     * @param      boolean   $include_div     include div with id around HTML?
     * @param      object    $from            expiremental
     * @param      string    $displayMode     'abstract' or 'graphical'
     * @return     string    HTML "appearances" of this object's inventory
     */
    function getAppearanceInventory($level = 0, $include_div = TRUE,
            $from = NULL, $displayMode = 'abstract', $displayTitlebar = TRUE,
            $elementId = 'dppage')
    {
        $inv = $this->getInventory();
        $inventory = '';
        foreach ($inv as &$ob) {
            $inventory .= $ob->getAppearance($level + 1, $include_div, $from,
                $displayMode, $displayTitlebar, $elementId);
        }
        $div_id = 'dppage' === $elementId ? 'dpinventory' : 'dpobinv';

        return $inventory == '' ? ''
            : "<div id=\"$div_id\"><div class=\"dpinventory2\" id=\""
                . "{$this->uniqueId}\">$inventory</div><div class=\"dpclr\">"
                . "&nbsp;</div></div><div class=\"dpclr\">&nbsp;</div>";
    }

    /**
     * Gets a HTML navigation trail for this object
     *
     * By default, a 'Home' link is always present.
     *
     * @return     string    HTML for navigation trail
     */
    function getNavigationTrailHtml()
    {
        return '<div id="navlink">' . DPUNIVERSE_NAVLOGO . '</div>';
    }

    /**
     * Adds an examinable 'item' to this object
     *
     * 'Items' in objects allow you to perform some basic actions, such as
     * examine, without making new objects.
     *
     * For example, consider this page with the following description:<br />
     * You are in front of a house. You see a sign.
     *
     * The following call will make the house examinable:<br />
     * <code>add_item('house', 'It is a big, brick house.<br />');</code>
     *
     * The $item can be an identifier string or an array with strings with
     * identifiers, for example array('house', 'brick house'). Such aliases can
     * be used from the command line, for example 'examine brick house'.
     *
     * The $description should be a string with the textual description.
     *
     * The $method is optional and should be a string containing a method or an
     * array with two elements: an object to call and a string with the method.
     * This method is called at runtime to obtain the description. It should be
     * defined like this:<br />
     * <code>function <methodName>($item)</code>
     * and return a string.<br />
     * When the $description is NULL, only the result from this method will be
     * shown. With the $description is a normal string, the result of the method
     * is appended. When the description has the %s token in it, the result of
     * the method is inserted there. Example:<br />
     * <code>add_item('door', 'A solid door. It is %s.', 'getOpenOrClosed');</code>
     *
     * The optional $mapArea can be given to make this item accessible on an
     * imagemap, so people can click on it to examine it. Otherwise the item
     * can only be examined by using the command line. It should be a map area
     * id if you set one earlier with setMapArea, or an array with the arguments
     * to setMapArea to define a new one (see setMapArea documentation for more
     * information).
     *
     * If a map area is used, the action on the imagemap is labelled 'examine'.
     * This can be overruled by supplying $mapAreaActionMenu, which should be
     * a string with an alternative label.
     *
     * @param      mixed     $item              string or array of strings with
     *                                          item ids
     * @param      string    $description       item description
     * @param      mixed     $method            method for dynamic description
     * @param      mixed     $mapArea           imagemap area id or definition
     * @param      mixed     $mapAreaActionMenu action to appear on menu
     * @see        getItem, getItemDescription, getItems, addAction, setMapArea
     * @since      DutchPIPE 0.2.0
     */
    function addItem($item, $description, $method = NULL, $mapArea = NULL,
            $mapAreaActionMenu = NULL)
    {
        if (is_array($item)) {
            $tmp = $item[0];
            if (1 < ($sz = sizeof($item))) {
                for ($i = 1; $i < $sz; $i++) {
                    $this->mItemAliases[$item[$i]] = $tmp;
                }
            }
            $item = $tmp;
        }
        $this->mItems[$item] = array($description, $method);

        if (is_null($mapArea)) {
            return;
        }
        if (is_array($mapArea)) {
            call_user_func_array(array(&$this, 'setMapArea'), $mapArea);
            $mapArea = $mapArea[1];
        }
        if (is_null($mapAreaActionMenu)) {
            $mapAreaActionMenu = dp_text('examine');
        }
        $this->addMapAreaAction($mapArea, $mapAreaActionMenu,
            sprintf(dp_text("examine %s"), $item));
    }

    /**
     * Gets an array with data about an item
     *
     * An array is returned with two elements: the item description and the item
     * method. Either can be NULL. If the item was not found, FALSE is returned.
     *
     * @param      string    $item            the item id to search for
     * @return     array     array with data about the given item, or FALSE
     * @see        addItem, getItemDescription, getItems, addAction, setMapArea
     * @since      DutchPIPE 0.2.0
     */
    function getItem($item)
    {
        if (isset($this->mItems[$item])) {
            return $this->mItems[$item];
        } elseif (isset($this->mItemAliases[$item])) {
            return $this->mItems[$this->mItemAliases[$item]];
        }

        $articles = explode('#', dp_text('a#an#the'));
        $space_pos = dp_strpos($item, ' ');
        if (FALSE !== $space_pos) {
            $first_word = dp_substr($item, 0, $space_pos);
            $rest = dp_substr($item, $space_pos + 1);
            if (in_array($first_word, $articles)) {
                return $this->getItem($item);
            }
        }
        return FALSE;
    }

    /**
     * Gets the description of a given item
     *
     * Returns the description of the given item, or FALSE if no such item was
     * found. Items can be added with a method to call (see addItem), here this
     * method is processed.
     *
     * @param      string    $item            the item id to search for
     * @return     mixed     string with description or FALSE
     * @see        addItem, getItem, getItems, addAction, setMapArea
     * @since      DutchPIPE 0.2.0
     */
    function getItemDescription($item)
    {
        if (isset($this->mItems[$item])) {
            $item_data =& $this->mItems[$item];
        } elseif (isset($this->mItemAliases[$item])) {
            $item_data =& $this->mItems[$this->mItemAliases[$item]];
        } else {
            $articles = explode('#', dp_text('a#an#the'));
            $space_pos = dp_strpos($item, ' ');
            if (FALSE !== $space_pos) {
                $first_word = dp_substr($item, 0, $space_pos);
                $rest = dp_substr($item, $space_pos + 1);
                if (in_array($first_word, $articles)) {
                    return $this->getItemDescription($item);
                }
            }

            return FALSE;
        }

        $description = $item_data[0];
        $method = $item_data[1];

        if (is_null($method)) {
            return $description;
        }

        $method_description = !is_array($method) ? $this->{$method}($item)
            : $method[0]->{$method[1]}($item);

        if (is_null($description) || '' === $description) {
            return $method_description;
        }

        $combined_description = sprintf($description, $method_description);

        return $combined_description != $description ? $combined_description :
            $description . $method_description;
    }

    /**
     * Gets an array with all item data added to this object
     *
     * @return     array     all items added to this object
     * @see        addItem, getItem, getItemDescription, addAction, setMapArea
     * @since      DutchPIPE 0.2.0
     */
    function getItems()
    {
        return $this->mItems;
    }

    /**
     * Adds an action to the object
     *
     * Use this method to have actions added to the menus you get when clicking
     * on items (the "action menu"), and at the same time to add actions you can
     * type. See DpLiving.php for some good examples.
     *
     * Because of the complexity of this method, it has its own manual page.
     * See {@tutorial DutchPIPE/actions.pkg} for further information.
     *
     * @param      mixed     $actionMenu        title of clickable menu item
     * @param      mixed     $actionVerb        alternative verb to type
     * @param      mixed     $actionMethod      method called to perform action
     * @param      mixed     $actionOperant     on who/what does it have effect?
     * @param      mixed     $actionTarget      where should menu item appear?
     * @param      mixed     $actionAuthorized  who may perform this action?
     * @param      mixed     $actionScope       who sees action to begin with?
     * @param      mixed     $mapArea           imagemap area id or definition
     * @param      string    $mapAreaAction     specific action to use
     * @see        removeAction, getActionData,  getActionsMenu, setMapArea
     * @tutorial   DutchPIPE/actions.pkg
     */
    final public function addAction($actionMenu, $actionVerb, $actionMethod,
            $actionOperant = DP_ACTION_OPERANT_MENU,
            $actionTarget = DP_ACTION_TARGET_SELF,
            $actionAuthorized = DP_ACTION_AUTHORIZED_ALL,
            $actionScope = DP_ACTION_SCOPE_ALL,
            $mapArea = NULL,
            $mapAreaAction = NULL)
    {
        if (is_array($actionVerb)) {
            $tmp = $actionVerb[0];
            if (!isset($this->mActions[$tmp])) {
                $new_key = 0;
            } else {
                end($this->mActions[$tmp]);
                $new_key = key($this->mActions[$tmp]) + 1;
                reset($this->mActions[$tmp]);
            }
            if (1 < ($sz = sizeof($actionVerb))) {
                for ($i = 1; $i < $sz; $i++) {
                    if (!isset($this->mActionAliases[$actionVerb[$i]])) {
                        $this->mActionAliases[$actionVerb[$i]] = array();
                    }
                    $this->mActionAliases[$actionVerb[$i]][] =
                        array($tmp, $new_key);
                }
            }
            $actionVerb = $tmp;
        }

        if (!isset($this->mActions[$actionVerb])) {
            $this->mActions[$actionVerb] = array();
        }
        $this->mActions[$actionVerb][] = array($actionMenu, $actionMethod,
            $actionOperant, $actionTarget, $actionAuthorized, $actionScope,
            $mapArea, $mapAreaAction);
        end($this->mActions[$actionVerb]);
        if (!is_null($mapArea)) {
            if (is_array($mapArea)) {
                call_user_func_array(array(&$this, 'setMapArea'), $mapArea);
                $mapArea = $mapArea[1];
            }
            if (is_null($mapAreaAction)) {
                $mapAreaAction = "{$actionVerb} {$this->uniqueId}";
            }

            $this->addMapAreaAction($mapArea, $actionMenu,
                $mapAreaAction, $actionVerb,
                key($this->mActions[$actionVerb]));
        }
    }

    /**
     * Removes the given action.
     *
     * Removes the action associated to the given $actionVerb. If the action was
     * linked to an imagemap area, provide the area id with $mapAreaId to
     * delete this information as well.
     *
     * @param      boolean   $actionVerb  alternative command to type
     * @param      string    $mapAreaId   imagemap area id
     * @see        addAction, getActionData, getActionsMenu
     * @since      DutchPIPE 0.2.0
     */
    final public function removeAction($actionVerb, $mapAreaId = NULL)
    {
        $action_nr = -1;
        if (isset($this->mActionAliases[$actionVerb])) {
            $actionVerb = $this->mActionAliases[$actionVerb][0];
            $action_nr = $this->mActionAliases[$actionVerb][1];
        }

        if (isset($this->mActions[$actionVerb])) {
            if (isset($this->mActions[$actionVerb][$action_nr])) {
                $this->_removeAction($actionVerb, $action_nr);
            }
            foreach ($this->mActions[$actionVerb] as
                    $action_nr => &$action_data) {
                $this->_removeAction($actionVerb, $action_nr, $mapAreaId);
            }
        }
    }

    /**
     * Helper method for removeAction
     *
     * @access     private
     * @param      boolean   $actionVerb  alternative command to type
     * @param      integer   &$action_nr  index of the verb in alternatives list
     * @param      string    $mapAreaId   imagemap area id
     * @see        removeAction
     * @since      DutchPIPE 0.2.0
     */
    private function _removeAction($actionVerb, $actionNr, $mapAreaId = NULL)
    {
        $action_data =& $this->mActions[$actionVerb][$actionNr];

        $map_area_id = $action_data[6];
        if (is_null($mapAreaId) || (!is_null($map_area_id)
                && $mapAreaId === $map_area_id)) {
            if (!is_null($map_area_id)) {
                $this->removeMapAreaAction($map_area_id,
                    $action_data[0],
                    $action_data[7]);
            }
            unset($this->mActions[$actionVerb][$actionNr]);
            if (0 === count($this->mActions[$actionVerb])) {
                unset($this->mActions[$actionVerb]);
            }
        }
    }

    /**
     * Gets data of one, multiple or all actions
     *
     * Without arguments, an array is returned with each element of pair
     * consisting of:
     * verb => array(menulabel, method, operant, target, authorized, scope)
     * with operant one of DP_ACTION_OPERANT_, target one of DP_ACTION_TARGET_,
     * authorized one of DP_ACTION_AUTHORIZED_ and scope one of DP_ACTION_SCOPE_
     * from dpuniverse/include/actions.php, for example:
     * 'read' => array('read me!', 'actionRead', DP_ACTION_OPERANT_MENU,
     *     DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_ALL)
     *
     * You can narrow the result set down by supplying a verb, in which case an
     * array with one or more results is returned, or FALSE if the given verb is
     * not defined as an action.
     *
     * Narrow it down further by giving a number, usually 0, to get the data at
     * the index of the array you would get without supplying a number.
     *
     * @param      boolean   $actionVerb  alternative command to type
     * @param      integer   &$actionNr   index of the verb in alternatives list
     * @return     array     array with actions, can be empty
     * @see        addAction, removeAction, getActionsMenu
     * @since      DutchPIPE 0.2.1
     */
    final function getActionData($actionVerb = NULL, $actionNr = NULL)
    {
        if (is_null($actionVerb)) {
            return $this->mActions;
        }

        if (!isset($this->mActions[$actionVerb])) {
            return FALSE;
        }

        if (is_null($actionNr)) {
            return $this->mActions[$actionVerb];
        }

        if (!isset($this->mActions[$actionVerb][$actionNr])) {
            return FALSE;
        }

        return $this->mActions[$actionVerb][$actionNr];
    }

    /**
     * Tells the current user the HTML with the action menu for this object
     *
     * @see        addAction, removeAction, getActionData, getTargettedActions
     */
    function getActionsMenu()
    {
        /*
         * A checksum is bounced back so the client can find the right
         * response
         */
        if (!($user = get_current_dpuser())
                || !isset($user->_GET['checksum'])) {
            return;
        }

        $user->lastActionTime = !isset($user->lastActionTime)
            ? new_dp_property(time()) : time();

        if (!($rval = $this->_getActionsMenuLevels($user))) {
            return;
        }
        list($lvl_titles, $lvl_cnt, $lvl_last_title) = $rval;

        if (!isset($user->_GET['map_name'])
                || !isset($user->_GET['map_area_id'])) {
            $actions = $this->getTargettedActions($user,
                is_null($lvl_last_title) ? NULL : $lvl_titles);
        } else {
            $actions = $this->getTargettedActions($user,
                (is_null($lvl_last_title) ? NULL : $lvl_titles),
                $user->_GET['map_area_id']);
            /*
             * For map area actions, when there's only one action, don't show a
             * menu, but execute action immediately
             */
            if (1 === count($actions) && 0 === $lvl_cnt) {
                reset($actions);
                $user->_GET['menuaction'] = '1';
                $user->performAction($actions[key($actions)][0]);
                return;
            }
        }

        if (!count($actions)) {
            return;
        }

        $action_menu = '';

        $icon_used = $submenu_used = FALSE;
        foreach ($actions as $action_menu_title => &$action_data) {
            /* If set to FALSE, inserts action into the command line */
            $get_operant = FALSE;
            $is_submenu = is_array($action_data) && $action_data[6];

            $icon = '&nbsp;';
            $icon_over = FALSE;

            if (is_array($action_data) && $action_data[4]) {
                $icon = $action_data[4];
                if ($action_data[5]) {
                    $icon_over = $action_data[5];
                }
                $icon_used = TRUE;
            }

            list($actionstr, $send_action, $ghosted) =
                $this->_getActionsMenuFullAction($action_data, $user);

            if ((!isset($user->inputMode) || 'say' !== $user->inputMode)
                    && '' === $actionstr) {
                $get_operant = $is_submenu = TRUE;
            }

            if (!$ghosted) {
                $gstyle = $gdstyle = $gdsstyle = '';
            } else {
                $gstyle = ' am_ghosted';
                $gdstyle = ' am_deep_ghosted';
                $gdsstyle = ' am_deep_selected_ghosted';
            }

            $mouseover = "if (action_over(this)) { ";
            $add_to_mouseover = '';
            $mouseout = $mouseclick = '';

            if (!$lvl_cnt && !$is_submenu) {
                $mouseover .= "jQuery('div.am_deep_selected')."
                    . "removeClass('am_deep_selected am_selected$gdsstyle')";
                $add_to_mouseover = '; ';
            }
            if (!$is_submenu || $ghosted) {
                $mouseout = "am_target_out = this";
                $mouseclick = $ghosted ? 'am_no_close = true'
                    : ((FALSE === $send_action ? 'show_input'
                    : 'send_action2server') . "('" .
                    addslashes(htmlspecialchars($actionstr)) . "')");
            } else {
                $submenu_used = TRUE;
                $mouseover .= $add_to_mouseover
                    . "jQuery(this).addClass('am_deep_selected" . $gdsstyle
                    . "'); " . (isset($user->_GET['map_area_id'])
                    ? "get_map_area_actions('{$user->_GET['map_name']}', "
                    . "'{$user->_GET['map_area_id']}', "
                    : "get_actions(")
                    . "'{$this->uniqueId}', event, ";

                for ($i = 0; $i < $lvl_cnt; $i++) {
                    $mouseover .= "'"
                        . addslashes(htmlspecialchars($lvl_titles[$i])) . "', ";
                }

                $mouseover .= "'"
                    . addslashes(htmlspecialchars($action_menu_title)) . "')";

                $add_to_mouseover = '; ';
                $mouseclick = 'am_no_close = true';
            }

            if ($icon_over) {
                $mouseover .= $add_to_mouseover
                    . "jQuery('span.am_icon', this).attr('id', "
                    . "jQuery('span.am_icon > img', this).attr('src')); "
                    . "jQuery('span.am_icon > img', this).attr('src', "
                    . "'{$icon_over}')";
            }

            $mouseover .= '}';

            $action_menu .= '<div id="action_menu' . $lvl_cnt . '" '
                . 'class="am' . (!$is_submenu ? '' : ' am_deep' . $gdstyle)
                . $gstyle . '" onMouseOver="' . $mouseover
                . '" onMouseOut="' . $mouseout
                . '" onClick="' . $mouseclick . '">'
                . '<span class="AM_ICON" id=' . $icon_over . '>' . $icon
                . '</span>'
                . '<span class="am_title">' . $action_menu_title . '</span>'
                . '<span class="AM_SUBMENU">&nbsp;</span>&nbsp;'
                . '<br clear="all" /></div>' . "\n";
        }

        $action_menu = str_replace(array('AM_ICON', 'AM_SUBMENU'),
            array((!$icon_used ? 'am_empty' : 'am_icon'),
            (!$submenu_used ? 'am_empty2' : 'am_submenu')), $action_menu);

        $user->tell('<actions id="' . $this->uniqueId
            . '" level="' . $lvl_cnt . '" checksum="' . $user->_GET['checksum']
            . '"><div class="actionwindow_inner">' . $action_menu
            . '</div></actions>');
    }

    /**
     * Gets information about the current trail of menu and submenus
     *
     * Used by getActionsMenu.
     *
     * @access     private
     * @param      object    &$user        performer of actions
     * @return     array     data structure with level info
     * @see        getActionsMenu
     * @since      DutchPIPE 0.3.0
     */
    private function &_getActionsMenuLevels(&$user)
    {
        /*
         * If the user is navigating to submenus, the client passes each menu
         * title leading to the submenu, for example:
         * &l1=foo&l2=bar
         */
        $lvl_titles = array();
        $lvl_cnt = 0;
        $lvl_last_title = NULL;
        while (isset($user->_GET['l' . ($lvl_cnt + 1)])) {
            $lvl_cnt++;
            $lvl_titles[] = $lvl_last_title = $user->_GET['l' . $lvl_cnt];
        }
        $rval = array($lvl_titles, $lvl_cnt, $lvl_last_title);

        return $rval;
    }

    /**
     * Gets actions which can be performed on this object, for action menu
     *
     * Gets an array with actions which can be performed on this object, so
     * we can make a menu when hovering over the object image with the mouse.
     * This includes actions that are defined by other objects but appear in
     * this object's action menu.
     *
     * An array is returned, empty if there are no menu actions found, with
     * key-value pairs. Each key is the (first) menu title associated with the
     * action pair, each value an array of three elements: the verb, the
     * "operant" as defined with addAction and a boolean indicating whether this
     * menu item is ghosted.
     *
     * @param      object    &$user      user getting the menu
     * @param      array     $levels     current path of user in (sub)menu
     * @param      string    $mapAreaId  id of area in imagemap
     * @return     array     array with menu actions, can be empty
     * @see        getActionsMenu
     */
    function &getTargettedActions(&$user, $levels = NULL, $mapAreaId = NULL)
    {
        /* Gets menu actions on this object defined by this object */
        $actions = $this->_getTargettedActions($this, $user, $levels,
            $mapAreaId);

        /*
         * Gets menu actions on this object defined by objects in inventory:
         */
        $inv = $this->getInventory();
        foreach ($inv as &$ob) {
            $actions = array_merge($actions,
                $this->_getTargettedActions($ob, $user, $levels, $mapAreaId));
        }

        /*
         * Gets menu actions on this object defined by objects in this
         * object's environment:
         */
        $env = $this->getEnvironment();
        if (FALSE !== $env) {
            $inv = $env->getInventory();
            foreach ($inv as &$ob) {
                if ($ob !== $this) {
                    $actions = array_merge($actions,
                        $this->_getTargettedActions($ob, $user, $levels,
                        $mapAreaId));
                }
            }

            /*
             * Gets menu actions on this object defined by the object's
             * environment:
             */
            $actions = array_merge($actions,
                $this->_getTargettedActions($env, $user, $levels, $mapAreaId));
        }

        return $actions;
    }

    /**
     * Gets actions that can be performed on a given object by a given user
     *
     * An array is returned with each element a pair consisting of:
     *     menulabel => array(verb, operant, ghosted)
     * with operant one of DP_ACTION_OPERANT_ from
     * dpuniverse/include/actions.php, for example:
     * 'read me!' => array('read', DP_ACTION_OPERANT_MENU, FALSE)
     *
     * @access     private
     * @param      object    &$ob        target of actions
     * @param      object    &$user      performer of actions
     * @param      array     $levels     current path of user in (sub)menu
     * @param      string    $mapAreaId  id of area in imagemap
     * @return     array     array with actions, can be empty
     * @see        getTargettedActions, _checkTargettedAction
     * @since      DutchPIPE 0.3.0
     */
    private function &_getTargettedActions(&$ob, &$user, $levels = NULL,
            $mapAreaId = NULL)
    {
        $ob_actions = is_null($mapAreaId) ? $ob->getActionData()
            : $ob->getMapAreaActions($mapAreaId);

        if (FALSE === $ob_actions || !count($ob_actions)) {
            $ob_actions = array();
            return $ob_actions;
        }
        $t_actions = array();

        $is_registered = isset($user->isRegistered)
            && $this->isRegistered === TRUE;
        $is_admin = isset($user->isAdmin) && $user->isAdmin === TRUE;

        $level = !is_array($levels) ? 0 : count($levels);
        if (is_null($mapAreaId)) {
            foreach ($ob_actions as $verb => &$a_d) {
                foreach ($a_d as $action_data) {
                    if (FALSE === $this->_addTargettedAction($t_actions,
                            $verb, $action_data, $ob, $user, $level, $levels,
                            $is_registered, $is_admin)) {
                        continue;
                    }
                }
            }
        } else {
            foreach ($ob_actions as $action_data) {
                if (FALSE === $this->_addTargettedAction($t_actions, NULL,
                        $action_data, $ob, $user, $level, $levels,
                        $is_registered, $is_admin)) {
                    continue;
                }
            }
        }
        return $t_actions;
    }

    /**
     * Adds action that can be performed on a given object by a given user
     *
     * Used by _getTargettedActions.
     *
     * @access     private
     * @param      array     &$t_actions   targetted actions found so far
     * @param      string    $verb         associated verb for this action
     * @param      array     &$actionData  complete action data
     * @param      object    &$ob          target of actions
     * @param      object    &$user        performer of actions
     * @param      integer   $level        submenu level, starts at 0
     * @param      array     $levels       sublevel titles
     * @param      boolean   $isRegistered is performer a registered user?
     * @param      boolean   $isAdmin      is performer an administrator?
     * @return     array     array with actions, can be empty
     * @see        _getTargettedActions
     * @since      DutchPIPE 0.3.0
     */
    private function _addTargettedAction(&$t_actions, $verb, &$actionData,
            &$ob, &$user, $level, &$levels, $isRegistered, $isAdmin)
    {
        $get_operant = FALSE;
        $add_operant = 0;
        $verb_data = $operant = NULL;

        $is_map_area = is_null($verb);
        if (1 || $level) {
            if ($is_map_area) {
                if (!is_null($actionData[2]) && !is_null($actionData[3])) {
                    $verb_data = $ob->getActionData($actionData[2],
                        $actionData[3]);
                    $operant = $verb_data[2];
                    $verb = $verb_data[2];
                } else {
                    $operant = DP_ACTION_OPERANT_MENU;
                    $verb = $actionData[1];
                }
            } else {
                $operant = $actionData[2];
            }

            parse_dp_callable($actionData[0], $verb, $this, $ob, $user);

            if (is_array($operant) && 2 === count($operant)
                    && is_object($operant[0])) {
                $operant = $operant[0]->{$operant[1]}($verb, $this, $ob,
                    $user);
            } elseif (DP_ACTION_OPERANT_METHOD === $operant) {
                $operant = $this->getActionOperant($verb, $this, $ob,
                    $user);
            }
            if (is_array($operant) ||
                    DP_ACTION_OPERANT_METHOD_MENU === $operant) {
                $get_operant = TRUE;
                $add_operant = 1;
                if (is_array($actionData[0]) && count($actionData[0])
                        < $level - $add_operant) {
                    return FALSE;
                }
            } elseif (is_array($actionData[0])
                    && count($actionData[0]) < $level) {
                return FALSE;
            }

            for ($i = 0; $i < $level; $i++) {
                $action_menu_title = !is_array($actionData[0])
                    ? $actionData[0] : $actionData[0][$i];
                if (FALSE !== ($pos = dp_strrpos($action_menu_title, '#'))) {
                    $action_menu_title = dp_substr($action_menu_title,
                        $pos + 1);
                }

                if (!is_array($actionData[0])) {
                    if (0 === $i && $levels[0] === $action_menu_title) {
                        continue;
                    }
                    return FALSE;
                }
                if ($levels[$i] <> $action_menu_title) {
                    return FALSE;
                }
            }
        }

        $ghosted = FALSE;
        if (!$is_map_area) {
            if (FALSE ===
                    $this->_checkTargettedAction($verb, $actionData, $ob,
                    $user, $isRegistered, $isAdmin)) {
                return FALSE;
            }
            parse_dp_callable($actionData[4], $verb, $this, $ob, $user);
            $ghosted = DP_ACTION_AUTHORIZED_DISABLED & $actionData[4];
        } else {
            if (!is_null($actionData[2]) && !is_null($actionData[3])) {
                if (is_null($verb_data)) {
                    $verb_data = $ob->getActionData($actionData[2],
                        $actionData[3]);
                }

                if (!$get_operant && FALSE === $this->_checkTargettedAction(
                        $actionData[2], $verb_data, $ob, $user, $isRegistered,
                        $isAdmin)) {
                    return FALSE;
                }
                if (is_array($verb_data)) {
                    parse_dp_callable($verb_data[4], $verb, $this, $ob, $user);
                    $ghosted = DP_ACTION_AUTHORIZED_DISABLED & $verb_data[4];
                }
            }
        }

        if (!$get_operant || $ghosted || ($level < (!is_array($actionData[0])
                ? 1 : count($actionData[0])))) {
            $titles =& $actionData[0];
            if (is_string($titles)) {
                $title = $titles;
            } elseif (is_array($titles)) {
                if (2 !== count($titles) || !is_object($titles[0])) {
                    parse_dp_callable($titles[$level], $verb, $this,
                        $ob, $user);
                } else {
                    parse_dp_callable($titles, $verb, $this, $ob,
                        $user);
                }

                $title = !is_array($titles) ? $titles : $titles[$level];
            } else {
                return FALSE;
            }

            $icon = $icon_over = FALSE;
            if (FALSE !== dp_strpos($title, '#')) {
                $tmp = explode('#', $title);
                if (2 == count($tmp)) {
                    $icon = $tmp[0];
                    $title = $tmp[1];
                } elseif (3 == count($tmp)) {
                    $icon = $tmp[0];
                    $icon_over = $tmp[1];
                    $title = $tmp[2];
                }
            }

            $cnt = !is_array($titles) ? 1 : count($titles);
            $is_submenu = $cnt > $level + 1
                - ($ghosted && $level + 1 === $cnt ? 0 : $add_operant);

            $data = !$is_map_area
                ? array($verb, $actionData[2], $ghosted, $ob, $icon,
                    $icon_over, $is_submenu)
                : array($actionData[1], (is_null($verb_data)
                    ? DP_ACTION_OPERANT_MENU : $verb_data[2]), $ghosted, $ob,
                    $icon, $icon_over, $is_submenu);

            $t_actions[$title] = $data;
        } elseif ($get_operant) {
            if (is_array($operant) && 2 === count($operant)
                    && is_object($operant[0])) {
                $data = $operant[0]->{$operant[1]}($verb, $this, $ob,
                    $user);
            } elseif (DP_ACTION_OPERANT_METHOD_MENU === $operant) {
                $data = $ob->getActionOperantMenu($verb, $this, $ob,
                    $user);
            }
            if ($data && is_array($data)) {
                $t_actions += $data;
            }
        }

        return TRUE;
    }

    /**
     * Checks if an action can be included in an action menu
     *
     * Processes DP_ACTION_TARGET_, DP_ACTION_SCOPE_ and DP_ACTION_AUTHORIZED_
     * directives of the action.
     *
     * @access     private
     * @param      string    $verb          associated verb for this action
     * @param      array     &$actionData   complete action data
     * @param      object    &$ob           target of actions
     * @param      object    &$user         performer of actions
     * @param      boolean   $isRegistered  is performer a registered user?
     * @param      boolean   $isAdmin       is performer an administrator?
     * @return     boolean   TRUE to include action, FALSE to exclude
     * @see        getTargettedActions, _getTargettedActions
     * @since      DutchPIPE 0.2.1
     */
    private function _checkTargettedAction($verb, &$actionData, &$ob, &$user,
            $isRegistered = FALSE, $isAdmin = FALSE)
    {
        $target =& $actionData[3];
        parse_dp_callable($target, $verb, $this, $ob, $user);

        if (is_array($target) && 2 === count($target) && isset($target[0])
                && is_object($target[0]) && isset($target[1])
                && is_integer($target[1])) {
            $ob =& $target[0];
            $target = $target[1];
        }

        if ($target === DP_ACTION_TARGET_NONE) {
            return FALSE;
        }

        $compare = 0;

        if ($ob === $this) {
            $compare = $compare | DP_ACTION_TARGET_SELF;
        } else {
            if (isset($this->isLiving) && TRUE === $this->isLiving) {
                $compare = $compare | DP_ACTION_TARGET_LIVING;

                if (isset($this->isUser) && TRUE === $this->isUser) {
                    $compare = $compare | DP_ACTION_TARGET_USER;
                }
            } elseif (FALSE !== ($this_env = $this->getEnvironment())
                    && FALSE !== ($ob_env = $ob->getEnvironment())
                    && $this_env === $ob_env) {
                $compare = $compare | DP_ACTION_TARGET_OBJENV;
            } elseif (FALSE !== ($this_env = $this->getEnvironment())
                    && $this_env === $ob) {
                $compare = $compare | DP_ACTION_TARGET_OBJINV;
            }
        }

        if (!($target & $compare)) {
            return FALSE;
        }

        $scope =& $actionData[5];
        parse_dp_callable($scope, $verb, $this, $ob, $user);

        if (is_array($scope) && 2 === count($scope) && isset($scope[0])
                && is_object($scope[0]) && isset($scope[1])
                && is_integer($scope[1])) {
            $ob =& $scope[0];
            $scope = $scope[1];
        }

        if ($scope & DP_ACTION_SCOPE_SELF) {
            if ($user !== $ob) {
                return FALSE;
            }
        }
        if ($scope & DP_ACTION_SCOPE_INVENTORY) {
            if ($user->getEnvironment() !== $ob) {
                return FALSE;
            }
        }
        if ($scope & DP_ACTION_SCOPE_ENVIRONMENT) {
            if ($user->getEnvironment() !== $ob->getEnvironment()) {
                return FALSE;
            }
        }

        $auth =& $actionData[4];
        parse_dp_callable($auth, $verb, $this, $ob, $user);

        if ($auth & DP_ACTION_AUTHORIZED_GUEST) {
            if (TRUE === $isRegistered) {
                return FALSE;
            }
        } elseif ($auth & DP_ACTION_AUTHORIZED_REGISTERED) {
            if (TRUE !== $isRegistered) {
                return FALSE;
            }
        } elseif ($auth & DP_ACTION_AUTHORIZED_ADMIN) {
            if (TRUE !== $isAdmin) {
                return FALSE;
            }
        }

        return TRUE;
    }

    /**
     * Gets the full action string for an action and some other data
     *
     * Used by getActionsMenu.
     *
     * @access     private
     * @param      array     &$actionData   action data array
     * @param      object    &$user          performer of actions
     * @return     array     data structure with action info
     * @see        getActionsMenu
     * @since      DutchPIPE 0.3.0
     */
    private function _getActionsMenuFullAction(&$actionData, $user)
    {
        $send_action = TRUE;
        $is_submenu = is_array($actionData) && $actionData[6];
        $gstyle = $gdstyle = $gdsstyle = '';
        $ghosted = FALSE;

        if (!$is_submenu && is_array($actionData)) {
            $action = $actionData[0];
            $operant = $actionData[1];
            $ghosted = $actionData[2];
            $defined_by = $actionData[3];
            if ($ghosted) {
                $actionstr = '';
            } else {
                if (is_array($operant) && 2 === count($operant)
                        && is_object($operant[0])) {
                    $operant = $operant[0]->{$operant[1]}($action, $this,
                        $defined_by, $user);
                } elseif (DP_ACTION_OPERANT_METHOD === $operant) {
                    $operant = $this->getActionOperant($action, $this,
                        $defined_by, $user);
                }
                if (is_array($operant) ||
                        DP_ACTION_OPERANT_METHOD_MENU === $operant) {
                    $actionstr = '';
                } elseif (DP_ACTION_OPERANT_MENU === $operant) {
                    $actionstr = isset($user->_GET['map_area_id']) ? $action
                        : $action . ' ' . $this->uniqueId;
                } elseif (DP_ACTION_OPERANT_NONE === $operant) {
                    $actionstr = $action;
                } elseif (is_string($operant)) {
                    $actionstr = $action . ' ' . $operant;
                    if (dp_strlen($actionstr)
                            && ' ' === dp_substr($actionstr, -1)) {
                        if (isset($user->inputMode)
                                && 'say' === $user->inputMode
                                && dp_text('say') !== $action) {
                            $actionstr = '/' . $actionstr;
                        }
                        $send_action = FALSE;
                    }
                } else {
                    /* DP_ACTION_OPERANT_COMPLETE */
                    $actionstr = $action . ' ';
                    if (isset($user->inputMode) && 'say' === $user->inputMode) {
                        $actionstr = dp_text('say') === $action ? ''
                            : '/' . $actionstr;
                    }
                    $send_action = FALSE;
                }
            }
        } else {
            $actionstr = is_string($actionData) ? $actionData : '';
        }

        return array($actionstr, $send_action, $ghosted, $gstyle, $gdstyle,
            $gdsstyle);
    }

    /**
     * Creates a new imagemap area which can be used by actions
     *
     * Creates an area for the imagemap $mapName. Make sure the right <map> and
     * <area> HTML is included when this object is viewed. The image should have
     * the following tag:
     *     usemap="#<map name>"
     *
     * The id given with $mapAreaId can be used by others methods, such as
     * addItem or addAction to refer to this area.
     *
     * The shape of the imagemap given with $mapAreaShape is one of:
     *     circle, poly, rect
     * See your HTML reference for a full explanation of these types.
     *
     * The coordinates of the shape should be given with $mapAreaCoords, for
     * example, the coordinates for a rectangle:
     *     128,132,241,179
     * See your HTML reference for a full explanation about coordinates.
     *
     * An optional tooltip can be shown when hovering over this area with the
     * mouse with $mapAreaAlt.
     *
     * @param      string    $mapName        name of imagemap
     * @param      string    $mapAreaId      id of area in this map
     * @param      string    $mapAreaShape   cirlce, poly, rect
     * @param      string    $mapAreaCoords  x,y,... (depends on shape)
     * @param      string    $mapAreaAlt     mouse tooltip for this area
     * @see        getMapArea, addMapAreaAction, removeMapAreaAction,
     *             getMapAreaActions, getMapAreaHtml, getActionsMenu
     * @since      DutchPIPE 0.2.0
     */
    function setMapArea($mapName, $mapAreaId, $mapAreaShape, $mapAreaCoords,
            $mapAreaAlt = '')
    {
        if (!isset($this->mMapAreas[$mapName])) {
            $this->mMapAreas[$mapName] = array();
        }
        $this->mMapAreas[$mapName][$mapAreaId] =
            array($mapAreaShape, $mapAreaCoords, $mapAreaAlt);
    }

    /**
     * Gets data of one, multiple or all imagemap areas
     *
     * If no arguments are given data of all imagemap areas is returned. If an
     * imagemap name is given, all data for that map is returned, or FALSE if
     * the imagemap is not defined. If both the map name and an id of the area
     * in the map are given, data for that area is returned, or FALSE if not
     * defined.
     *
     * @param      string    $mapName    name of imagemap
     * @param      string    $mapAreaId  id of area in imagemap
     * @return     mixed     array with imagemap areas, can be empty, or FALSE
     * @see        setMapArea, addMapAreaAction, removeMapAreaAction,
     *             getMapAreaActions, getMapAreaHtml, getActionsMenu
     * @since      DutchPIPE 0.2.0
     */
    function getMapArea($mapName = NULL, $mapAreaId = NULL)
    {
        if (is_null($mapName)) {
            return $this->mMapAreas;
        }

        if (is_null($mapAreaId)) {
            return !isset($this->mMapAreas[$mapName]) ? FALSE
                : $this->mMapAreas[$mapName];
        }

        return !isset($this->mMapAreas[$mapName])
            || !isset($this->mMapAreas[$mapName][$mapAreaId]) ? FALSE
            : $this->mMapAreas[$mapName][$mapAreaId];
    }

    /**
     * Adds an action to a map area so it becomes clickable there
     *
     * Creates a menu item labelled $actionMenuTitle on the imagemap area with
     * id $mapAreaId. When clicked, executes $action for that user.
     *
     * Can optionally be associated with an existing action by giving an
     * $actionVerb and $actionVerbKey. Normal map area menu actions always
     * appear. By associating it to a regular action, all its settings such as
     * scope, target and authorization are used. Use addAction to set up such
     * a map area action, as addAction will call addMapAreaAction with the right
     * verb and verb key (a verb key is needed because there can be multiple
     * indentical verbs).

     * @param      string    $mapAreaId        id of area in imagemap
     * @param      string    $actionMenuTitle  title of menu item
     * @param      string    $action           action executed when clicked
     * @param      string    $actionVerb       optional associated verb
     * @param      string    $actionVerbKey    optional index of this verb
     * @see        setMapArea, getMapArea, removeMapAreaAction,
     8             getMapAreaActions, getMapAreaHtml, getActionsMenu
     * @since      DutchPIPE 0.2.0
     */
    function addMapAreaAction($mapAreaId, $actionMenuTitle, $action,
            $actionVerb = NULL, $actionVerbKey = NULL)
    {
        if (!isset($this->mMapAreaActions[$mapAreaId])) {
            $this->mMapAreaActions[$mapAreaId] = array();
        }
        $new_area_data = array($actionMenuTitle, $action, $actionVerb,
            $actionVerbKey);
        foreach ($this->mMapAreaActions[$mapAreaId] as $area_data) {
            if ($area_data === $new_area_data) {
                return;
            }
        }

        $this->mMapAreaActions[$mapAreaId][] = $new_area_data;
    }

    /**
     * Removes a map area action
     *
     * Either 1) the action menu title, 2) the executed action string when
     * clicked (while the title is NULL) or 3) both can be given. Removes the
     * first found match.
     *
     * @param      string    $mapAreaId        id of area in imagemap
     * @param      string    $actionMenuTitle  title of menu item
     * @param      string    $action           action executed when clicked
     * @see        setMapArea, getMapArea, addMapAreaAction, getMapAreaActions,
     *             getMapAreaHtml, getActionsMenu
     * @since      DutchPIPE 0.2.0
     */
    function removeMapAreaAction($mapAreaId, $actionMenuTitle, $action = NULL)
    {
        if (!isset($this->mMapAreaActions[$mapAreaId])
                || (is_null($actionMenuTitle) && is_null($action))) {
            return;
        }

        foreach ($this->mMapAreaActions[$mapAreaId] as $i => &$area_data) {
            if ((is_null($actionMenuTitle)
                    || $area_data[0] === $actionMenuTitle)
                    && (is_null($action) || $area_data[1] === $action)) {
                unset($this->mMapAreaActions[$mapAreaId][$i]);
                return;
            }
        }
    }

    /**
     * Gets all map area actions for all areas or for a given area
     *
     * @param      string    $mapAreaId  id of area in imagemap
     * @return     array     array with map area data, can be empty
     * @see        setMapArea, getMapArea, addMapAreaAction,
     *             removeMapAreaAction, getMapAreaHtml, getActionsMenu
     * @since      DutchPIPE 0.2.0
     */
    function getMapAreaActions($mapAreaId = NULL)
    {
        if (is_null($mapAreaId)) {
            return $this->mMapAreaActions;
        }

        return !isset($this->mMapAreaActions[$mapAreaId]) ? FALSE
            : $this->mMapAreaActions[$mapAreaId];
    }

    /**
     * Gets HTML for all imagemaps or the given imagemap for inclusion in page
     *
     * Constructs the right HTML with <map> and <area> tags and onclick events.
     * Returns an empty string of no map data was found. Used by getBody.
     *
     * @param      string    $mapName        name of imagemap
     * @return     string    HTML for one or more imagemaps, or empty string.
     * @see        getBody, setMapArea, getMapArea, addMapAreaAction,
     *             removeMapAreaAction, getMapAreaActions, getActionsMenu
     * @since      DutchPIPE 0.2.0
     */
    function getMapAreaHtml($mapName = NULL)
    {
        if (is_null($mapName)) {
            $rval = '';
            $map_names = array_keys($this->mMapAreas);
            foreach ($map_names as $map_name) {
                $rval .= $this->getMapAreaHtml($map_name);
            }

            return $rval;
        }

        if (!isset($this->mMapAreas[$mapName])) {
            return '';
        }


        $rval = "<map name=\"{$mapName}\">\n";
        foreach ($this->mMapAreas[$mapName] as
                $map_area_id => &$map_area_data) {
            $actions = $this->getMapAreaActions($map_area_id);
            $nr_of_actions = count($actions);
            if (0 === $nr_of_actions) {
                $alt = '';
            } elseif (1 === $nr_of_actions) {
                reset($actions);
                $action = current($actions);
                $alt = $action[0];
            } else {
                $alt = dp_text('Click for menu');
            }
            $rval .= "<area shape=\"{$map_area_data[0]}\" "
                . "coords=\"{$map_area_data[1]}}\" "
                . "href=\"javascript:void(0)\" "
                . "alt=\"{$alt}\" title=\"{$alt}\" "
                . "onClick=\"get_map_area_actions('{$mapName}', "
                . "'{$map_area_id}', '{$this->uniqueId}', event)\" "
                . "style=\"cursor: pointer\" />\n";
        }
        $rval .= '</map>';

        return $rval;
    }

    /**
     * Tries if a user action can be performed on this object
     *
     * When a user doesn't use the action menu, but gives a command such as
     * 'take beer', or when an NPC performs an action, the system doesn't know
     * which object defines the action. Therefore, it calls this method in all
     * objects which are eligable to perform this action. Objects are searched
     * for, in this order, in the object performing the action, its inventory,
     * in its environment, and the environment itself.
     *
     * performActionSubject returns TRUE if the action was completed by this
     * object, or FALSE if this action was not directed at this object, in which
     * case the system will try the next object, if any.
     *
     * @param      string    $action     the complete input string
     * @param      object    &$living    user or npc performing the action
     * @return     boolean   TRUE for action completed, FALSE otherwise
     */
    final public function performActionSubject($action, &$living)
    {
        if (dp_strlen($action) >= 1 && dp_substr($action, 0, 1) == "'") {
            $say = dp_text('say');
            $action = dp_strlen($action) == 1 ? $say : $say . ' '
                . dp_substr($action, 1);
        } elseif (dp_strlen($action) >= 1 && dp_substr($action, 0, 1) == '"') {
            $tell = dp_text('tell');
            $action = dp_strlen($action) == 1 ? $tell : $tell . ' '
                . dp_substr($action, 1);
        }

        if (FALSE !== ($x = dp_strpos($action, ' '))) {
            $verb = dp_substr($action, 0, $x);
            $noun = trim(dp_substr($action, $x));
            if (!dp_strlen($noun)) {
                $noun = null;
            }
        } else {
            $verb = trim($action);
            $noun = null;
        }

        if (isset($this->isLiving) && TRUE === $this->isLiving
                && $living === $this) {
            /* Try inventory and environment */
            $inv = $this->getInventory();
            foreach ($inv as &$ob) {
                if (TRUE === (bool)$ob->performActionSubject($action,
                        $living)) {
                    return TRUE;
                }
            }
            $env = $this->getEnvironment();
            $inv = $env->getInventory();
            foreach ($inv as &$ob) {
                if ($ob !== $this && TRUE ===
                        (bool)$ob->performActionSubject($action, $living)) {
                    return TRUE;
                }
            }
            if ((boolean)$env->performActionSubject($action, $living)) {
                return TRUE;
            }
        }

        $is_phrase = FALSE;

        if (isset($this->mActions[$verb])) {
            reset($this->mActions[$verb]);
            $action_data = $this->mActions[$verb][key($this->mActions[$verb])];
        } elseif (isset($this->mActions[$action])) {
            $is_phrase = TRUE;
            reset($this->mActions[$verb]);
            $action_data = $this->mActions[$action]
                [key($this->mActions[$verb])];
        } elseif (isset($this->mActionAliases[$verb])) {
            reset($this->mActionAliases[$verb]);
            $alias =& $this->mActionAliases[$verb]
                [key($this->mActionAliases[$verb])];
            $action_data = $this->mActions[$alias[0]][$alias[1]];
        } elseif (isset($this->mActionAliases[$action])) {
            $is_phrase = TRUE;
            reset($this->mActionAliases[$verb]);
            $alias =& $this->mActionAliases[$action]
                [key($this->mActionAliases[$verb])];
            $action_data = $this->mActions[$alias[0]][$alias[1]];
        } else {
            return FALSE;
        }

        $scope =& $action_data[5];
        parse_dp_callable($scope, $verb, NULL, $this, $living);

        if ($scope & DP_ACTION_SCOPE_SELF) {
            if ($living !== $this) {
                return FALSE;
            }
        }
        if ($scope & DP_ACTION_SCOPE_INVENTORY) {
            if ($living->getEnvironment() !== $this) {
                return FALSE;
            }
        }
        if ($scope & DP_ACTION_SCOPE_ENVIRONMENT) {
            if ($living->getEnvironment() !== $this->getEnvironment()) {
                return FALSE;
            }
        }

        $auth =& $action_data[4];
        parse_dp_callable($auth, $verb, NULL, $this, $living);

        if ($auth === DP_ACTION_AUTHORIZED_GUEST) {
            if (TRUE === $living->isRegistered) {
                return FALSE;
            }
        } elseif ($auth === DP_ACTION_AUTHORIZED_REGISTERED) {
            if (TRUE !== $living->isRegistered) {
                return FALSE;
            }
        } elseif ($auth === DP_ACTION_AUTHORIZED_ADMIN) {
            if (TRUE !== $living->isAdmin) {
                return FALSE;
            }
        }

        if (!is_dp_callable($action_data[1])) {
            $call_obj =& $this;
            $call_method = $action_data[1];
        } else {
            $call_obj =& $action_data[1][0];
            $call_method = $action_data[1][1];
        }

        return method_exists($call_obj, $call_method) &&
            (bool)$call_obj->{$call_method}(!$is_phrase ? $verb : $action,
            $noun);
    }

    /**
     * Reports an event
     *
     * Called when certain events occur, given with $name. The property
     * lastEventTime is set to a unix time stamp. Calls eventDpObject.
     *
     * @param      object    $name       Name of event
     * @param      mixed     $args       One or more arguments, depends on event
     */
    final function event($name)
    {
        $this->lastEventTime = time();
        $args = func_get_args();
        call_user_func_array(array($this, 'eventDpObject'), $args);
    }

    /**
     * Reports an event
     *
     * Called when certain events occur, given with $name.
     *
     * @param      object    $name       Name of event
     * @param      mixed     $args       One or more arguments, depends on event
     * @since      DutchPIPE 0.2.0
     */
    function eventDpObject($name)
    {
    }

    /**
     * Called by the universe object, checks if this object can be removed.
     *
     * To save some memory, the universe object will call this method in objects
     * with no environment, which haven't been referenced for a while.
     *
     * If no users or special object using the isNoCleanUp property are present,
     * the object and all of its inventory is destroyed.
     *
     * @since      DutchPIPE 0.2.0
     */
    function handleCleanUp()
    {
        foreach ($this->mCheckPresentObjects as $i => &$ob) {
            if ($ob->getEnvironment() !== $this) {
                echo sprintf(
                    dp_text("handleCleanUp() called in %s: not removed",
                    $this->getTitle()));
                return;
            }
        }

        $inv = $this->getInventory();
        foreach ($inv as &$ob) {
            if ($ob->isUser || $ob->isNoCleanUp) {
                echo sprintf(
                    dp_text("handleCleanUp() called in %s: not removed",
                    $this->getTitle()));
                return;
            }
        }

        echo sprintf(dp_text("handleCleanUp() called in %s: removing\n",
            $this->getTitle()));
        $this->removeDpObject();
    }

    /**
     * Can we be dragged on the screen by the given user?
     *
     * Experimental mouse dragging of objects.
     *
     * @param      object    &$by_who    subject that wants to drag us
     * @return     boolean   TRUE if $by_who may drag us, FALSE otherwise
     * @since      DutchPIPE 0.2.0
     */
    function isDraggable(&$by_who)
    {
        return $this->getDpProperty('isDraggable');
    }

    /**
     * Reports graphical movement of this object to other objects
     *
     * Called by the client-side dpclient-js.php script after an object was
     * dragged by a user. Coordinates are passed as 'x' and 'y' in a GET
     * request and passed on to other users in the same environment.
     *
     * @see        dpclient-js.php
     * @since      DutchPIPE 0.2.0
     */
    function reportMove()
    {
        if (!($user = get_current_dpuser())
                || !($env = $user->getEnvironment())
                || !($inv = $env->getInventory())) {
            return;
        }

        foreach ($inv as &$ob) {
            if ($ob !== $user && $ob->isUser) {
                if ($user !== $this) {
                    $ob->tell(sprintf(dp_text("%s moves %s around.<br />"),
                        $user->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                        $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE)));
                }
                $ob->tell('<reportmove id="' . $this->uniqueId . '" left="'
                    . $user->_GET['x'] . '" top="' . $user->_GET['y']
                    . '">&#160;</reportmove>');
            }
        }
    }

    /**
     * Sets a heap object of a given amount in this object
     *
     * @param      string    $pathname   path to code from universe base path
     * @param      string    $idProperty unique property to identify heap object
     * @param      int|float $amount     amount, depends on heap type
     * @return     boolean   TRUE for success, FALSE otherwise
     * @since      DutchPIPE 0.2.0
     */
    function setHeapAmount($pathname, $idProperty, $amount)
    {
        $inv = $this->getInventory();

        foreach ($inv as &$ob) {
            if ($ob->{$idProperty}) {
                $ob->amount = $amount;
                return TRUE;
            }
        }

        $heap_ob = get_current_dpuniverse()->newDpObject($pathname);
        $heap_ob->amount = $amount;
        $heap_ob->moveDpObject($this);
        return TRUE;
    }

    /**
     * Gets the amount of a given heap object
     *
     * @param      string    $idProperty unique property to identify heap object
     * @return     int|float amount, depends on heap type
     * @since      DutchPIPE 0.2.0
     */
    function getHeapAmount($idProperty)
    {
        $inv = $this->getInventory();
        $amount = 0;

        foreach ($inv as &$ob) {
            if ($ob->{$idProperty}) {
                $amount += $ob->amount;
            }
        }

        return $amount;
    }

    /**
     * Sets the amount of credits in the inventory of this object
     *
     * @param      int|float $credits    amount of credits
     * @return     boolean   TRUE for success, FALSE otherwise
     * @see        getCredits
     * @since      DutchPIPE 0.2.0
     */
    function setCredits($credits)
    {
        return $this->setHeapAmount(DPUNIVERSE_OBJ_PATH . 'credits.php',
            'isCredits', $credits);
    }

    /**
     * Gets the amount of credits in the inventory of this object
     *
     * @return     int|float amount of credits
     * @see        setCredits
     * @since      DutchPIPE 0.2.0
     */
    function getCredits()
    {
        return $this->getHeapAmount('isCredits');
    }

    /**
     * Makes the given method callable from the DutchPIPE client
     *
     * @param      string    $methodName  method to add
     * @see        removeValidClientCall, isValidClientCall
     * @since      DutchPIPE 0.4.0
     */
    protected final function addValidClientCall($methodName)
    {
        $this->mValidClientCalls[] = $methodName;
    }

    /**
     * Removes the given method from callable methods from the DutchPIPE client
     *
     * @param      string    $methodName  method to remove
     * @see        addValidClientCall, isValidClientCall
     * @since      DutchPIPE 0.4.0
     */
    protected final function removeValidClientCall($methodName)
    {
        if (in_array($methodName, $this->mValidClientCalls, TRUE)) {
            unset($this->mValidClientCalls[$methodName]);
        }
    }

    /**
     * May the given method be called in this object from the DutchPIPE client?
     *
     * @param      string    $methodName  method to check
     * @return     boolean   TRUE if the method can be called, FALSE otherwise
     * @see        addValidClientCall, removeValidClientCall
     * @since      DutchPIPE 0.4.0
     */
    final function isValidClientCall($methodName)
    {
        return in_array($methodName, $this->mValidClientCalls, TRUE);
    }
}
?>
