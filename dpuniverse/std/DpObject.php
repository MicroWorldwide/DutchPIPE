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
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: DpObject.php 2 2006-05-16 00:20:42Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */

/**
 * Gets moving constants
 */
require_once(DPUNIVERSE_BASE_PATH . '/include/move.php');

/**
 * Gets events constants
 */
require_once(DPUNIVERSE_BASE_PATH . '/include/events.php');

/**
 * Gets action constants
 */
require_once(DPUNIVERSE_BASE_PATH . '/include/actions.php');

/**
 * Gets title type constants
 */
require_once(DPUNIVERSE_BASE_PATH . '/include/title_types.php');

/**
 * The standard object which is built upon by all other objects
 *
 * @package    DutchPIPE
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: @package_version@
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 */
class DpObject
{
    /**
     * @var         string    Unique internal id for this object, 'object#628'
     * @access      private
     */
    private $mUniqueId;

    /**
     * @var         array     Public ids for this object, 'beer', 'cool beer'
     * @access      private
     */
    private $mIds = array();

    /**
     * @var         string    Title for this object, 'Cool, fresh beer'
     * @access      private
     */
    private $mTitle;

    /**
     * @var         int       Type of title, 'A beer', 'The beer', etc.
     * @access      private
     */
    private $mTitleType = DPUNIVERSE_TITLE_TYPE_INDEFINITE;

    /**
     * @var         string    Path to the avatar or object image
     * @access      private
     */
    private $mTitleImg;

    /**
     * @var         mixed     The long description or page content
     * @access      private
     */
    private $mBody;

    /**
     * @var         array     Actions defined by this object, see addAction
     * @access      private
     */
    private $mActions = array();

    /**
     * @var         array     Aliases used for actions, e.g. 'exa' for 'examine'
     * @access      private
     */
    private $mActionAliases = array();

    /**
     * @var         type      Generic property array for dynamic, simple vars
     * @access      private
     */
    private $mProperties = array();

    /**
     * Creates this object
     *
     * Calls the method 'createDpObject' in this object, if it exists.
     *
     * :WARNING: Use get_current_dpuniverse()->newDpObject only to create
     * objects, do not use 'new'. DpUniverse will pass a unique id for this
     *
     * @param   string  $unique_id  A unique id for this object
     */
    function __construct($unique_id)
    {
        /* This method may only be called once, at creation time */
        if (FALSE !== $this->getProperty('creation_time')) {
            return;
        }

        /* Standard setup calls to set some default values */
        $this->setUniqueId('object#' . $unique_id);
        $this->addId('object');
        $this->setTitle('An initalized object',
            DPUNIVERSE_TITLE_TYPE_INDEFINITE,
            DPUNIVERSE_IMAGE_URL . 'object.gif');
        $this->setBody('You see nothing special.<br />');
        $this->addProperty('display_mode', 'graphical');
        $this->addProperty('creation_time', time());

        /* Call CreateDpObjects for objects that extend on this object */
        if (method_exists($this, 'createDpObject')) {
            $this->createDpObject();
        }
    }

    /**
     * Destructs the object
     */
    function __destruct()
    {
        echo "__destruct() called in object.\n";
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
        /* TODO: Move all users somewhere so they don't get destroyed */
        $inv = $this->getInventory();
    }

    /**
     * Resets the object
     *
     * Calls the method 'resetDpObject' in this object, if it exists.
     */
    function __reset()
    {
        if (method_exists($this, 'resetDpObject')) {
            $this->resetDpObject();
        }
    }

    /**
     * Removes this object
     *
     * The object is destroyed and no longer part of the universe.
     */
    function removeDpObject()
    {
        echo "removeDpObject() called in object.\n";
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
     * @param   mixed   $target_ob  path or object to move into to
     * @param   boolean $simple     skip some checks
     * @return  int                 FALSE for success, an error code for failure
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

        if ((!isset($this->_GET) || !isset($this->_GET['getdivs']))
                && FALSE !== ($body = $target_ob->getAppearance(0, TRUE, NULL,
                    $this->getProperty('display_mode')))) {
            $this->tell('<div id="dppage">' . $body . '</div>');
        }

        return FALSE;
    }

    /**
     * Checks if an object is present in our inventory
     *
     * If $what is a string, searches for an object with that id. If $what is an
     * object, searches for the object. Searches are done in the inventory of
     * this object.
     *
     * @param      mixed     $what       string (id) or object to search for
     * @return     boolean   TRUE if $what is in our inventory, false otherwise
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
     * @param      int d     $secs       delay in seconds
     */
    function setTimeout($method, $secs)
    {
        get_current_dpuniverse()->setTimeout($this, $method, $secs);
    }

    /**
     * Tells data (message, window, location, ...) to this object
     *
     * Tells a message to this object, for instance a chat line. Handled by
     * tell() in DpUser.php and DpNpc.php, so other objects do not hear anything
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
     * @param      string    $id         uique internal id for this object
     */
    private function setUniqueId($id)
    {
        $this->mUniqueId = $id;
    }

    /**
     * Gets the unique id for this object
     *
     * @return     string    the unique id for this object
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
     */
    function AddId($id)
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
     */
    function RemoveId($id)
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
     * Returns the array of ids for this object, or an empty array for no ids
     *
     * @return     array     array of name strings
     */
    function getIds()
    {
        return $this->mIds;
    }

    /**
     * Checks if the given id is a valid id for this object
     *
     * @return     bool      TRUE if the id is valid, FALSE otherwise
     */
    function isId($id)
    {
        return strlen($id) && (isset($this->mIds[$id = strtolower($id)])
            || $id == strtolower($this->getTitle()))
            || $id == $this->getUniqueId();
    }

    /**
     * Sets the title for this object used as object labels, page titles, etc.
     *
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
     * The $title should be a short description like "barkeeper" without "a"
     * or "the" in front.
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
     * DPUNIVERSE_TITLE_TYPE_INDEFINITE - the title is indefinite, "a barkeeper"
     * DPUNIVERSE_TITLE_TYPE_DEFINITE - the title is definite, "the hulk"
     * DPUNIVERSE_TITLE_TYPE_NAME - the title is a name, "Lennert"
     * DPUNIVERSE_TITLE_TYPE_PLURAL - the title is plural, "sweets" (not yet
     * implemented)
     *
     * @param      string    $title      short description, "barkeeper"
     * @param      string    $type       noun type, use the constants above
     * @param      string    $title_img  URL to avatar or object image
     */
    public function setTitle($title, $type = FALSE, $title_img = FALSE)
    {
        $this->mTitle = $title;

        if (FALSE !== $type) {
            $this->mTitleType = $type;
        }

        if (FALSE !== $title_img) {
            $this->mTitleImg = $title_img;
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
     * @return     string                the object's title
     */
    public function getTitle($type = NULL)
    {
        switch ($type) {
        case DPUNIVERSE_TITLE_TYPE_INDEFINITE:
            switch ($this->getTitleType()) {
            case DPUNIVERSE_TITLE_TYPE_DEFINITE:
                return 'the ' . (string)$this->mTitle;
            case DPUNIVERSE_TITLE_TYPE_NAME: case DPUNIVERSE_TITLE_TYPE_PLURAL:
                return (string)$this->mTitle;
            default:
                return (FALSE === strpos('aeioux', strtolower($this->mTitle{0}))
                    ? 'a' : 'an') . ' ' . (string)$this->mTitle;
            }
        case DPUNIVERSE_TITLE_TYPE_DEFINITE:
            switch ($this->getTitleType()) {
            case DPUNIVERSE_TITLE_TYPE_NAME:
                return (string)$this->mTitle;
            default:
                return 'the ' . (string)$this->mTitle;
            }
        default:
            return (string)$this->mTitle;
        }
    }

    /**
     * Sets the type of this object's title
     *
     * See: include/title_types.php
     *
     * @param      string    $type       noun type, use the constants above
     *
     */
    public function setTitleType($titleType)
    {
        $this->mTitleType = $titleType;
    }

    /**
     * Gets the type of this object's title
     *
     * See: include/title_types.php
     */
    public function getTitleType()
    {
        return $this->mTitleType;
    }

    /**
     * Sets URL to the avatar or other image represeting this object
     *
     * @param      string    $titleImg   URL to avatar or object image
     */
    public function setTitleImg($titleImg)
    {
        $this->mTitleImg = $titleImg;
    }

    /**
     * Gets URL to the avatar or other image represeting this object
     */
    public function getTitleImg()
    {
        return $this->mTitleImg;
    }

    /**
     * Sets the HTML content of this object.
     *
     * When a single argument is given, sets the HTML content to the given text.
     *
     * Pairs of arguments can be given to set the content in other ways, with
     * the second argument the type, and the first the data (what the data is
     * depends on the type).
     *
     * Types are: string (default, raw data), file (read content of given
     * filename)
     *
     * Examples:
     * $this->setBody('Hello world');
     * $this->setBody('helloworld.html', 'file');
     * $this->setBody('Prefix', 'string', 'helloworld.html', 'file', 'Postfix',
     *     'string')
     *
     * @param       string    $str        Content data
     * @param       string    $type       Content type
     * @see         getBody, getBaseLong
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
     */
    public function getBody()
    {
        if (!is_array($this->mBody)) {
            return (string)$this->mBody;
        }

        $rval = '';
        foreach ($this->mBody as $type => &$data) {
            if ($type === 'text') {
                $rval .= $data;
            }
            elseif ($type === 'file') {
                $tmp = file_get_contents(DPUNIVERSE_BASE_PATH . $data);

                if (FALSE !== $tmp) {
                    $rval .= $tmp;
                }
            } elseif ($type === 'url') {
                /* Experimental, ignore */
                echo "Getting mailman contents\n";

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
        }
        return $rval;
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
     * @param      object    $from            expiremental
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
                if (FALSE === $user) {
                    $titlebar = '<div id="titlebar">'
                        . $this->getNavigationTrailHtml() . '</div>';
                } else {
                    $login_link = FALSE === $user->getProperty("is_registered")
                        ? '<a href="/dpclient.php?location='
                        . DPUNIVERSE_PAGE_PATH. 'login.php" style='
                        . '"padding-left: 4px">Login/register</a>'
                        : $login_link = '<a href="/dpclient.php?location='
                        . DPUNIVERSE_PAGE_PATH . 'login.php&amp;act=logout" '
                        . 'style="padding-left: 4px">Logout</a>';
                    $titlebar = '<div id="titlebar"><div id="titlebarleft">' .
                        $this->getNavigationTrailHtml() . '</div><div id='
                        . '"titlebarright">&#160;Welcome <span id="username">'
                        . $user->getTitle() . '</span> <span id="loginlink">'
                        . $login_link . '</span>&#160;&#160;&#160;&#160;'
                        . '<img id="butbottom" src="/images/bottom.gif" '
                        . 'align="absbottom" width="11" height="11" border="0" '
                        . 'alt="Go to Bottom" title="Go to Bottom" '
                        . 'onClick="_gel(\'action\').focus(); '
                        . 'scroll(0, 999999)" /></div></div>';
                }
            }
            $body = '<div id="' . $elementId . '"><div id="' . $elementId . '_inner1">'
                . $titlebar . '<div class="' . $elementId . '_inner2">' . ($displayTitlebar === -1 ? ''
                : $this->getBody() . '<br />');

            $inventory = $this->getAppearanceInventory($level, $include_div, $from,
                $displayMode);

            if (TRUE === $this->getProperty('is_living')) {
                return $body
                    . (get_current_dpobject() && get_current_dpobject() === $this
                    ? 'You are' : ucfirst($this->getTitle(
                    DPUNIVERSE_TITLE_TYPE_DEFINITE)) . ' is')
                    . ' carrying:<br />'
                    . ($inventory == '' ? 'Nothing' : $inventory)
                    . '</div></div></div>';
            }
            return $body . $inventory . '</div></div></div>';
        } elseif (1 === $level) {
            if (is_null($from)) {
                $from = $user;
            }

            if ($displayMode === 'graphical' && isset($this->mTitleImg)) {
                $title_img = '<img src="' . $this->mTitleImg
                    . '" border="0" alt="" style="cursor: pointer" '
                    . 'onClick="get_actions(\''
                    . $this->getUniqueId() . '\', event)" /><br />'
                    . ucfirst($this->getTitle(DPUNIVERSE_TITLE_TYPE_INDEFINITE));
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

    function getAppearanceInventory($level = 0, $include_div = TRUE,
            $from = NULL, $displayMode = 'abstract')
    {
        $inv = $this->getInventory();
        $inventory = '';
        foreach ($inv as &$ob) {
            $inventory .= $ob->getAppearance($level + 1, $include_div, $from,
                $displayMode);
        }
        return $inventory == '' ? '' : "<div id=\"dpinventory\"><div id=\"{$this->getUniqueId()}\">$inventory</div></div>";
    }

    /**
     * Adds a property with the given name
     *
     * Sets the value of the property to TRUE if no value was given, or
     * to the given value.
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
     */
    public function getProperty($propertyName)
    {
        return !isset($this->mProperties[$propertyName]) ? FALSE
            : $this->mProperties[$propertyName];
    }

    /**
     * Gets all properties set in this object
     */
    public function getProperties()
    {
        return $this->mProperties;
    }

    /**
     * Gets a HTML navigation trail for this object
     *
     * By default, a 'Home' link is always present.
     */
    function getNavigationTrailHtml()
    {
        return '<div id="navlink">' . DPUNIVERSE_NAVLOGO . '</div>';
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
                . 'onClick="' . (FALSE === $send_action ?
                "_gel('action').value = '$actionstr'" : 'send_action2server(\''
                . $actionstr . "')") . '">' . $action_data[0] . "</div>\n";
        }
        get_current_dpuser()->tell('<actions id="' . $this->getUniqueId()
            . '">' . $action_menu . '</actions>');
    }

    /**
     * Gets an array with actions which can be performed on this object, so
     * we can make a menu when hovering over the object image with the mouse.
     * This includes actions that are defined by other objects but appear in
     * this objects action menu.
     */
    function &getTargettedActions(&$from)
    {
        /* Gets menu actions on this object defined by this object */
        $actions = $this->_getTargettedActions($actions, $this, $from);

        /*
         * If we're getting menu actions for ourselves, gather actions from our
         * inventory and environment:
         */
        if ($from === $this || 1) {
            /*
             * Gets menu actions on this object defined by objects in inventory:
             */
            $inv = $this->getInventory();
            foreach ($inv as &$ob) {
                $actions = array_merge($actions,
                    $this->_getTargettedActions($actions, $ob, $from));
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
                        $this->_getTargettedActions($actions, $ob, $from));
                }
            }

            /*
             * Gets menu actions on this object defined by the object's
             * environment:
             */
            $actions = array_merge($actions,
                $this->_getTargettedActions($actions, $env, $from));
        }

        return $actions;
    }

    function &_getTargettedActions(&$actions, &$ob, &$from)
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
                if ($from !== $ob) {
                    continue;
                }
            }
            if ($action_data[5] & DP_ACTION_SCOPE_INVENTORY) {
                if ($from !== $ob->getEnvironment()) {
                    continue;
                }
            }
            if ($action_data[5] & DP_ACTION_SCOPE_ENVIRONMENT) {
                if ($from->getEnvironment() !== $ob->getEnvironment()) {
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

    final public function performActionSubject($action, $user)
    {
        if (strlen($action) >= 1 && substr($action, 0, 1) == "'") {
            $action = strlen($action) == 1 ? 'say' : 'say '
                . substr($action, 1);
        } elseif (strlen($action) >= 1 && substr($action, 0, 1) == '"') {
            $action = strlen($action) == 1 ? 'tell' : 'tell '
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
                    && $user === $this) {
                /* Try inventory and environment */
                $inv = $this->getInventory();
                foreach ($inv as &$ob) {
                    if (TRUE === (bool)$ob->performActionSubject($action,
                            $user)) {
                        return TRUE;
                    }
                }
                $env = $this->getEnvironment();
                $inv = $env->getInventory();
                foreach ($inv as &$ob) {
                    if ($ob !== $this && TRUE ===
                            (bool)$ob->performActionSubject($action, $user)) {
                        return TRUE;
                    }
                }
                return (boolean)$env->performActionSubject($action, $user);
            }
            return FALSE;
        }
        $action_data = isset($this->mActions[$verb]) ? $this->mActions[$verb]
            : $this->mActions[$this->mActionAliases[$verb]];

        return (bool)$this->{$action_data[1]}($verb, $noun);
    }
}
?>
