<?php
/**
 * The standard object which is built upon by all other objects
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
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: DpObject.php 196 2007-06-10 22:54:38Z ls $
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
 * - string <b>templateFile</b> - Optional replacement for dpdefault.tpl,
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
 * @version    Release: 0.2.0
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
     * Creates this object
     *
     * Calls {@link createDpObject()} in the inheriting class.
     *
     * :WARNING: Use get_current_dpuniverse()->newDpObject only to create
     * objects, do not use 'new'. DpUniverse will pass a unique id for this
     *
     * :WARNING: it is unlikely you need to call this function directly
     *
     * @access     private
     * @param      string    $unique_id   A unique id for this object
     * @param      int       $reset_time  The UNIX reet time for this object
     * @param      string    $location    Location, e.g. /page/cms.php
     * @param      string    $sublocation Sublocation, e.g. 96
     * @see        createDpObject
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
         * This method is called by __construct with a string such as 'object_628'
         * to set a unique id. This id is used in HTML output as the id of the DIV
         * element spanning this object's HTML appearance, by the AJAX engine to
         * operate on the object, et cetera.
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
        $this->templateFile = new_dp_property(NULL);

        $this->addId(dptext('object'));

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
        $this->body = new_dp_property(dptext('You see nothing special.<br />'));

        $this->creationTime = new_dp_property(time(), FALSE);
        $this->lastEventTime = new_dp_property(time());

        $this->navigationTrailHtml = new_dp_property(NULL, FALSE);
        $this->value = new_dp_property(0);
    }

    final function __construct2()
    {
        /* Call CreateDpObjects for objects that extend on this object */
        $this->createDpObject();

        if (!isset($this->title)) {
            $this->title = dptext('initialized object');
            $this->titleDefinite = dptext('the initialized object');
            $this->titleIndefinite = dptext('an initialized object');
        }

        $this->resetDpObject();
    }

    /**
     * Sets this object up at the time it is created
     *
     * An empty function which can be redefined by the class extending on
     * DpObject. When the object is created, it has no title, HTML body, etc.,
     * so in this function function like $this->setTitle() are called.
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

        echo sprintf(dptext("__destruct() called in object %s (%s).\n"),
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
        echo dptext("removeDpObject() called in object " . $this->getTitle()
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
     * @return  int     FALSE for success, an error code for failure
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
            if (FALSE !== $curr_env && $curr_env->isLiving && (bool)$curr_env->noDrop) {
                return E_MOVEOBJECT_NODROP;
            }

            /* Checks if target is neither a page nor a living */
            if (FALSE !== $target_ob->getEnvironment() &&
                    FALSE === $target_ob->isLiving) {
                /*
                 * Checks if the object may be inserted into containers, for
                 * instance, drinks could disallow this, to prevent 'spilling':
                 */
                if (method_exists($this, 'preventInsert') && (bool)$this->preventInsert()) {
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
                return;
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
                        . '" where="' . $target_ob->getUniqueId()
                        . '" class="' . $abstract_class
                        . '">&#160;</moveDpElement>'));
                if ($target_ob === $new_page) {
                    $new_page->tell(array(
                        'graphical' => '<addDpElement id="'
                            . $this->uniqueId . '" where="'
                            . $target_ob->getUniqueId()
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
                        . $target_ob->getUniqueId() . '" class="'
                        . $abstract_class . '">' . $this->getAppearance(1,
                        FALSE, $new_page, 'abstract') . '</addDpElement>',
                        'graphical' => '<addDpElement id="'
                        . $this->uniqueId . '" where="'
                        . $target_ob->getUniqueId()
                        . '" class="title_img draggable">' . $this->getAppearance(1,
                        FALSE, $new_page, 'graphical') . '</addDpElement>'),
                        $this);
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
                ? $target_ob->getAppearanceInventory(0, TRUE, NULL,
                    $this->displayMode)
                : $target_ob->getAppearance(0, TRUE, NULL,
                    $this->displayMode);
            if (FALSE !== $body) {
                if (!is_null($target_ob->templateFile)) {
                    $this->tell('<xhtml>' . $body . '</xhtml>');
                } else {
                    $this->tell('<div id="'
                        . (isset($this->_GET) && isset($this->_GET['ajax'])
                        ? 'dpinventory' : 'dppage') . '">' . $body . '</div>');
                }
                if ($this->isUser && ($type = $target_ob->isMovingArea)) {
                    if (!isset($this->_GET) || !isset($this->_GET['ajax'])) {
                        $this->tell('<script type="text/javascript" src="/interface/iutil.js"></script>');
                        $this->tell('<script type="text/javascript" src="/interface/idrag.js"></script>');
                    }
                    $containment = $type === 1 ? "containment : 'parent',\n" : '';
                    $cssfix = $type == 1 ? '.dpinventory, .dpinventory2' : '.dpinventory';
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

        return FALSE;
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
     * @see        addId(), removeId(), getIds(), isId(), getUniqueId(),
     */
    function setIds($ids)
    {
        $this->mIds = array();
        foreach ($ids as $id) {
            $this->mIds[strtolower($id)] = TRUE;
        }
    }

    /**
     * Adds one or more ids for this object
     *
     * A single id can be added at a time, or an array with ids can be given,
     * or multiple arguments can be given (strings or array of strings).
     *
     * @param      string|array  $id     name string or array of name strings
     * @see        setIds(), removeId(), getIds(), isId(), getUniqueId(),
     */
    function addId($id)
    {
        if ($sz = func_num_args()) {
            for ($i = 0; $i < $sz; $i++) {
                $ids = func_get_arg($i);
                if (FALSE === is_array($ids)) {
                    if (strlen($ids)) {
                        $this->mIds[strtolower($ids)] = TRUE;
                    }
                } else {
                    foreach ($ids as $id) {
                        if (strlen($id)) {
                            $this->mIds[strtolower($id)] = TRUE;
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
     * @see        setIds(), addId(), getIds(), isId(), getUniqueId(),
     */
    function removeId($id)
    {
        if ($sz = func_num_args()) {
            for ($i = 0; $i < $sz && sizeof($this->mIds); $i++) {
                $ids = func_get_arg($i);
                if (FALSE === is_array($ids)) {
                    if (strlen($ids) && isset($this->mIds[$ids])) {
                        unset($this->mIds[$ids]);
                    }
                } else {
                    foreach ($ids as $id) {
                        if (strlen($id) && isset($this->mIds[$id])) {
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
     * @see        setIds(), addId(), removeId(), isId(), getUniqueId(),
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
     * @see        setIds(), addId(), removeId(), getIds(), getUniqueId()
     */
    function isId($id, $checkWithArticle = TRUE)
    {
        $id = trim($id);
        return strlen($id) && (isset($this->mIds[$id = strtolower($id)])
            || $id == strtolower($this->getTitle()))
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
        $articles = explode('#', dptext('a#an#the'));
        $space_pos = strpos($id, ' ');
        if (FALSE === $space_pos) {
            return FALSE;
        }

        $first_word = substr($id, 0, $space_pos);
        $rest = substr($id, $space_pos + 1);
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
                    : sprintf(dptext('the %s'), $this->getDpProperty('title'));
            case DPUNIVERSE_TITLE_TYPE_NAME: case DPUNIVERSE_TITLE_TYPE_PLURAL:
                return $this->getDpProperty('title');
            default:
                if (!is_null($this->titleIndefinite)) {
                    return $this->titleIndefinite;
                }
                $title = $this->getDpProperty('title');
                return (FALSE === strpos(dptext('aeioux'),
                    strtolower($title{0}))
                    ? sprintf(dptext('a %s'), $title)
                    : sprintf(dptext('an %s'), $title));
            }
        case DPUNIVERSE_TITLE_TYPE_DEFINITE:
            switch ($this->getTitleType()) {
            case DPUNIVERSE_TITLE_TYPE_NAME:
                return $this->getDpProperty('title');
            default:
                return !is_null($this->titleDefinite) ? $this->titleDefinite
                    : sprintf(dptext('the %s'), $this->getDpProperty('title'));
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
     * @param       string    $body       content data
     * @param       string    $type       content type
     * @see         getBody()
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
            $tmp[func_get_arg($i + 1)] = func_get_arg($i);
        }
        $this->setDpProperty('body', $tmp);
    }

    /**
     * Gets the HTML content of this object
     *
     * @return     string    HTML content of this object
     * @see        setBody()
     */
    public function getBody()
    {
        if (!is_array($this->getDpProperty('body'))) {
            return $this->getMapAreaHtml() . "\n" . $this->getDpProperty('body');
        }

        $rval = '';
        $body = $this->getDpProperty('body');
        foreach ($body as $type => &$data) {
            if ($type === 'text') {
                $rval .= $data;
            }
            elseif ($type === 'file') {
                $tmp = file_get_contents(
                    (DPSERVER_GETTEXT_ENABLED && DPSERVER_LOCALE != '0'
                    && file_exists(DPSERVER_GETTEXT_LOCALE_PATH
                    . DPSERVER_LOCALE . $data)
                    ? DPSERVER_GETTEXT_LOCALE_PATH . DPSERVER_LOCALE
                    : DPUNIVERSE_PREFIX_PATH)
                    . $data);

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
        echo dptext("Getting mailman contents\n");

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
        $len = strlen($tmp);
        if (FALSE !== $pos1 && FALSE !== $pos2) {
            $this->setTitle($tmp2 = substr($tmp, $pos1 + 7, $pos2 -
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
     * @return     string    HTML "appearance" of this object
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
                $navtrail = $this->getNavigationTrailHtml();
                if (FALSE === $user) {
                    $titlebar = $navtrail;
                } else {
                    $login_link = !isset($user->isRegistered)
                        || TRUE !== $user->isRegistered
                        ? '<a href="' . DPSERVER_CLIENT_URL . '?location='
                        . DPUNIVERSE_PAGE_PATH. 'login.php" style='
                        . '"padding-left: 4px">' . dptext('Login/register')
                        . '</a>'
                        : '<a href="' . DPSERVER_CLIENT_URL . '?location='
                        . DPUNIVERSE_PAGE_PATH . 'login.php&amp;act=logout" '
                        . 'style="padding-left: 4px">' . dptext('Logout')
                        . '</a>';
                    $bottom = dptext('Go to Bottom');
                    $titlebar = '<div id="titlebarleft">' .
                        $navtrail . '</div><div id='
                        . '"titlebarright">&#160;<div id="dploginout">'
                        .sprintf(dptext('Welcome %s'), '<span id="username">'
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
            }
            $body = '<div id="' . $elementId . '"><div id="' . $elementId
                . '_inner1">' . $titlebar . '<div class="' . $elementId
                . '_inner2" id="' . $elementId . '_inner2">'
                . '<div id="dppage_body">' . ($displayTitlebar === -1 ? ''
                : $this->getBody() . '</div><br />');

            $inventory = $this->getAppearanceInventory($level, $include_div,
                $from, $displayMode);

            if (isset($this->isLiving) && TRUE === $this->isLiving) {
                $reguser_age = $inactive_time = $session_age = '';
                if ($displayTitlebar !== -1) {
                    if (isset($this->isUser) && TRUE === $this->isUser) {
                        $session_age = (get_current_dpobject()
                            && get_current_dpobject() === $this
                            ? (!isset($this->isRegistered)
                                || TRUE !== $this->isRegistered
                                ? dptext("You have been %s on this site.",
                                $this->sessionAge)
                                : dptext("This session, you have been %s on this site.",
                                $this->sessionAge))
                            : (!isset($this->isRegistered)
                                || TRUE !== $this->isRegistered
                                ? ucfirst(dptext("%s has been %s on this site.",
                                $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                                $this->sessionAge))
                                : dptext("This session, %s has been %s on this site.",
                                $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                                $this->sessionAge))) . '<br /><br />';
                    }

                    $reguser_age = !isset($this->isRegistered)
                        || TRUE !== $this->isRegistered
                        ? '' : (get_current_dpobject()
                        && get_current_dpobject() === $this ?
                        dptext("In total, you have been %s on this site.",
                        $this->age)
                        : dptext("In total, %s has been %s on this site.",
                        $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                        $this->age)) . '<br /><br />';

                    $inactive_time = !isset($this->isInactive)
                        || TRUE !== $this->isInactive ? ''
                        : dptext('%s has been inactive for %s.',
                        $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE),
                        $this->inactive) . '<br /><br />';
                }

                return $body . $session_age . $reguser_age . $inactive_time .
                    (get_current_dpobject() && get_current_dpobject() === $this
                    ? dptext('You are carrying:')
                    : ucfirst(dptext('%s is carrying:',
                        $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))))
                    . '<br />'
                    . ($inventory == '' ? dptext('Nothing') : $inventory)
                    . '</div></div></div>';
            }
            $body .= $inventory . '</div></div></div>';
            if (FALSE !== $displayTitlebar && !is_null($this->templateFile)) {
                $fp = fopen($this->templateFile, 'r');
                $xhtml = fread($fp, filesize($this->templateFile));
                fclose($fp);
                $xhtml = str_replace('{$body}', $body, $xhtml);
                return $xhtml;
            }
            return $body;
        } elseif (1 === $level) {
            $status = !isset($this->status) || FALSE === $this->status
                ? '' : ' (' . $this->status . ')';

            if (is_null($from)) {
                $from = $user;
            }

            if ($displayMode === 'graphical' && isset($this->titleImg)) {
                $title_img_class = "dpimage";
                if ($this->isDraggable($user)) {
                    $title_img_class .= ' draggable';
                }
                $title_img = '<img src="' . $this->titleImg
                    . '" border="0" alt="" class="' . $title_img_class
                    . '" onMD="init_drag(\'' . $this->uniqueId . '\', event)" '
                    . 'onClick="get_actions(\'' . $this->uniqueId
                    . '\', event)" /><br />' . ucfirst($this->getTitle(
                    DPUNIVERSE_TITLE_TYPE_INDEFINITE)) . $status;
                return FALSE === $include_div ? $title_img
                    : '<div id="' . $this->uniqueId
                    . '" class="title_img' . ($from === $this ? '_me' : '')
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
                ? ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE)) . $status
                : '<div id="' . $this->uniqueId
                . '" class="dpobject2" onClick="get_actions(\''
                . $this->uniqueId . '\')">'
                . ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))
                . $status . '</div>';
        }
        return $body . (0 === strlen($inventory) ? "" : $inventory)
            . '</div></div></div>';
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
            $from = NULL, $displayMode = 'abstract')
    {
        $inv = $this->getInventory();
        $inventory = '';
        foreach ($inv as &$ob) {
            $inventory .= $ob->getAppearance($level + 1, $include_div, $from,
                $displayMode);
        }
        return $inventory == '' ? ''
            : "<div id=\"dpinventory\"><div class=\"dpinventory2\" id=\"{$this->uniqueId}\">"
                . "$inventory</div><br clear=\"all\" /></div>";
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
     * For example, consider this page with the following description:
     * You are in front of a house. You see a sign.
     *
     * The following call will make the house examinable:
     * add_item('house', 'It is a big, brick house.<br />');
     *
     * The $item can be an identifier string or an array with strings with
     * identifiers, for example array('house', 'brick house'). Such aliases can
     * be used from the command line, for example 'examine brick house'.
     *
     * The $description should be a string with the textual description:
     * 'It is a big, brick house.<br />'
     *
     * The $method is optional and should be a string containing a method or an
     * array with two elements: an object to call and a string with the method.
     * This method is called at runtime to obtain the description. It should be
     * defined like this:
     * function <methodName>($item)
     * and return a string.
     * When the $description is NULL, only the result from this method will be
     * shown. With the $description is a normal string, the result of the method
     * is appended. When the description has the %s token in it, the result of
     * the method is inserted there. Example:
     * add_item('door', 'A solid door. It is %s.', 'getOpenOrClosed');
     *
     * The optional $mapArea can be given to make this item accessible on an
     * image map, so people can click on it to examine it. It should be a
     * map area id if you set one earlier with setMapArea, or an array with the
     * arguments to setMapArea to define a new one (see setMapArea documentation
     * for more information).
     *
     * If a map area is used, the action on the imagemap is labelled 'examine'.
     * This can be overruled by supplying $mapAreaActionMenu, which should be
     * a string with an alternative label.
     *
     * @param      mixed     $item              string or array of strings with
     *                                          item ids
     * @param      string    $description       item description
     * @param      mixed     $method            method for dynamic description
     * @param      mixed     $mapArea           image map area id or definition
     * @param      mixed     $mapAreaActionMenu action to appear on menu
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
            $mapAreaActionMenu = dptext('examine');
        }
        $this->addMapAreaAction($mapArea, $mapAreaActionMenu,
            sprintf(dptext("examine %s"), $item));
    }

    /**
     * Gets an array with data about an item
     *
     * An array is returned with two elements: the item description and the item
     * method. Either can be NULL. If the item was not found, FALSE is returned.
     *
     * @param      string    $item            the item id to search for
     * @return     array     array with data about the given item, or FALSE
     * @since      DutchPIPE 0.2.0
     */
    function getItem($item)
    {
        if (isset($this->mItems[$item])) {
            return $this->mItems[$item];
        } elseif (isset($this->mItemAliases[$item])) {
            return $this->mItems[$this->mItemAliases[$item]];
        }

        $articles = explode('#', dptext('a#an#the'));
        $space_pos = strpos($item, ' ');
        if (FALSE !== $space_pos) {
            $first_word = substr($item, 0, $space_pos);
            $rest = substr($item, $space_pos + 1);
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
     * @since      DutchPIPE 0.2.0
     */
    function getItemDescription($item)
    {
        if (isset($this->mItems[$item])) {
            $item_data =& $this->mItems[$item];
        } elseif (isset($this->mItemAliases[$item])) {
            $item_data =& $this->mItems[$this->mItemAliases[$item]];
        } else {
            $articles = explode('#', dptext('a#an#the'));
            $space_pos = strpos($item, ' ');
            if (FALSE !== $space_pos) {
                $first_word = substr($item, 0, $space_pos);
                $rest = substr($item, $space_pos + 1);
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
     * type. This method is a bit of a mess yet, with a horrible number of
     * parameters, and needs to be fleshed out more, please bear with me. See
     * DpLiving.php for some good examples.
     *
     * Each action must be added with three mandatory parameters: its title
     * in the menu ($actionMenu), its command line equivalent ($actionVerb),
     * and the method called in this object when a user or object performs the
     * action ($actionMethod).
     *
     * So note.php has the following line in its createDpObject method:
     * > $this->addAction('read me!', 'read', 'actionRead');
     *
     * When someone clicks on the note and selects 'read me!', or types
     * 'read [something]', method actionRead is called with two parameters:
     * > function actionRead($verb, $noun)
     * $verb contains 'read', $noun contains whatever the user typed, or the
     * objects unique id if the mouse was used (see the getUniqueId method).
     *
     * The following four parameters are optional, see include/actions.php for
     * constants used to pass these parameters.
     *
     * $actionOperant is used when you click to perform an action only, and is
     * used to define on who the action operates, in other words, how the verb
     * noun command that is internally formed gets its noun. With the default
     * DP_ACTION_OPERANT_MENU, the noun becomes the object that carries the
     * action in its action menu, for example "object_41", a unique object id.
     * Where a user would type 'read note', clicking would generate
     * 'read object_41'.
     * With DP_ACTION_OPERANT_NONE, there is no noun, for example "laugh".
     * DP_ACTION_OPERANT_COMPLETE can be used to ask the user on who or what
     * the action must be performed, for example "give".
     *
     * $actionTarget is used to determine on which objects' action menus this
     * action must appear. This doesn't have to be this object, the default
     * DP_ACTION_TARGET_SELF. For a user object that defines actions, this would
     * be true for the action "laugh", it should appear in the action menu of
     * the user itself. With DP_ACTION_TARGET_LIVING the menu item appears on
     * other users and computer generated characters, for example action "kiss"
     * should not appear on the user but on anyone near. Likewise, with
     * DP_ACTION_TARGET_OBJINV and DP_ACTION_TARGET_OBJENV you can make the
     * action appear on any object in the users inventory or environment.
     *
     * $actionAuthorized defines what objects or users may perform the action if
     * the action is in their scope (see next).
     * By default, everybody may try to perform the action (although the method
     * that is called may forbid it anyway) and this option is set to
     * DP_ACTION_AUTHORIZED_ALL. With DP_ACTION_AUTHORIZED_REGISTERED, the
     * action becomes only available for registered users. Use
     * DP_ACTION_AUTHORIZED_ADMIN to restrict the action to your site
     * administrators.
     *
     * $actionScope defines which objects have access to the action to begin
     * with. With the default, DP_ACTION_SCOPE_ALL, the action becomes available
     * for the object to which is was added, but also all objects it contains or
     * in its environment. For example, the action "read" on a note uses this.
     * However, the action 'smile' added to a user object is designed so
     * the action becomes available for the user, not other objects nearby, by
     * using DP_ACTION_SCOPE_SELF. With DP_ACTION_SCOPE_ENVIRONMENT and
     * DP_ACTION_SCOPE_INVENTORY you can also restrict the scope.
     *
     * @param      string    $actionMenu        title of clickable menu item
     * @param      boolean   $actionVerb        alternative command to type
     * @param      object    $actionMethod      method called to perform action
     * @param      string    $actionOperant     on who/what does it have effect?
     * @param      boolean   $actionTarget      where should menu item appear?
     * @param      object    $actionAuthorized  who may perform this action?
     * @param      string    $actionScope       who sees action to begin with?
     * @param      mixed     $mapArea           image map area id or definition
     * @param      mixed     $mapAreaActionMenu action to appear on menu
     * @param      mixed     $mapAreaAction     specific action to use
     */
    final public function addAction($actionMenu, $actionVerb, $actionMethod,
            $actionOperant = DP_ACTION_OPERANT_MENU,
            $actionTarget = DP_ACTION_TARGET_SELF,
            $actionAuthorized = DP_ACTION_AUTHORIZED_ALL,
            $actionScope = DP_ACTION_SCOPE_ALL,
            $mapArea = NULL,
            $mapAreaActionTitle = NULL,
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

        if (!is_null($mapArea)) {
            if (is_array($mapArea)) {
                call_user_func_array(array(&$this, 'setMapArea'), $mapArea);
                $mapArea = $mapArea[1];
            }
            if (is_null($mapAreaActionTitle)) {
                $mapAreaActionTitle = $actionMenu;
            }
            if (is_null($mapAreaAction)) {
                $mapAreaAction = "{$actionVerb} {$this->uniqueId}";
            }
            $this->addMapAreaAction($mapArea, $mapAreaActionTitle, $mapAreaAction);
        }
        if (!isset($this->mActions[$actionVerb])) {
            $this->mActions[$actionVerb] = array();
        }
        $this->mActions[$actionVerb][] = array($actionMenu, $actionMethod,
            $actionOperant, $actionTarget, $actionAuthorized, $actionScope,
            $mapArea, $mapAreaActionTitle, $mapAreaAction);
    }

    /**
     * Removes the given action.
     *
     * Removes the action associated to the given $actionVerb. If the action was
     * linked to an image map area, provide the area id with $mapAreaId to
     * delete this information as well.
     *
     * @param      boolean   $actionVerb  alternative command to type
     * @param      string    $mapAreaId   image map area id
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
            foreach ($this->mActions[$actionVerb] as $action_nr => &$action_data) {
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
     * @param      string    $mapAreaId   image map area id
     * @since      DutchPIPE 0.2.0
     */
    private function _removeAction($actionVerb, $action_nr, $mapAreaId = NULL)
    {
        $action_data =& $this->mActions[$actionVerb][$action_nr];

        $map_area_id = $action_data[6];
        if (is_null($mapAreaId) || (!is_null($map_area_id)
                && $mapAreaId === $map_area_id)) {
            if (!is_null($map_area_id)) {
                $this->removeMapAreaAction($map_area_id,
                    $action_data[7],
                    $action_data[8]);
            }
            unset($this->mActions[$actionVerb][$action_nr]);
            if (0 === count($this->mActions[$actionVerb])) {
                unset($this->mActions[$actionVerb]);
            }
        }
    }

    /**
     * Gets an array with all actions added to this object
     *
     * An array is returned with each element of pair consisting of:
     * verb => array(menulabel, method, operant, target, authorized, scope)
     * with operant one of DP_ACTION_OPERANT_, target one of DP_ACTION_TARGET_,
     * authorized one of DP_ACTION_AUTHORIZED_ and scope one of DP_ACTION_SCOPE_
     * from dpuniverse/include/actions.php, for example:
     * 'read' => array('read me!', 'actionRead', DP_ACTION_OPERANT_MENU,
     *     DP_ACTION_TARGET_SELF, DP_ACTION_AUTHORIZED_ALL, DP_ACTION_SCOPE_ALL)
     *
     * @return     array     array with actions, can be empty
     */
    function getActions()
    {
        return $this->mActions;
    }

    /**
     * Tells user the HTML with the action menu for this object
     */
    function getActionsMenu()
    {
        $actions = $this->getTargettedActions(get_current_dpuser());
        $action_menu = '';
        foreach ($actions as $action => $action_data) {
            $send_action = TRUE;
            if (DP_ACTION_OPERANT_MENU === $action_data[1]) {
                $actionstr = $action . ' ' . $this->uniqueId;
            } elseif (DP_ACTION_OPERANT_NONE === $action_data[1]) {
                $actionstr = $action;
            } elseif (is_array($action_data[1])) {
                $actionstr = $action . ' '
                    . (string)$action_data[1][0]->{$action_data[1][1]}($this);
                $send_action = FALSE;
            } else {
                $actionstr = $action . ' ';
                $send_action = FALSE;
            }
            $action_menu .= '<div class="action_menu" '
                . 'onMouseOver="this.className=\'action_menu_selected\'" '
                . 'onMouseOut="this.className=\'action_menu\'" '
                . 'onClick="' . (FALSE === $send_action
                ? "_gel('dpaction').value = '$actionstr'"
                : 'send_action2server(\'' . $actionstr . "')") . '">'
                . $action_data[0] . "</div>\n";
        }
        get_current_dpuser()->tell('<actions id="' . $this->uniqueId
            . '">' . $action_menu . '</actions>');
    }

    /**
     * Gets actions which can be performed on this object, for action menu
     *
     * Gets an array with actions which can be performed on this object, so
     * we can make a menu when hovering over the object image with the mouse.
     * This includes actions that are defined by other objects but appear in
     * this objects action menu.
     *
     * An array is returned, empty if there are no menu actions found, with
     * key-value pairs. Each key is the (first) verb associated with the action
     * pair, each value an array of two elements: the menu label, the "operant"
     * as defined with addAction.
     *
     * @param      object    &$user      user getting the menu
     * @return     array     array with menu actions, can be empty
     */
    function &getTargettedActions(&$user)
    {
        /* Gets menu actions on this object defined by this object */
        $actions = $this->_getTargettedActions($this, $user);

        /*
         * If we're getting menu actions for ourselves, gather actions from our
         * inventory and environment:
         */
        if ($user === $this || 1) {
            /*
             * Gets menu actions on this object defined by objects in inventory:
             */
            $inv = $this->getInventory();
            foreach ($inv as &$ob) {
                $actions = array_merge($actions,
                    $this->_getTargettedActions($ob, $user));
            }

            /*
             * Gets menu actions on this object defined by objects in this
             * object's environment:
             */
            $env = $this->getEnvironment();
            if (FALSE === $env) {
                return array();
            }

            $inv = $env->getInventory();
            foreach ($inv as &$ob) {
                if ($ob !== $this) {
                    $actions = array_merge($actions,
                        $this->_getTargettedActions($ob, $user));
                }
            }

            /*
             * Gets menu actions on this object defined by the object's
             * environment:
             */
            $actions = array_merge($actions,
                $this->_getTargettedActions($env, $user));
        }

        return $actions;
    }

    /**
     * Gets actions that can be performed on a given object by a given user
     *
     * An array is returned with each element a pair consisting of:
     *     verb => array(menulabel, operant)
     * with operant one of DP_ACTION_OPERANT_ from
     * dpuniverse/include/actions.php, for example:
     * 'read' => array('read me!', DP_ACTION_OPERANT_MENU)
     *
     * @access     private
     * @param      object    &$ob        target of actions
     * @param      object    &$user      performer of actions
     * @return     array   array with actions, can be empty
     */
    function &_getTargettedActions(&$ob, &$user)
    {
        $ob_actions = $ob->getActions();
        $moreactions = array();

        $is_registered = isset($user->isRegistered)
            && $this->isRegistered === TRUE;
        $is_admin = isset($user->isAdmin) && $user->isAdmin === TRUE;

        foreach ($ob_actions as $verb => &$a_d) {
            foreach ($a_d as $action_data) {
                /*if (FALSE !== in_array($action_data[1], $actions)) {
                    continue;
                }
                if (FALSE !== in_array($action_data[1], $moreactions)) {
                    continue;
                }*/

                if ($action_data[3] === DP_ACTION_TARGET_NONE) {
                    continue;
                }

                $compare = 0;

                if ($ob === $this) {
                    $compare = $compare | DP_ACTION_TARGET_SELF;
                } elseif (isset($this->isLiving) && FALSE !== $this->isLiving) {
                    $compare = $compare | DP_ACTION_TARGET_LIVING;
                } elseif (FALSE !== ($this_env = $this->getEnvironment())
                        && FALSE !== ($ob_env = $ob->getEnvironment())
                        && $this_env === $ob_env) {
                    $compare = $compare | DP_ACTION_TARGET_OBJENV;
                } elseif (FALSE !== ($this_env = $this->getEnvironment())
                        && $this_env === $ob) {
                    $compare = $compare | DP_ACTION_TARGET_OBJINV;
                }

                if (!($action_data[3] & $compare)) {
                    continue;
                }
                if ($action_data[5] & DP_ACTION_SCOPE_SELF) {
                    if ($user !== $ob) {
                        continue;
                    }
                }
                if ($action_data[5] & DP_ACTION_SCOPE_INVENTORY) {
                    if ($user->getEnvironment() !== $ob) {
                        continue;
                    }
                }
                if ($action_data[5] & DP_ACTION_SCOPE_ENVIRONMENT) {
                    if ($user->getEnvironment() !== $ob->getEnvironment()) {
                        continue;
                    }
                }
                /*if (!($action_data[3] | DP_ACTION_SCOPE_ALL)) {
                    continue;
                }*/
                if ($action_data[4] === DP_ACTION_AUTHORIZED_REGISTERED) {
                    if (FALSE === $is_registered) {
                        continue;
                    }
                } elseif ($action_data[4] === DP_ACTION_AUTHORIZED_ADMIN) {
                    if (FALSE === $is_admin) {
                        continue;
                    }
                }
                $moreactions[$verb] = array($action_data[0], $action_data[2]);
            }
        }

        return $moreactions;
    }

    /**
     * Creates a new image map area which can be used by actions
     *
     * Creates an area for the image map $mapName. Make sure the right <map> and
     * <area> HTML is included when this object is viewed. The image should have
     * the following tag:
     *     usemap="#<map name>"
     *
     * The id given with $mapAreaId can be used by others methods, such as
     * addItem or addAction to refer to this area.
     *
     * The shape of the image map given with $mapAreaShape is one of:
     *     circle, poly, rect
     * See your HTML reference for a full explanation of these types.
     *
     * The coordinates of the shape should be given with $mapAreaCoords, for
     * example, the coordinates for a rectangle:
     *     128,132,241,179
     * See your HTML reference for a full explanation about coordinates.
     *
     * An optional tooltip can be shown when hoveirng over this area with the
     * mouse with $mapAreaAlt.
     *
     * @param      string    $mapName        name of image map
     * @param      string    $mapAreaId      id of area in this map
     * @param      string    $mapAreaShape   cirlce, poly, rect
     * @param      string    $mapAreaCoords  x,y,... (depends on shape)
     * @param      string    $mapAreaAlt     mouse tooltip for this area
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
     * Gets actions that can be performed on a given object by a given user
     *
     * @access     private
     * @param      object    &$ob        target of actions
     * @param      object    &$user      performer of actions
     * @return     array   array with actions, can be empty
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
     * Gets actions that can be performed on a given object by a given user
     *
     * @access     private
     * @param      object    &$ob        target of actions
     * @param      object    &$user      performer of actions
     * @return     array   array with actions, can be empty
     * @since      DutchPIPE 0.2.0
     */
    function addMapAreaAction($mapAreaId, $actionMenuTitle, $action)
    {
        if (!isset($this->mMapAreaActions[$mapAreaId])) {
            $this->mMapAreaActions[$mapAreaId] = array();
        }
        $new_area_data = array($actionMenuTitle, $action);
        foreach ($this->mMapAreaActions[$mapAreaId] as $area_data) {
            if ($area_data === $new_area_data) {
                return;
            }
        }
        $this->mMapAreaActions[$mapAreaId][] = $new_area_data;
    }

    /**
     * Gets actions that can be performed on a given object by a given user
     *
     * @access     private
     * @param      object    &$ob        target of actions
     * @param      object    &$user      performer of actions
     * @return     array   array with actions, can be empty
     * @since      DutchPIPE 0.2.0
     */
    function removeMapAreaAction($mapAreaId, $actionMenuTitle, $action)
    {
        if (!isset($this->mMapAreaActions[$mapAreaId])) {
            return;
        }
        foreach ($this->mMapAreaActions[$mapAreaId] as $i => &$area_data) {
            if ($area_data[0] === $actionMenuTitle
                    && $area_data[1] === $action) {
                unset($this->mMapAreaActions[$mapAreaId][$i]);
                return;
            }
        }
    }

    /**
     * Gets actions that can be performed on a given object by a given user
     *
     * @access     private
     * @param      object    &$ob        target of actions
     * @param      object    &$user      performer of actions
     * @return     array   array with actions, can be empty
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
     * Gets actions that can be performed on a given object by a given user
     *
     * @access     private
     * @param      object    &$ob        target of actions
     * @param      object    &$user      performer of actions
     * @return     array   array with actions, can be empty
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
                $alt = dptext('Click for menu');
            }
            $rval .= "<area shape=\"{$map_area_data[0]}\" "
                . "coords=\"{$map_area_data[1]}}\" "
                . "href=\"javascript:void(0)\" "
                . "alt=\"{$alt}\" title=\"{$alt}\" "
                . "onClick=\"get_map_area_actions('{$mapName}', "
                . "'{$map_area_id}', '{$this->uniqueId}', event)\" style=\"cursor: pointer\" />\n";
        }
        $rval .= '</map>';

        return $rval;
    }

    /**
     * Gets actions that can be performed on a given object by a given user
     *
     * @access     private
     * @param      object    &$ob        target of actions
     * @param      object    &$user      performer of actions
     * @return     array   array with actions, can be empty
     * @since      DutchPIPE 0.2.0
     */
    function _getActionsMenu(&$actions)
    {
        $action_menu = '';
        foreach ($actions as $menu => $fullaction) {
            $action_menu .= '<div class="action_menu" '
                . 'onMouseOver="this.className=\'action_menu_selected\'" '
                . 'onMouseOut="this.className=\'action_menu\'" '
                . 'onClick="send_action2server(\'' . $fullaction . '\')">'
                . $menu . "</div>\n";
        }
        get_current_dpuser()->tell('<actions id="' . $this->uniqueId
            . '">' . $action_menu . '</actions>');
    }

    /**
     * Gets actions that can be performed on a given object by a given user
     *
     * @access     private
     * @param      object    &$ob        target of actions
     * @param      object    &$user      performer of actions
     * @return     array   array with actions, can be empty
     * @since      DutchPIPE 0.2.0
     */
    function getMapAreaActionsMenu()
    {
        if (!isset(get_current_dpuser()->_GET['map_name'])
                || !isset(get_current_dpuser()->_GET['map_area_id'])) {
            return;
        }

        $map_name = get_current_dpuser()->_GET['map_name'];
        $map_area_id = get_current_dpuser()->_GET['map_area_id'];

        $actions = array();

        if (!isset($this->mMapAreaActions[$map_area_id])) {
            return;
        }
        foreach ($this->mMapAreaActions[$map_area_id] as
                $action_data) {
            $actions[$action_data[0]] = $action_data[1];
        }
        $sz =  count($actions);
        if (0 === $sz) {
            return;
        }
        if (1 === $sz) {
            get_current_dpuser()->performAction($action_data[1]);
            return;
        }
        $this->_getActionsMenu($actions);
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
        if (strlen($action) >= 1 && substr($action, 0, 1) == "'") {
            $say = dptext('say');
            $action = strlen($action) == 1 ? $say : $say . ' '
                . substr($action, 1);
        } elseif (strlen($action) >= 1 && substr($action, 0, 1) == '"') {
            $tell = dptext('tell');
            $action = strlen($action) == 1 ? $tell : $tell . ' '
                . substr($action, 1);
        }

        if (FALSE !== ($x = strpos($action, ' '))) {
            $verb = substr($action, 0, $x);
            $noun = trim(substr($action, $x));
            if (!strlen($noun)) {
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
            $action_data = $this->mActions[$action][key($this->mActions[$verb])];
        } elseif (isset($this->mActionAliases[$verb])) {
            reset($this->mActionAliases[$verb]);
            $alias =& $this->mActionAliases[$verb][key($this->mActionAliases[$verb])];
            $action_data = $this->mActions[$alias[0]][$alias[1]];
        } elseif (isset($this->mActionAliases[$action])) {
            $is_phrase = TRUE;
            reset($this->mActionAliases[$verb]);
            $alias =& $this->mActionAliases[$action][key($this->mActionAliases[$verb])];
            $action_data = $this->mActions[$alias[0]][$alias[1]];
        } else {
            return FALSE;
        }

        if ($action_data[5] & DP_ACTION_SCOPE_SELF) {
            if ($living !== $this) {
                return FALSE;
            }
        }
        if ($action_data[5] & DP_ACTION_SCOPE_INVENTORY) {
            if ($living->getEnvironment() !== $this) {
                return FALSE;
            }
        }
        if ($action_data[5] & DP_ACTION_SCOPE_ENVIRONMENT) {
            if ($living->getEnvironment() !== $this->getEnvironment()) {
                return FALSE;
            }
        }
        /*if (!($action_data[3] | DP_ACTION_SCOPE_ALL)) {
            continue;
        }*/
        if ($action_data[4] === DP_ACTION_AUTHORIZED_REGISTERED) {
            if (FALSE === $living->isRegistered) {
                return FALSE;
            }
        } elseif ($action_data[4] === DP_ACTION_AUTHORIZED_ADMIN) {
            if (FALSE === $living->isAdmin) {
                return FALSE;
            }
        }


        return (bool)$this->{$action_data[1]}(!$is_phrase ? $verb : $action, $noun);
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
     * If no users or special object using the isNoCleanup property are present,
     * the object and all of its inventory is destroyed.
     *
     * @since      DutchPIPE 0.2.0
     */
    function handleCleanup()
    {
        foreach ($this->mCheckPresentObjects as $i => &$ob) {
            if ($ob->getEnvironment() !== $this) {
                echo sprintf(dptext("handleCleanup() called in %s: not removed",
                    $this->getTitle()));
                return;
            }
        }

        $inv = $this->getInventory();
        foreach ($inv as &$ob) {
            if ($ob->isUser || $ob->isNoCleanup) {
                echo sprintf(dptext("handleCleanup() called in %s: not removed",
                    $this->getTitle()));
                return;
            }
        }

        echo sprintf(dptext("handleCleanup() called in %s: removing\n",
            $this->getTitle()));
        $this->removeDpObject();
    }

    /**
     * Can we be dragged on the screen by the given user?
     *
     * Experimental mouse dragging of objects.
     *
     * @param      object    $by_who     subject that wants to drag us
     * @return     boolean   TRUE if $by_who may drag us, FALSE otherwise
     * @since      DutchPIPE 0.2.0
     */
    function isDraggable($by_who)
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
                    $ob->tell(sprintf(dptext("%s moves %s around.<br />"),
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
     * Can we be dragged on the screen by the given user?
     *
     * Experimental mouse dragging of objects.
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
     * @since      DutchPIPE 0.2.0
     */
    function getCredits()
    {
        return $this->getHeapAmount('isCredits');
    }
}
?>
