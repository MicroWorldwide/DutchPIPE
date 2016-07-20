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
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: DpObject.php 97 2006-08-11 21:56:59Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */

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
 * The standard object which is built upon by all other objects
 *
 * @package    DutchPIPE
 * @subpackage dpuniverse_std
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: @package_version@
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */
class DpObject
{
    /**
     * Unique internal id for this object, 'object#628'
     *
     * @var        string
     * @access     private
     */
    private $mUniqueId;

    /**
     * Public ids for this object, 'beer', 'cool beer'
     *
     * @var        array
     * @access     private
     */
    private $mIds = array();

    /**
     * Path to non-default template file for this object, if any
     *
     * @var        string
     * @access     private
     */
    private $mTemplateFile;

    /**
     * Title for this object, 'Cool, fresh beer'
     *
     * @var        string
     * @access     private
     */
    private $mTitle;

    /**
     * Definite title, 'The cool, fresh beer'
     *
     * @var        string
     * @access     private
     */
    private $mTitleDefinite;

    /**
     * Indefinite title, 'A cool, fresh beer'
     *
     * @var        string
     * @access     private
     */
    private $mTitleIndefinite;

    /**
     * Type of title, 'A beer', 'The beer', etc.
     *
     * @var        int
     * @access     private
     */
    private $mTitleType = DPUNIVERSE_TITLE_TYPE_INDEFINITE;

    /**
     * Path to the avatar or object image
     *
     * @var        string
     * @access     private
     */
    private $mTitleImg = NULL;

    /**
     * The long description or page content
     *
     * @var        mixed
     * @access     private
     */
    private $mBody;

    /**
     * Actions defined by this object, see addAction
     *
     * @var        array
     * @access     private
     */
    private $mActions = array();

    /**
     * Aliases used for actions, e.g. 'exa' for 'examine'
     *
     * @var        array
     * @access     private
     */
    private $mActionAliases = array();

    /**
     * Generic property array for dynamic, simple vars
     *
     * @var         type
     * @access      private
     */
    private $mProperties = array();

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
        if (FALSE !== $this->getProperty('creation_time')) {
            return;
        }
        $this->addProperty('reset_time', $reset_time);
        $this->addProperty('location', $location);
        if (FALSE !== $sublocation) {
            echo "adding property sublocation\n";
            $this->addProperty('sublocation', $sublocation);
        } else {
            echo "no sublocation\n";
        }

        /* Standard setup calls to set some default values */
        $this->setUniqueId('object#' . $unique_id);
        $this->addId(dptext('object'));
        $this->setTitleImg(DPUNIVERSE_IMAGE_URL . 'object.gif');
        $this->setBody(dptext('You see nothing special.<br />'));
        $this->addProperty('display_mode', 'graphical');
        $this->addProperty('creation_time', time());

        /* Call CreateDpObjects for objects that extend on this object */
        $this->createDpObject();

        if (!isset($this->mTitle)) {
            $this->setTitle(dptext('initialized object'));
            $this->setTitleDefinite(dptext('the initialized object'));
            $this->setTitleIndefinite(dptext('an initialized object'));
        }
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
        echo sprintf(dptext("__destruct() called in object %s (%s).\n"),
            $this->getUniqueId(),
            $this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE));

        if (FALSE !== ($env = $this->getEnvironment())) {
            if (method_exists($env, 'event')) {
                $env->event(EVENT_LEFT_INV, $this, 0);
            }
            if (method_exists($this, 'event')) {
                $this->event(EVENT_CHANGED_ENV, $env, 0);
            }
        }
        elseif (method_exists($this, 'event')) {
            $this->event(EVENT_CHANGED_ENV, 0);
        }

        if (method_exists($this, 'event')) {
            $this->event(EVENT_DESTROYING_OBJ);
        }
        /* :TODO: Move all users somewhere so they don't get destroyed */
        $inv = $this->getInventory();
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
        echo dptext("removeDpObject() called in object.\n");
        $env = $this;
        while (FALSE !== $env->getEnvironment()) {
            $env = $env->getEnvironment();
        }
        $env->tell('<removeDpElement id="' . $this->getUniqueId()
            . '">&#160;</removeDpElement>');

        $this->addProperty('is_removed');
        get_current_dpuniverse()->removeDpObject($this);
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

        $curr_env = $this->getEnvironment();

        /* Do more checking? */
        if (FALSE === $simple) {
            /* Checks if the living carrying this object may not drop it: */
            if (FALSE !== $curr_env && $curr_env->getProperty('is_living')
                    && FALSE !== $curr_env->getProperty('no_drop')) {
                return E_MOVEOBJECT_NODROP;
            }

            /* Checks if target is neither a page nor a living */
            if (FALSE !== $target_ob->getEnvironment() &&
                    FALSE === $target_ob->getProperty('is_living')) {
                /*
                 * Checks if thie object may be inserted into containers, for
                 * instance, drinks could disallow this, to prevent 'spilling':
                 */
                if (method_exists($this, 'preventInsert')
                        && (bool)$this->preventInsert()) {
                    return E_MOVEOBJECT_NOSRCINS;
                }

                /* Checks if the target is a container, or fail */
                if (FALSE === ((bool)$target_ob->getProperty('is_container'))) {
                    return E_MOVEOBJECT_NODSTINS;
                }
            }

            /* Checks if the object may be taken by livings */
            if ($target_ob->getProperty('is_living')
                    && (bool)$this->getProperty('no_take')) {
                return E_MOVEOBJECT_NOGET;
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
            if (method_exists($this, 'event')) {
                $this->event(EVENT_CHANGED_ENV, $curr_env, $target_ob);
            }
            if (FALSE !== ($curr_env)) {
                $old_page = $curr_env;
                while (FALSE !== $old_page->getEnvironment()) {
                    $old_page = $old_page->getEnvironment();
                }
                if (method_exists($curr_env, 'event')) {
                    $curr_env->event(EVENT_LEFT_INV, $this, $target_ob);
                }
            }
            $new_page = $target_ob;
            while (FALSE !== $new_page->getEnvironment()) {
                $new_page = $new_page->getEnvironment();
            }

            $abstract_class = FALSE === $target_ob->getEnvironment()
                ? 'inventory' : 'inventory2';

            if (isset($old_page) && $old_page === $new_page) {
                $new_page->tell(array(
                    'abstract' => '<moveDpElement id="'
                        . $this->getUniqueId()
                        . '" where="' . $target_ob->getUniqueId()
                        . '" class="' . $abstract_class
                        . '">&#160;</moveDpElement>'));
                if ($target_ob === $new_page) {
                    $new_page->tell(array(
                        'graphical' => '<addDpElement id="'
                            . $this->getUniqueId() . '" where="'
                            . $target_ob->getUniqueId()
                            . '" class="title_img">'
                            . $this->getAppearance(1, FALSE, $new_page,
                            'graphical') . '</addDpElement>'));
                } else {
                    $new_page->tell(array(
                        'graphical' => '<removeDpElement id="'
                        . $this->getUniqueId()
                        . '">&#160;</removeDpElement>'));
                }
            }
            else {
                if (isset($old_page)) {
                    $old_page->tell('<removeDpElement id="'
                        . $this->getUniqueId()
                        . '">&#160;</removeDpElement>');
                }
                $new_page->tell(array('abstract' => '<addDpElement id="'
                    . $this->getUniqueId() . '" where="'
                    . $target_ob->getUniqueId() . '" class="'
                    . $abstract_class . '">' . $this->getAppearance(1, FALSE,
                    $new_page, 'abstract') . '</addDpElement>',
                    'graphical' => '<addDpElement id="'
                    . $this->getUniqueId() . '" where="'
                    . $target_ob->getUniqueId()
                    . '" class="title_img">' . $this->getAppearance(1, FALSE,
                    $new_page, 'graphical') . '</addDpElement>'),
                    $this);
            }
            if (method_exists($target_ob, 'event')) {
                $target_ob->event(EVENT_ENTERED_INV, $this, $curr_env);
            }
            $dest_all = $target_ob->getInventory();
            foreach ($dest_all as &$ob) {
                if (method_exists($ob, 'event')) {
                    $ob->event(EVENT_ENTERED_ENV, $this, $curr_env);
                }
            }
        }

        if (!isset($this->_GET) || !isset($this->_GET['getdivs'])) {
            $body = isset($this->_GET) && isset($this->_GET['ajax'])
                ? $target_ob->getAppearanceInventory(0, TRUE, NULL,
                    $this->getProperty('display_mode'))
                : $target_ob->getAppearance(0, TRUE, NULL,
                    $this->getProperty('display_mode'));
            if (FALSE !== $body) {
                if (!is_null($target_ob->getTemplateFile())) {
                    $this->tell('<xhtml>' . $body . '</xhtml>');
                } else {
                    $this->tell('<div id="'
                        . (isset($this->_GET) && isset($this->_GET['ajax'])
                        ? 'dpinventory' : 'dppage') . '">' . $body . '</div>');
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
     * Sets a unique internal id for this object
     *
     * This method is called by __construct with a string such as 'object#628'
     * to set a unique id. This id is used in HTML output as the id of the DIV
     * element spanning this object's HTML appearance, by the AJAX engine to
     * operate on the object, et cetera.
     *
     * WARNING: Don't call this method. Only __construct should call it.
     *
     * @access     private
     * @param      string    $id         uique internal id for this object
     * @see        getUniqueId(), setIds(), addId(), removeId(), getIds(),
     *             isId()
     */
    private function setUniqueId($id)
    {
        $this->mUniqueId = $id;
    }

    /**
     * Gets the unique id for this object
     *
     * @return     string    the unique id for this object
     * @see        setIds(), addId(), removeId(), getIds(), isId()
     */
    function getUniqueId()
    {
        return $this->mUniqueId;
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
     * @param      string    $id         name string to check
     * @return     bool      TRUE if the id is valid, FALSE otherwise
     * @see        setIds(), addId(), removeId(), getIds(), getUniqueId(),
     */
    function isId($id)
    {
        return strlen($id) && (isset($this->mIds[$id = strtolower($id)])
            || $id == strtolower($this->getTitle()))
            || $id == $this->getUniqueId();
    }

    /**
     * Sets a non-default XHTML template file for this object
     *
     * @param      string    $templateFile Path to a template file
     * @see        getTemplateFile()
     */
    function setTemplateFile($templateFile)
    {
        $this->mTemplateFile = $templateFile;
    }

    /**
     * Gets a non-default XHTML template file for this object, if set
     *
     * @return     string    Path to a template file, or FALSE for not set
     * @see        setTemplateFile()
     */
    function getTemplateFile()
    {
        return $this->mTemplateFile;
    }

    /**
     * Sets the title for this object, "beer", used as for object labels, etc.
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
        $this->mTitle = $title;
        unset($this->mTitleDefinite);
        unset($this->mTitleInDefinite);

        if (FALSE !== $titleType) {
            $this->mTitleType = $titleType;
        }

        if (FALSE !== $titleImg) {
            $this->mTitleImg = $titleImg;
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
                return isset($this->mTitleDefinite) ? $this->mTitleDefinite
                    : sprintf(dptext('the %s'), $this->mTitle);
            case DPUNIVERSE_TITLE_TYPE_NAME: case DPUNIVERSE_TITLE_TYPE_PLURAL:
                return $this->mTitle;
            default:
                if (isset($this->mTitleIndefinite)) {
                    return $this->mTitleIndefinite;
                }
                return (FALSE === strpos(dptext('aeioux'),
                    strtolower($this->mTitle{0}))
                    ? sprintf(dptext('a %s'), $this->mTitle)
                    : sprintf(dptext('an %s'), $this->mTitle));
            }
        case DPUNIVERSE_TITLE_TYPE_DEFINITE:
            switch ($this->getTitleType()) {
            case DPUNIVERSE_TITLE_TYPE_NAME:
                return (string)$this->mTitle;
            default:
                return isset($this->mTitleDefinite) ? $this->mTitleDefinite
                    : sprintf(dptext('the %s'), $this->mTitle);
            }
        default:
            return $this->mTitle;
        }
    }

    /**
     * Sets the type of this object's title
     *
     * For a list of possible types, see: dpuniverse/include/title_types.php
     * You must use one of the constants defined in this file.
     *
     * @param      string    $titleType  noun type, use the constants above
     */
    public function setTitleType($titleType)
    {
        $this->mTitleType = $titleType;
    }

    /**
     * Gets the type of this object's title
     *
     * For a list of possible return values, see:
     * dpuniverse/include/title_types.php
     *
     * @return     int       this object's title type
     */
    public function getTitleType()
    {
        return $this->mTitleType;
    }

    /**
     * Sets URL for the avatar or other image representing this object
     *
     * @param      string    $titleImg   URL for avatar or object image
     */
    public function setTitleImg($titleImg)
    {
        $this->mTitleImg = $titleImg;
    }

    /**
     * Gets URL for the avatar or other image representing this object
     *
     * Returns NULL if no image was set for this object.
     *
     * @return     string    URL for the image representing this object or NULL
     */
    public function getTitleImg()
    {
        return $this->mTitleImg;
    }

    /**
     * Sets the definite title for this object, "the beer"
     *
     * Sets the description starting with "the", for example "the beer".
     * If your site is only in English and you don't plan to support I18N/L10N
     * or the contribute the object to DutchPIPE, you don't need to call this
     * method, as the system can automatically prefix titles with 'the' when
     * needed. Otherwise, you should call it with dptext like this:
     * $ob->setTitleDefinite(dptext('the beer'));
     * This way the system can replace 'the beer' with the proper translation.
     * Some languages have multiple words or other means for "the".
     *
     * Must be called after setTitle, which resets the definite title.
     *
     * @param      string    $title      short definite description, "the beer"
     */
    public function setTitleDefinite($title)
    {
        $this->mTitleDefinite = $title;
    }

    /**
     * Gets the definite title for this object, "the beer"
     *
     * @return     string    the object's definite title
     */
    public function getTitleDefinite()
    {
        return $this->mTitleDefinite;
    }

    /**
     * Sets the indefinite title for this object, "a beer"
     *
     * Sets the description starting with "a" or "an", for example "a beer".
     * If your site is only in English and you don't plan to support I18N/L10N
     * or the contribute the object to DutchPIPE, you don't need to call this
     * method, as the system can automatically prefix titles with 'a' or 'an'
     * when needed, using an educated guess. Otherwise, you should call it with
     * dptext like this:
     * $ob->setTitleDefinite(dptext('a beer'));
     * (or if the educated guess didn't cut it in your single language object:
     * $ob->setTitleDefinite('a beer'); )
     * This way the system can replace 'a beer' with the proper translation.
     * Some languages have just a single word or other means for "a" and "an".
     *
     * Must be called after setTitle, which reset the indefinite title.
     *
     * @param      string    $title      short indefinite description, "a beer"
     */
    public function setTitleIndefinite($title)
    {
        $this->mTitleIndefinite = $title;
    }

    /**
     * Gets the indefinite title for this object, "a beer"
     *
     * @return     string    the object's indefinite title
     */
    public function getTitleIndefinite()
    {
        return $this->mTitleIndefinite;
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
     * @param       string    $body       content data
     * @param       string    $type       content type
     * @see         getBody()
     */
    public function setBody($body, $type = 'text')
    {
        $num_args = func_num_args();
        if (1 == $num_args) {
            $this->mBody = $body;
            return;
        }
        $tmp = array();
        for ($i = 0; $i < $num_args; $i += 2) {
            $tmp[func_get_arg($i + 1)] = func_get_arg($i);
        }
        $this->mBody = $tmp;
    }

    /**
     * Gets the HTML content of this object
     *
     * @return     string    HTML content of this object
     * @see        setBody()
     */
    public function getBody()
    {
        if (!is_array($this->mBody)) {
            return $this->mBody;
        }

        $rval = '';
        foreach ($this->mBody as $type => &$data) {
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
                    $rval .= $tmp;
                }
            } elseif ($type === 'url') {
                /* Experimental, ignore */
                $rval .= $this->getBodyUrl($data);
            }
        }
        return $rval;
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
            $this->setNavigationTrail(array(DPUNIVERSE_NAVLOGO, '/'),
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
        $user = get_current_dpuser();
        $body = $inventory = '';
        $titlebar = '';
        if (0 === $level) {
            if (TRUE === $displayTitlebar) {
                $navtrail = $this->getNavigationTrailHtml();
                if (FALSE === $user) {
                    $titlebar = $navtrail;
                } else {
                    $login_link = FALSE === $user->getProperty("is_registered")
                        ? '<a href="' . DPSERVER_CLIENT_URL . '?location='
                        . DPUNIVERSE_PAGE_PATH. 'login.php" style='
                        . '"padding-left: 4px">' . dptext('Login/register')
                        . '</a>'
                        : $login_link = '<a href="' . DPSERVER_CLIENT_URL
                        . '?location=' . DPUNIVERSE_PAGE_PATH
                        . 'login.php&amp;act=logout" '
                        . 'style="padding-left: 4px">' . dptext('Logout')
                        . '</a>';
                    $bottom = dptext('Go to Bottom');
                    $titlebar = '<div id="titlebarleft">' .
                        $navtrail . '</div><div id='
                        . '"titlebarright">&#160;'. sprintf(
                        dptext('Welcome %s'), '<span id="username">'
                        . $user->getTitle() . '</span>')
                        . ' <span id="loginlink">'
                        . $login_link . '</span>&#160;&#160;&#160;&#160;'
                        . '<img id="butbottom" src="/images/bottom.gif" '
                        . 'align="absbottom" width="11" height="11" border="0" '
                        . 'alt="' . $bottom . '" title="' . $bottom . '" '
                        . 'onClick="_gel(\'dpaction\').focus(); '
                        . 'scroll(0, 999999)" /></div>';
                }
                $titlebar = '<div id="titlebar">' . $titlebar . '</div>';
            }
            $body = '<div id="' . $elementId . '"><div id="' . $elementId
                . '_inner1">' . $titlebar . '<div class="' . $elementId
                . '_inner2">' . ($displayTitlebar === -1 ? ''
                : $this->getBody() . '<br />');

            $inventory = $this->getAppearanceInventory($level, $include_div,
                $from, $displayMode);

            if (TRUE === $this->getProperty('is_living')) {
                return $body . (get_current_dpobject()
                    && get_current_dpobject() === $this
                    ? dptext('You are carrying:')
                    : ucfirst(sprintf(dptext('%s is carrying:'),
                        $this->getTitle(DPUNIVERSE_TITLE_TYPE_DEFINITE))))
                    . '<br />'
                    . ($inventory == '' ? dptext('Nothing') : $inventory)
                    . '</div></div></div>';
            }
            $body .= $inventory . '</div></div></div>';
            if (FALSE !== $displayTitlebar && !is_null($this->mTemplateFile)) {
                $fp = fopen($this->mTemplateFile, 'r');
                $xhtml = fread($fp, filesize($this->mTemplateFile));
                fclose($fp);
                $xhtml = str_replace('{$body}', $body, $xhtml);
                return $xhtml;
            }
            return $body;
        } elseif (1 === $level) {
            if (is_null($from)) {
                $from = $user;
            }

            if ($displayMode === 'graphical' && isset($this->mTitleImg)) {
                $title_img = '<img src="' . $this->mTitleImg
                    . '" border="0" alt="" style="cursor: pointer" '
                    . 'onClick="get_actions(\'' . $this->getUniqueId()
                    . '\', event)" /><br />' . ucfirst($this->getTitle(
                    DPUNIVERSE_TITLE_TYPE_INDEFINITE));
                return FALSE === $include_div ? $title_img
                    : '<div id="' . $this->getUniqueId()
                    . '" class="title_img' . ($from === $this ? '_me' : '')
                    . '">' . $title_img . '</div>';
            }

            $body = $from === $this ? '<span class="me">'
                . ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))
                . '</span>'
                : ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE));

            $inv = $this->getInventory();
            foreach ($inv as &$ob) {
                $inventory .= $ob->getAppearance($level + 1, $include_div,
                    $from, $displayMode);
            }

            return FALSE === $include_div ? $body . $inventory
                : '<div id="' . $this->getUniqueId()
                . '" class="dpobject" onClick="get_actions(\''
                . $this->getUniqueId() . '\')">'
                . $body . $inventory . '</div>';
        } elseif (2 === $level) {
            return FALSE === $include_div
                ? ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))
                : '<div id="' . $this->getUniqueId()
                . '" class="dpobject2" onClick="get_actions(\''
                . $this->getUniqueId() . '\')">'
                . ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE))
                . '</div>';
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
            : "<div id=\"dpinventory\"><div id=\"{$this->getUniqueId()}\">"
                . "$inventory</div></div>";
    }

    /**
     * Adds a property with the given name
     *
     * Sets the value of the property to TRUE if no value was given, or
     * to the given value.
     *
     * @param      array     $propertyName  name of the property to add
     * @param      array     $propertyValue value of the property to add
     */
    public function addProperty($propertyName)
    {
        $this->mProperties[$propertyName] = func_num_args() == 1 ? TRUE
            : func_get_arg(1);
    }

    /**
     * Adds multiple properties with the given array of names
     *
     * Sets the value of the properties to TRUE if no array with values was
     * given, or with each corresponding value in the given array of values.
     *
     * @param      array     $propertyNames  names of the properties to add
     * @param      array     $propertyValues values of the properties to add
     */
    public function setProperties($propertyNames)
    {
        if (func_num_args() == 1) {
            foreach ($propertyNames as $str) {
                $this->addProperty($str);
            }
        }
        else {
            $vals = func_get_arg(1);
            for ($i = 0, $sz = sizeof($propertyNames); $i < $sz; $i++) {
                $this->addProperty($propertyNames[$i], $vals[$i]);
            }
        }
    }

    /**
     * Removes a property with the given name, if it exists
     *
     * @param      string    $propertyName name of the property to remove
     */
    public function removeProperty($propertyName)
    {
        if (isset($this->mProperties[$propertyName])) {
            unset($this->mProperties[$propertyName]);
        }
    }

    /**
     * Gets the value of the property with the given name
     *
     * If the property exists, returns TRUE if the property was set without
     * value, or a value if it was set with a value. Returns FALSE if the
     * property does not exist.
     *
     * @param      string    $propertyName name of the property
     * @return     mixed     value of property, FALSE if it doesn't exist
     */
    public function getProperty($propertyName)
    {
        return !isset($this->mProperties[$propertyName]) ? FALSE
            : $this->mProperties[$propertyName];
    }

    /**
     * Gets all properties set in this object
     *
     * An array is returned with each element of pair consisting of:
     * propertyname => propertyvalue
     * Properties that have been set without a value, will have the
     * propertyvalue TRUE.
     *
     * @return     array     all properties set in this object, can be empty
     */
    public function getProperties()
    {
        return $this->mProperties;
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
        return '<div id="navlink">' . dptext(DPUNIVERSE_NAVLOGO) . '</div>';
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
     * $actionOperant is used when you click to perform an action only, and
     * used to define on who the action operates, in other words, how the verb
     * noun command that is internally formed gets its noun. With the default
     * DP_ACTION_OPERANT_MENU, the noun becomes the object that carries the
     * action in its action menu, for example "object#41", a unique object id.
     * Where a user would type 'read note', clicking would generate
     * 'read object#41'.
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
     * @param      string    $actionMenu       Title of clickable menu item
     * @param      boolean   $actionVerb       Alternative command to type
     * @param      object    $actionMethod     Method called to perform action
     * @param      string    $actionOperant    On who/what does it have effect?
     * @param      boolean   $actionTarget     Where should menu item appear?
     * @param      object    $actionAuthorized Who may perform this action?
     * @param      string    $actionScope      Who sees action to begin with?
     */
    final public function addAction($actionMenu, $actionVerb, $actionMethod,
            $actionOperant = DP_ACTION_OPERANT_MENU,
            $actionTarget = DP_ACTION_TARGET_SELF,
            $actionAuthorized = DP_ACTION_AUTHORIZED_ALL,
            $actionScope = DP_ACTION_SCOPE_ALL)
    {
        if (is_array($actionVerb)) {
            $tmp = $actionVerb[0];
            if (1 < ($sz = sizeof($actionVerb))) {
                for ($i = 0; $i < $sz; $i++) {
                    $this->mActionAliases[$actionVerb[$i]] = $tmp;
                }
            }
            $actionVerb = $tmp;
        }
        $this->mActions[$actionVerb] = array($actionMenu, $actionMethod,
            $actionOperant, $actionTarget, $actionAuthorized, $actionScope);
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
                $actionstr = $action . ' ' . $this->getUniqueId();
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
        get_current_dpuser()->tell('<actions id="' . $this->getUniqueId()
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
     * Gets actions that can be performed an a given object by a given user
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

        $is_registered = $this->getProperty('is_registered');
        $is_admin = $this->getProperty('is_admin');

        foreach ($ob_actions as $verb => &$action_data) {
            /*if (FALSE !== in_array($action_data[1], $actions)) {
                continue;
            }
            if (FALSE !== in_array($action_data[1], $moreactions)) {
                continue;
            }*/

            $compare = 0;

            if ($ob === $this) {
                $compare = $compare | DP_ACTION_TARGET_SELF;
            } elseif (FALSE !== $this->getProperty('is_living')) {
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
                if ($user !== $ob->getEnvironment()) {
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

        return $moreactions;
    }

    /**
     * Tries if a user action can be performed on this object
     *
     * When a user doesn't use the action menu, but gives a command such as
     * 'take beer', or when an NPC performs an action, the system doesn't know
     * which object to operate on.  Therefore, it calls this function in all
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
        if (!isset($this->mActions[$verb])
                && !isset($this->mActionAliases[$verb])) {
            if (TRUE === (boolean)$this->getProperty('is_living')
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
                return (boolean)$env->performActionSubject($action, $living);
            }
            return FALSE;
        }
        $action_data = isset($this->mActions[$verb]) ? $this->mActions[$verb]
            : $this->mActions[$this->mActionAliases[$verb]];

        return (bool)$this->{$action_data[1]}($verb, $noun);
    }
}
?>
