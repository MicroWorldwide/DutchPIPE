<?php
/**
 * Serves as a gateway between the browser and the PHP DutchPIPE server
 *
 * Passes the HTTP request to the PHP server along with its environment and user
 * variables, and returns the info retrieved from the PHP server back to the
 * user's browser. Used to serve pages, and by the AJAX engine in dpclient.js.
 * It talks to the PHP server using a fast file socket connection.
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
 * @version    Subversion: $Id: dpclient.php 2 2006-05-16 00:20:42Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpserver-ini.php, dpserver.php, dpclient.css
 */

require_once(dirname(realpath(__FILE__ . '/..')) . '/config/dpserver-ini.php');

error_reporting(DPSERVER_ERROR_REPORTING);

/**
 * Contains a message describing the last error, if any
 */
$gLastErrorMsg = NULL;

/* Deal with AJAX requests and normal page requests */
if (isset($_GET) && (isset($_GET['ajax']) || isset($_GET['method']))) {
    /* Output XML or '1' to "keep-alive" */
    handle_ajax_request(talk2server(DPSERVER_SOCKET_PATH));
} else {
    /* Output XHTML */
    handle_normal_request(talk2server(DPSERVER_SOCKET_PATH));
}

/**
 * Talks to the DutchPIPE server, returns response
 *
 * @return      string    output from the DutchPIPE server
 */
function talk2server($socketPath)
{
    global $gLastErrorMsg, $header_data;

    if (DPSERVER_SOCKET_TYPE === AF_UNIX) {
        /* Creates a file socket and connects to it */
        if (FALSE === ($socket = socket_create(AF_UNIX, SOCK_STREAM, 0))) {
            $gLastErrorMsg = '<h1>The DutchPIPE server is down</h1>socket_create() '
                . 'unable to create socket [' . socket_last_error() . ']: '
                . socket_strerror(socket_last_error()) . "\n";
            return FALSE;
        }
        if (FALSE === @socket_connect($socket, $socketPath)) {
            $gLastErrorMsg = '<h1>The DutchPIPE server is down</h1>'
                . 'socket_connect() unable to connect [' . socket_last_error()
                . ']: ' . socket_strerror(socket_last_error($socket)) . "\n";
            return FALSE;
        }
    } elseif (DPSERVER_SOCKET_TYPE === AF_INET) {
        /* Creates a TCP/IP socket and connects to it */
        if (FALSE === ($socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP))) {
            $gLastErrorMsg = '<h1>The DutchPIPE server is down</h1>socket_create() '
                . 'unable to create socket [' . socket_last_error() . ']: '
                . socket_strerror(socket_last_error()) . "\n";
            return FALSE;
        }
        if (FALSE === @socket_connect($socket, DPSERVER_SOCKET_ADDRESS, DPSERVER_SOCKET_PORT)) {
            $gLastErrorMsg = '<h1>The DutchPIPE server is down</h1>'
                . 'socket_connect() unable to connect [' . socket_last_error()
                . ']: ' . socket_strerror(socket_last_error($socket)) . "\n";
            return FALSE;
        }
    } else {
        $gLastErrorMsg = '<h1>The DutchPIPE server is down</h1>Invalid socket '
            . 'protocol';
        return FALSE;
    }

    /* Talk to the DutchPIPE server and write user variables to it */
    if (!isset($_SESSION)) {
        $_SESSION = array();
    }

    /* Serialize $_SERVER, $_SESSION, ... variables so they can be sent */
    $in = '<vars>' . serialize(array($_SERVER, $_SESSION, $_COOKIE, $_GET,
        $_POST, $_FILES)) . "</vars>\r\nquit\r\n";

    socket_write($socket, $in, strlen($in));

    /* Read and process server reply, filter header info given by the server,
      put the remainder in $output */
    $cookie_set = $remove_guest_cookie = $remove_registered_cookie = FALSE;

    for ($output = ''; $buf = socket_read($socket, DPSERVER_CLIENT_CHUNK); ) {
        if (!strlen($buf) || isset($newlocation)) {
            continue;
        }

        elseif (strlen($buf) > 11 && substr($buf, 0, 11) == "Set-Login: ") {
            $cookie_data = substr($buf, 11);
            setcookie('dutchpipe', $cookie_data);
            $cookie_set = TRUE;
        }

        elseif (strlen($buf) > 17 && FALSE !== ($pos1 = strpos($buf,
                "<header><![CDATA[")) && FALSE !== ($pos2 = strpos($buf,
                "]]></header>")) && $pos2 > $pos1 + 12) {
            $header_data = substr($buf, 0, $pos2);
            $header_data = substr($header_data, $pos1 + 17);
            header($header_data);
        }

        elseif (strlen($buf) > 17 && FALSE !== ($pos1 = strpos($buf,
                "<cookie><![CDATA[")) && FALSE !== ($pos2 = strpos($buf,
                "]]></cookie>")) && $pos2 > $pos1 + 12) {
            $cookie_data = substr($buf, 0, $pos2);
            $cookie_data = substr($cookie_data, $pos1 + 17);
            if ($cookie_data == 'removeguest') {
                $remove_guest_cookie = TRUE;
            }
            elseif ($cookie_data == 'removeregistered') {
                $remove_registered_cookie = TRUE;
            }
            else {
                setcookie('dutchpipe', $cookie_data, time() + 630720000);
                $cookie_set = TRUE;
            }
        }

        elseif (strlen($buf) > 19 && FALSE !== ($pos1 = strpos($buf,
                "<location><![CDATA[")) && FALSE !== ($pos2 = strpos($buf,
                "]]></location>")) && $pos2 > $pos1 + 14) {
            $buf = substr($buf, 0, $pos2);
            $buf = substr($buf, $pos1 + 19);
            $newlocation = $buf;
            $output = "<location><![CDATA[$buf]]></location>";
            continue;
        }

        else {
            $output .= $buf;
        }
    }

    /* Close cocket, return server reply */
    socket_close($socket);

    if (FALSE === $cookie_set) {
        if (FALSE !== $remove_guest_cookie) {
            setcookie('dutchpipe', FALSE);
        }
        if (FALSE !== $remove_registered_cookie) {
            setcookie('dutchpipe', FALSE, time() - 3600);
        }
    }

    if (isset($newlocation)) {
        $newlocation = $newlocation == '/' ? '/'
            : "/dpclient.php?location=$newlocation";
        if (!isset($_GET) || !isset($_GET['ajax'])) {
            header("Location: $newlocation");
            exit;
        }
    }

    return $output;
}

/**
 * Handles AJAX requests from dpclient.js
 */
function handle_ajax_request($output)
{
    if (FALSE === $output || $output == '1') {
        echo '1';
    }
    elseif (FALSE === $output || $output == '2') {
        echo '2';
    }
    else {
        header('Content-Type: text/xml');
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\" ?>
<dutchpipe>$output</dutchpipe>\n";
    }
}

/**
 * Sets cookies transmitted with the xml we got from the server
 */
function handle_cookies(&$xml)
{
    foreach ($xml->cookie as $id => $cookie) {
        //echo $cookie . '<br />';
        foreach($xml->cookie->attributes() as $attname => $attval) {
            switch ($attname) {
            case 'name':
                $name = $attval;
                break;
            case 'expire':
                $expire = strlen($attval) ? $attval : FALSE;
                break;
            case 'path':
                $path = $attval;
                break;
            case 'domain':
                $domain = $attval;
                break;
            case 'secure':
                $secure = $attval;
                break;
            default:
                break;
            }
        }

        if (!isset($name)) {
            break;
        }

        if (!isset($secure)) {
            if (!isset($domain)) {
                if (!isset($path)) {
                    if (!isset($expire)) {
                        setcookie($name, $cookie);
                        $cookie_debug = "setcookie($name, $cookie)";
                    } else {
                        setcookie($name, $cookie, $expire);
                        $cookie_debug = "setcookie($name, $cookie, $expire)";
                    }
                } else {
                    setcookie($name, $cookie,
                        (!isset($expire) ? FALSE : $expire), $path);
                    $cookie_debug = "setcookie($name, $cookie, "
                        . (!isset($expire) ? 'FALSE' : $expire) . ", $path)";
                }
            } else {
                setcookie($name, $cookie, (!isset($expire) ? FALSE : $expire),
                    (!isset($path) ? '' : $path), $domain);
                $cookie_debug = "setcookie($name, $cookie, " . (!isset($expire)
                    ? 'FALSE' : $expire) . ", " . (!isset($path) ? '' : $path)
                    .  ", $domain)";
            }
        } else {
            setcookie($name, $cookie, (!isset($expire) ? FALSE : $expire),
                (!isset($path) ? '' : $path), (!isset($domain) ? '' : $domain),
                $secure);
            $cookie_debug = "setcookie($name, $cookie, " . (!isset($expire) ?
                'FALSE' : $expire) . ", " . (!isset($path) ? '' : $path) .  ", "
                . (!isset($domain) ? '' : $domain) . ", $secure)";
        }
    }
}

/**
 * Handles a normal page request
 */
function handle_normal_request($output)
{
    global $gLastErrorMsg;

    if (FALSE === $output) {
        $body = "<h1>$gLastErrorMsg</h1>";
        $dpelements = '';
        $inputtopmargin = '0';
        $windows = '';
        $messages = '';

        /* Otherwise serve the page with the retrieved content in it */
        echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
        require_once(DPSERVER_DPUNIVERSE_TEMPLATE_PATH . 'dpdefault.tpl');
        exit;
    }
    $xml = simplexml_load_string($str = '<?xml version="1.0" encoding="UTF-8" '
        . 'standalone="yes" ?><dutchpipe>' . $output . '</dutchpipe>');

    if (FALSE === $xml) {
        echo '<pre>' . htmlentities($str) . '</pre>';
        exit;
    }
    handle_cookies($xml);

    $messages = $windows = array();
    $body = '';
    $dpelements = '';

    foreach ($xml->event as $e) {
        foreach ($e as $type => $data) {
            switch ($type) {
            case 'message':
                $messages[] = $data;
                break;
            case 'addDpElement':
                $dpelements .= $data->asXML();
                break;
            case 'removeDpElement':
                $dpelements .= $data->asXML();
                break;
            case 'changeDpElement':
                $dpelements .= $data->asXML();
                break;
            case 'moveDpElement':
                $dpelements .= $data->asXML();
                break;
            case 'div':
                $body .= $data;
                break;
            case 'window':
                $windows[] = '<div class="dpwindow_default" id="dpwindow">'
                    . $data . '<p align="right"><a '
                    . 'href="javascript:close_dpwindow()">close</a></p></div>';
                break;
            default:
                break;
            }
        }
    }

    if (0 === strlen($body)) {
        $body = '<h1>Error fetching page. Invalid page XML.</h1>';
    }

    if (strlen($dpelements)) {
        $dpelements = "        <script type=\"text/javascript\">
            function load_elements()
            {
                var content = '<?xml version=\"1.0\"?><dutchpipe>"
            . "<event count=\"-1\" time=\"-1\">" . addslashes($dpelements)
            . "</event></dutchpipe>'; handle_response(content);
            }
            </script>\n";
    }

    $inputtopmargin = !sizeof($messages) ? '0' : '10';
    $windows = implode("\n", $windows);
    $messages = implode("\n", $messages);

    /* Otherwise serve the page with the retrieved content in it */
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    require_once(DPSERVER_DPUNIVERSE_TEMPLATE_PATH . 'dpdefault.tpl');
}
?>