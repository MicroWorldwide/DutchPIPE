<?php
/**
 * Serves as a gateway between the browser and the PHP DutchPIPE server
 *
 * Passes the HTTP request to the PHP server along with its environment and user
 * variables, and returns the info retrieved from the PHP server back to the
 * user's browser. Used to serve pages, and by the AJAX engine in
 * dpclient-js.php. It talks to the PHP server using a fast file socket
 * connection.
 *
 * DutchPIPE version 0.4; PHP version 5
 *
 * LICENSE: This source file is subject to version 1.0 of the DutchPIPE license.
 * If you did not receive a copy of the DutchPIPE license, you can obtain one at
 * http://dutchpipe.org/license/1_0.txt or by sending a note to
 * license@dutchpipe.org, in which case you will be mailed a copy immediately.
 *
 * @package    DutchPIPE
 * @subpackage lib
 * @author     Lennert Stock <ls@dutchpipe.org>
 * @copyright  2006, 2007 Lennert Stock
 * @license    http://dutchpipe.org/license/1_0.txt  DutchPIPE License
 * @version    Subversion: $Id: dpclient.php 307 2007-09-01 17:16:09Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpserver-ini.php, dpserver.php, dpclient.css
 */

/**
 * Gets server settings
 */
if (!defined('DPSERVER_HOST_URL')) {
    require_once(realpath(dirname($_SERVER['SCRIPT_FILENAME']) . '/..')
        . '/config/dpserver-ini.php');
}

/**
 * Universe paths used by templates
 */
require_once(DPSERVER_DPUNIVERSE_CONFIG_PATH . 'dpuniverse-ini.php');

/**
 * Common functions for templates available to universe objects and dpclient.php
 */
require_once(DPSERVER_LIB_PATH . 'dptemplates.php');

/**
 * Provides alternatives to multibyte string functions if not supported
 */
require_once(DPSERVER_LIB_PATH . 'dpmbstring_'
    . (!DPSERVER_ENABLE_MBSTRING || !function_exists('mb_strlen')
    ? 'disabled' : 'enabled') . '.php');

error_reporting(DPSERVER_ERROR_REPORTING);

/**
 * Contains a message describing the last error, if any
 */
$gLastErrorMsg = NULL;

/* Deal with AJAX requests and normal page requests */
if ((!isset($_GET['ie6']) || $_GET['ie6'] !== 'yes') && isset($_GET) && (isset($_GET['ajax']) || isset($_GET['method']))) {
    /* Output XML or '1' to "keep-alive" */
    handle_ajax_request(talk2server());
} else {
    /* Output XHTML */
    handle_normal_request(talk2server());
}

/**
 * Talks to the DutchPIPE server, returns response
 *
 * @return     boolean|string  output from the DutchPIPE server, FALSE for error
 */
function talk2server()
{
    global $gLastErrorMsg, $header_data;

    if (DPSERVER_SOCKET_TYPE === AF_UNIX) {
        /* Creates a file socket and connects to it */
        if (FALSE === ($socket = socket_create(AF_UNIX, SOCK_STREAM, 0))) {
            $gLastErrorMsg = '<h1>' . DPSERVER_SOCKERR_MSG . '</h1>'
                . sprintf(dp_text(
                "socket_create(): unable to create socket [%u]: %s\n"),
                socket_last_error(), socket_strerror(socket_last_error()));
            return FALSE;
        }
        if (FALSE === @socket_connect($socket, DPSERVER_SOCKET_PATH)) {
            $gLastErrorMsg = '<h1>' . DPSERVER_SOCKERR_MSG . '</h1>'
                . sprintf(dp_text(
                "socket_create(): unable to connect [%u]: %s\n"),
                socket_last_error(), socket_strerror(socket_last_error()));
            return FALSE;
        }
    } elseif (DPSERVER_SOCKET_TYPE === AF_INET) {
        /* Creates a TCP/IP socket and connects to it */
        if (FALSE === ($socket = socket_create(AF_INET, SOCK_STREAM,
                SOL_TCP))) {
            $gLastErrorMsg = '<h1>' . DPSERVER_SOCKERR_MSG . '</h1>'
                . sprintf(dp_text(
                "socket_create(): unable to create socket [%u]: %s\n"),
                socket_last_error(), socket_strerror(socket_last_error()));
            return FALSE;
        }
        if (FALSE === @socket_connect($socket, DPSERVER_SOCKET_ADDRESS,
                DPSERVER_SOCKET_PORT)) {
            $gLastErrorMsg = '<h1>' . DPSERVER_SOCKERR_MSG . '</h1>'
                . sprintf(dp_text(
                "socket_create(): unable to connect [%u]: %s\n"),
                socket_last_error(), socket_strerror(socket_last_error()));
            return FALSE;
        }
    } else {
        $gLastErrorMsg = '<h1>' . DPSERVER_SOCKERR_MSG. '</h1>'
            . dp_text('Invalid socket protocol');
        return FALSE;
    }

    /* Talk to the DutchPIPE server and write user variables to it */
    if (!isset($_SESSION)) {
        $_SESSION = array();
    }

    if (isset($_FILES['dpuploadimg'])
            && isset($_FILES['dpuploadimg']['tmp_name'])) {
        @chmod($_FILES['dpuploadimg']['tmp_name'], 0777);
    }

    /* Serialize $_SERVER, $_SESSION, ... variables so they can be sent */
    $in = serialize(array($_SERVER, $_SESSION, $_COOKIE, $_GET, $_POST,
        $_FILES));
    if (TRUE === DPSERVER_BASE64_CLIENT2SERVER) {
        $in = base64_encode($in);
    }
    $in = "<vars>$in</vars>\r\nquit\r\n";

    socket_write($socket, $in, strlen($in));
    /* Read and process server reply, filter header info given by the server,
      put the remainder in $output */
    $cookie_set = $remove_guest_cookie = $remove_registered_cookie = FALSE;

    for ($output = ''; $buf = @socket_read($socket, DPSERVER_CLIENT_CHUNK); ) {
        $output .= $buf;
    }

    $arroutput = explode(chr(0), $output);
    $output = '';
    foreach ($arroutput as $buf) {
        if (!dp_strlen($buf) || isset($newlocation)) {
            continue;
        }
        $bufdec = TRUE === DPSERVER_BASE64_SERVER2CLIENT ? base64_decode($buf)
            : $buf;
        if (dp_strlen($bufdec) > 11
                && dp_substr($bufdec, 0, 11) == "Set-Login: ") {
            $cookie_data = dp_substr($bufdec, 11);
            setcookie(DPSERVER_COOKIE_NAME, $cookie_data, FALSE, '/');
            $cookie_set = TRUE;
        }

        elseif (dp_strlen($bufdec) > 17 && FALSE !== ($pos1 = dp_strpos($bufdec,
                "<header><![CDATA[")) && FALSE !== ($pos2 = dp_strpos($bufdec,
                "]]></header>")) && $pos2 > $pos1 + 12) {
            $header_data = dp_substr($bufdec, 0, $pos2);
            $header_data = dp_substr($header_data, $pos1 + 17);
            header($header_data);
        }

        elseif (dp_strlen($bufdec) > 17 && FALSE !== ($pos1 = dp_strpos($bufdec,
                "<cookie><![CDATA[")) && FALSE !== ($pos2 = dp_strpos($bufdec,
                "]]></cookie>")) && $pos2 > $pos1 + 12) {
            $cookie_data = dp_substr($bufdec, 0, $pos2);
            $cookie_data = dp_substr($cookie_data, $pos1 + 17);
            if ($cookie_data == 'removeguest') {
                $remove_guest_cookie = TRUE;
            }
            elseif ($cookie_data == 'removeregistered') {
                $remove_registered_cookie = TRUE;
            }
            else {
                setcookie(DPSERVER_COOKIE_NAME, $cookie_data, time()
                    + 630720000, '/');
                $cookie_set = TRUE;
            }
        }

        elseif (dp_strlen($bufdec) > 19 && FALSE !== ($pos1 = dp_strpos($bufdec,
                "<location><![CDATA[")) && FALSE !== ($pos2 = dp_strpos($bufdec,
                "]]></location>")) && $pos2 > $pos1 + 14) {
            $bufdec = dp_substr($bufdec, 0, $pos2);
            $bufdec = dp_substr($bufdec, $pos1 + 19);
            $newlocation = $bufdec;
            $newlocation = $newlocation == '' ? DPSERVER_CLIENT_DIR
                : DPSERVER_CLIENT_URL . "?location=$newlocation";
            $output = "<location><![CDATA[$newlocation]]></location>";
            continue;
        }
        else {
            $output .= TRUE === DPSERVER_BASE64_SERVER2CLIENT
                ? base64_decode($buf) : $buf;
        }
    }

    /* Close cocket, return server reply */
    socket_close($socket);

    if (FALSE === $cookie_set) {
        if (FALSE !== $remove_guest_cookie) {
            setcookie(DPSERVER_COOKIE_NAME, FALSE, FALSE, '/');
        }
        if (FALSE !== $remove_registered_cookie) {
            setcookie(DPSERVER_COOKIE_NAME, FALSE, time() - 3600, '/');
        }
    }

    if (isset($newlocation)) {
        if (!isset($_GET) || !isset($_GET['ajax'])) {
            header("Location: $newlocation");
            exit;
        }
    }

    return $output;
}

/**
 * Handles AJAX requests from dpclient-js.php
 *
 * @param   string  $output     The output from talk2server()
 */
function handle_ajax_request($output)
{
    set_time_limit(round(DPUNIVERSE_LINKDEATH_KICKTIME / 2));
    if (FALSE === $output || $output == '1') {
        if (is_integer(DPSERVER_APACHE_GZIP_MIN)
                && DPSERVER_APACHE_GZIP_MIN > 1) {
            apache_setenv('no-gzip', '1');
        }
        echo '1';
    }
    elseif (FALSE === $output || $output == '2') {
        if (is_integer(DPSERVER_APACHE_GZIP_MIN)
                && DPSERVER_APACHE_GZIP_MIN > 1) {
            apache_setenv('no-gzip', '1');
        }
        echo '2';
    }
    else {
        if (isset($_GET) && isset($GET['standalone']) && isset($GET['_seq'])
                && $GET['_seq'] == '0') {
            $xml2 = simplexml_load_string($str =
                '<?xml version="1.0" encoding="UTF-8" standalone="yes" ?>'
                . '<dutchpipe>' . $output . '</dutchpipe>');

            if (FALSE !== $xml2) {
                handle_cookies($xml2);
            }
        }
        if (is_integer(DPSERVER_APACHE_GZIP_MIN)
                && DPSERVER_APACHE_GZIP_MIN > dp_strlen($output)) {
            apache_setenv('no-gzip', '1');
        }

        header('Content-Type: text/xml');
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\" standalone=\"yes\" ?>
<dutchpipe>$output</dutchpipe>\n";
        exit;
    }
}

/**
 * Sets cookies transmitted with the xml we got from the server
 *
 * @param   string  &$xml       XML of the output from talk2server()
 */
function handle_cookies(&$xml)
{
    foreach ($xml->cookie as $id => $cookie) {
        foreach($xml->cookie->attributes() as $attname => $attval) {
            switch ($attname) {
            case 'name':
                $name = $attval;
                break;
            case 'expire':
                $expire = dp_strlen($attval) ? $attval : FALSE;
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
                        setcookie($name, $cookie, FALSE, '/');
                    } else {
                        setcookie($name, $cookie, $expire, '/');
                    }
                } else {
                    setcookie($name, $cookie,
                        (!isset($expire) ? FALSE : $expire), $path);
                }
            } else {
                setcookie($name, $cookie, (!isset($expire) ? FALSE : $expire),
                    (!isset($path) ? '' : $path), $domain);
            }
        } else {
            setcookie($name, $cookie, (!isset($expire) ? FALSE : $expire),
                (!isset($path) ? '' : $path), (!isset($domain) ? '' : $domain),
                $secure);
        }
    }
}

/**
 * Handles a normal page request
 *
 * @param   string  $output     The output from talk2server()
 */
function handle_normal_request($output)
{
    global $gLastErrorMsg;

    if (isset($_GET['ie6']) && 'yes' === $_GET['ie6']) {
        return;
    }
    if (FALSE === $output) {
        $body = $gLastErrorMsg;

        /* Otherwise serve the page with the retrieved content in it */
        echo "<?xml version=\"1.0\" encoding=\"UTF-8\"?>\n";
        require_once(DPSERVER_TEMPLATE_PATH . DPSERVER_TEMPLATE_DOWN_FILE);
        exit;
    }
    $xml = simplexml_load_string($str = '<?xml version="1.0" encoding="UTF-8" '
        . 'standalone="yes" ?><dutchpipe>' . $output . '</dutchpipe>');

    if (FALSE === $xml) {
        echo '<pre>' . htmlentities($str) . '</pre>';
        exit;
    }

    $messages = $windows = array();
    $body = $dpelements = $scripts = '';
    $closetext = dp_text('close');
    $inputpersistent = $template_file = FALSE;

    handle_cookies($xml);

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
            case 'stylesheet':
                $dpelements .= $data->asXML();
                break;
            case 'script':
                $tmp = $data->asXML();
                $pos1 = dp_strpos($tmp, '<![CDATA[');
                $pos2 = dp_strpos($tmp, ']]>');
                if (FALSE !== $pos1 && FALSE !== $pos2 && $pos1 < $pos2) {
                    $tmp = dp_substr($tmp,  0, $pos1)
                        . dp_substr($tmp, $pos1 + 9, $pos2 - $pos1 - 9)
                        . dp_substr($tmp, $pos2 + 3);
                }
                $scripts .= $tmp;
                break;
            case 'div':
                foreach ($data->attributes() as $a => $b) {
                    if ('template' === $a) {
                        $template_file = $b;

                    }
                }
                $body .= str_replace(' template="' . (!$template_file ? ''
                    : $template_file) . '"', '', $data);
                break;
            case 'window':
                $windows[] = '<div class="dpwindow_default" id="dpwindow">'
                    . $data . '<p align="right"><a href="javascript:'
                    . 'close_dpwindow()">' . $closetext . '</a></p></div>';
                break;
            case 'inputpersistent':
                foreach ($data->attributes() as $a => $b) {
                    if ('persistent' === $a) {
                        $inputpersistent = $b;
                    }
                }
                break;
            default:
                break;
            }
        }
    }

    if (0 === dp_strlen($body)) {
        $body = '<h1>' . dp_text('Error fetching page. Invalid page XML.')
            . '</h1>';
    }

    if (dp_strlen($dpelements)) {
        $dpelements = "        <script type=\"text/javascript\">
            function dp_load_xml(text)
            {
                var xmlDoc = '';
                if (window.ActiveXObject) {
                    xmlDoc = new ActiveXObject('Microsoft.XMLDOM');
                    xmlDoc.async='false';
                    xmlDoc.loadXML(text);
                }
                else if (document.implementation &&
                        document.implementation.createDocument) {
                        var parser = new DOMParser();
                    var xmlDoc=parser.parseFromString(text,'text/xml');
                }
                else {
                    /* alert('Your browser cannot handle this script'); */
                    return false;
                }
                return xmlDoc;
            }

            function dp_load_elements()
            {
                var content = '<?xml version=\"1.0\"?><dutchpipe>"
            . "<event count=\"-1\" time=\"-1\">" . addslashes($dpelements)
            . "</event></dutchpipe>'; handle_response(dp_load_xml(content));
            }
            </script>\n";
    }

    $messages_style = !sizeof($messages) ? ''
        : ' style="display: block; padding-top: 12px"';

    $windows = implode("\n", $windows);
    $messages = implode("\n", $messages);

    $subtemplates = dp_get_subtemplates(array('input', 'input_say'),
        $template_file);
    ob_start();
    include($subtemplates['input']);
    if ('always' == $inputpersistent) {
        $dpinput_say = ob_get_contents();
    } else {
        $dpinput = ob_get_contents();
    }
    ob_end_clean();

    ob_start();
    include($subtemplates['input_say']);
    if ('always' == $inputpersistent) {
        $dpinput = ob_get_contents();
    } else {
        $dpinput_say = ob_get_contents();
    }
    ob_end_clean();

    /* Otherwise serve the page with the retrieved content in it */
    echo "<?xml version=\"1.0\" encoding=\"utf-8\"?>\n";
    require_once(!$template_file
        ? DPSERVER_TEMPLATE_PATH . DPSERVER_TEMPLATE_FILE : $template_file);
}
?>
