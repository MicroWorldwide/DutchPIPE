<?php
/**
 * Provides 'DpServer' class to answer normal and AJAX requests from web clients
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
 * @version    Subversion: $Id: dpserver.php 16 2006-05-18 22:03:43Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpclient.php, dpuniverse.php
 */

// Shows all possible errors:
error_reporting(E_ALL | E_STRICT);

/**
 * A DutchPIPE server to answer normal and AJAX requests from web clients
 *
 * The DutchPIPE server which can be started and then keeps running while
 * accepting socket connections. Waits for and accepts socket requests from
 * users connected through dpclient.php, and returns output to dpclient.php,
 * which returns it to the end user. If there are 10 users on the site, it will
 * loop through the main code for each user at a time.
 *
 * To answer each request, the server passes the request on to the persistent
 * DbUniverse object, where the real processing takes place.
 *
 * @package    DutchPIPE
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Release: @package_version@
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpclient.php, dpuniverse.php
 */
final class DpServer
{
    /**
     * The socket the server has opened to a client
     */
    private $mMsgsock;

    /**
     * Used to show server status info on the command line, shows a header once
     * in a while
     */
    private $mShowedStatusHeader = 0;

    /**
     * Used to show server status info based on getrusage, start 'user' time of
     * script
     */
    private $mUtimeBefore;

    /**
     * Used to show server status info based on getrusage, start 'system' time
     * of script
     */
    private $mStimeBefore;

    /**
     * Sets up the server at object creation time based on your settings
     *
     * @param       string    $iniFile    Path to a dpserver-ini.php like file
     */
    function __construct($iniFile = 'dpserver-ini.php')
    {
        // Used by showStatus() further below:
        if (function_exists('getrusage')) {
            $rusage = getrusage();
            $this->mUtimeBefore = (int)$rusage["ru_utime.tv_sec"] * 1e6 +
                (int)$rusage["ru_utime.tv_usec"];
            $this->mStimeBefore = (int)$rusage["ru_stime.tv_sec"] * 1e6 +
                (int)$rusage["ru_stime.tv_usec"];
        }

        // Get the server settings:
        require_once($iniFile);

        error_reporting(DPSERVER_ERROR_REPORTING);

        // Allow the script to hang around waiting for connections:
        set_time_limit(DPSERVER_MAXUPTIME);

        // See what we're getting as it comes in:
        ob_implicit_flush();

        date_default_timezone_set(DPSERVER_TIMEZONE);
    }

    /**
     * Starts the DutchPIPE server, using a specific 'universe' object
     *
     * Also see: http://www.php.net/sockets
     *
     * @param       object    $universe   An instance of DpUniverse
     */
    function runDpServer(&$universe)
    {
        // Check if the server is already running:
        if (file_exists(DPSERVER_SOCKET_PATH)) {
            // :KLUDGE: should be improved:
            unlink(DPSERVER_SOCKET_PATH);
            //die("Cannot start server: server already running\n");
        }

        if (DPSERVER_SOCKET_TYPE === AF_UNIX) {
            // Initialize the server, first create a socket:
            if (FALSE === ($socket = socket_create(AF_UNIX, SOCK_STREAM, 0))) {
                die('socket_create() unable to create socket ['
                    . socket_last_error() . ']: '
                    . socket_strerror(socket_last_error()) . "\n");
            }

            // Bind the socket to a file, for example /tmp/dutchpipesock:
            if (FALSE === socket_bind($socket, DPSERVER_SOCKET_PATH)) {
                die('socket_bind() unable to bind [' . socket_last_error() . ']: '
                    . socket_strerror(socket_last_error()) . "\n");
            }
            if (FALSE === chmod(DPSERVER_SOCKET_PATH, 0777)) {
                die('Cannot start server: reason: chmod on ' . DPSERVER_SOCKET_PATH
                    . " failed\n");
            }
        } elseif (DPSERVER_SOCKET_TYPE === AF_INET) {
            // Initialize the server, first create a socket:
            if (FALSE === ($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) {
                die('socket_create() unable to create socket ['
                    . socket_last_error() . ']: '
                    . socket_strerror(socket_last_error()) . "\n");
            }

            // Bind the socket to a file, for example /tmp/dutchpipesock:
            if (FALSE === socket_bind($socket, DPSERVER_SOCKET_ADDRESS, DPSERVER_SOCKET_PORT)) {
                die('socket_bind() unable to bind [' . socket_last_error() . ']: '
                    . socket_strerror(socket_last_error()) . "\n");
            }
        } else {
            echo "Invalid socket protocol.\n";
            exit;
        }
        // Start listening to the socket which dpclient.php talks to:
        if (FALSE === socket_listen($socket, DP_SERVER_MAX_SOCKET_BACKLOG)) {
            die('socket_listen() failed [' . socket_last_error() . ']: '
                . socket_strerror(socket_last_error()) . "\n");
        }

        // Accept connections and loop for each request:
        do {
            if (FALSE === ($this->mMsgsock = socket_accept($socket))) {
                echo 'socket_accept() failed [' . socket_last_error() . ']: '
                    . socket_strerror(socket_last_error()) . "\n";
                break;
            }

            /*
             * We got a connection with a user client through dpclient.php,
             * dpclient.php should send some serialized arrays with variables.
             * These are the PHP global $_SERVER, $_COOKIE, ... arrays.
             * All client info, including input and AJAX parameters, are send
             * through these arrays.
             */
            $allbuf = '';
            do {
                if (DPSERVER_SOCKET_TYPE === AF_UNIX) {
                    // Read in what dpclient.php is telling us in chunks:
                    if (FALSE === ($buf = socket_read($this->mMsgsock,
                            DPSERVER_SERVER_CHUNK, PHP_NORMAL_READ))) {
                        echo 'socket_read() failed [' . socket_last_error() . ']: '
                            . socket_strerror(socket_last_error()) . "\n";
                        break 2;
                    }
                    if (!strlen($buf = trim($buf))) {
                        continue;
                    }
                    // This can be used to shutdown the server:
                    if ($buf == 'shutdown') {
                        socket_close($this->mMsgsock);
                        break 2;
                    }
                    // Read the 2KB blocks until dpclient.php sends us a 'quit':
                    if ($buf <> 'quit') {
                        $allbuf .= $buf;
                        continue;
                    }
                } else {
                    // Read in what dpclient.php is telling us in chunks:
                    if (FALSE === ($buf = socket_read($this->mMsgsock,
                            DPSERVER_SERVER_CHUNK))) {
                        echo 'socket_read() failed [' . socket_last_error() . ']: '
                            . socket_strerror(socket_last_error()) . "\n";
                        break 2;
                    }
                    if (!strlen($buf = trim($buf))) {
                        continue;
                    }
                    $allbuf .= $buf;

                    // This can be used to shutdown the server:
                    if (strlen($allbuf) >= 7 && substr($allbuf, -7) == 'shutdown') {
                        socket_close($this->mMsgsock);
                        break 2;
                    }
                    // Read the 2KB blocks until dpclient.php sends us a 'quit':
                    if (strlen($allbuf) < 4 || substr($allbuf, -4) != 'quit') {
                        continue;
                    }
                    //$allbuf = substr($allbuf, 0, strlen($all    buf - 4));
                }
                // Check for invalid input from dpclient.php:
                if (strlen($allbuf) <= 6
                        || FALSE === ($pos1 = strpos($allbuf, "<vars>"))
                        || FALSE === ($pos2 = strpos($allbuf, "</vars>"))
                        || $pos2 <= $pos1 + 7) {
                    echo 'allbuf: ' . $allbuf . "\n";
                    break;
                }

                // Cut out and unserialize the three global PHP vars
                // dpclient.php passed on:
                $vars = substr($allbuf, 0, $pos2);
                $vars = substr($vars, $pos1 + 6);
                $tmp = unserialize($vars);
                if (FALSE === $tmp) {
                    //$handle = fopen('/tmp/dpserver.log', 'a');
                    //fwrite($handle, "No unserialize: $vars\n");
                    //fclose($handle);
                    $__SERVER = $__SESSION = $__COOKIE = $__GET = $__POST =
                        $__FILES = array();
                }

                list($__SERVER, $__SESSION, $__COOKIE, $__GET, $__POST,
                    $__FILES) = $tmp;
                echo "handling...\n";
                // Pass on the request to the universe object. The universe
                // object can response by calling tellCurrentDpUserRequest() below:
                $universe->handleCurrentDpUserRequest($this, $__SERVER, $__SESSION,
                    $__COOKIE, $__GET, $__POST, $__FILES);

                // :KLUDGE: Shows server status once in a while. The ticks
                // system or external cron triggered calls should be used in the
                // future:
                if (1 === mt_rand(1, 2 + sizeof($universe->mDpUsers))) {
                    $this->_showStatus($universe);
                }
                break;
            } while (true);
            socket_close($this->mMsgsock);
        } while (true);

        socket_close($sock);
    }

    /**
     * Tells a string to the current user request through dpclient.php
     *
     * Called from 'the universe' to talk back to the client, messages are
     * typically XML with something like '<message>Lennert says: hi</message>'.
     *
     * @param       string    $talkback   String to send to current user client
     */
    function tellCurrentDpUserRequest($talkback)
    {
        if (0 === ($len = strlen($talkback))) {
            return;
        }
        if (FALSE === socket_write($this->mMsgsock, $talkback, $len)) {
            echo 'socket_write() failed [' . socket_last_error() . ']: '
                . socket_strerror(socket_last_error()) . "\n";
        }
    }

    /**
     * Shows some server info on the command line
     *
     * @param       object    $universe   An instance of DpUniverse
     */
    private function _showStatus(&$universe)
    {
        if (function_exists('memory_get_usage')) {
            // Shows a line with memory and universe info:
            echo 'Memory: ' . round(memory_get_usage() / 1024) . 'KB  #Objects: '
                . sizeof($universe->mDpObjects) . '  #Users: '
                . sizeof($universe->mDpUsers) . '  #Environments: '
                . sizeof($universe->mEnvironments) . '  #Timeouts: '
                . sizeof($universe->mTimeouts) . "\n";
            return;
        }

        // Outcomment this to get info based on getrusage, see man getrusage:
        /*
        $dat = getrusage();
        //print_r($dat);

        $utime_after = (int)$dat["ru_utime.tv_sec"]*1e6 +
            (int)$dat["ru_utime.tv_usec"];
        $stime_after = (int)$dat["ru_stime.tv_sec"]*1e6 +
            (int)$dat["ru_stime.tv_usec"];

        $dat["ru_utime"] = number_format(($utime_after - $utime_before) /
            1000000, 2) . 's';
        $dat["ru_stime"] = number_format(($stime_after - $stime_before) /
            1000000, 2) . 's';
        $dat["ru_maxrss"] = $dat["ru_maxrss"] . 'kb';
        unset($dat['ru_oublock']);
        unset($dat['ru_inblock']);
        unset($dat['ru_nswap']);
        unset($dat['ru_nsignals']);
        unset($dat['ru_utime.tv_sec']);
        unset($dat['ru_utime.tv_usec']);
        unset($dat['ru_stime.tv_sec']);
        unset($dat['ru_stime.tv_usec']);

        if (0 === (int)$this->mShowedStatusHeader) {
            $keys = array_keys($dat);
            foreach ($keys as $i => $k) {
                $keys[$i] = substr($keys[$i], 3);
                if (strlen($keys[$i]) > 7) {
                    $keys[$i] = substr($keys[$i], 0, 7);
                }
            }
            $this->mShowedStatusHeader = 30;

            echo "Memory\t" . implode("\t", $keys) . "\n";
        }
        echo round(memory_get_usage() / 1024) . "kb\t" . implode("\t", $dat)
            . "\n";
        $this->mShowedStatusHeader--;
        */
    }
}
?>
