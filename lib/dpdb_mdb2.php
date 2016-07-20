<?php
/**
 * MDB2 database functions
 *
 * A very simple layer over MDB2 functions, providing the same interface as
 * dpdb_mysql.php. MDB2 is a database abstraction layer, pary of PHP's PEAR
 * library, and provides interfaces for a number of databases.
 * Either this file or dpdb_mysql.php is included, based on settings in
 * dpuniverse-ini.php.
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
 * @version    Subversion: $Id: dpdb_mdb2.php 287 2007-08-21 18:47:19Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpuniverse-ini.php, dpdb_mysql.php
 * @since      DutchPIPE 0.4.0
 */

/**
 * PEAR:MDB2.php must be installed
 */
require_once(DPUNIVERSE_MDB2_PEAR_PATH . 'MDB2.php');

/**
 * Connects to the database server and sets it the current database
 *
 * Prints an error message to the server output in case of an error
 *
 * @return     boolean   MDB2 connection object on success, FALSE on failure
 * @see        DPUNIVERSE_MDB2_DSN, DPUNIVERSE_MDB2_CONNECT_OPTIONS,
 *             MDB2::singleton, dp_db_query, dp_db_exec, dp_db_fetch_one,
 *             dp_db_fetch_row, dp_db_quote, dp_db_num_rows, dp_db_next_id,
 *             dp_db_free
 * @ignore
 */
function &dp_db_connect()
{
    global $DPUNIVERSE_MDB2_DSN, $DPUNIVERSE_MDB2_CONNECT_OPTIONS;

    $mdb2 =& MDB2::singleton($DPUNIVERSE_MDB2_DSN,
        $DPUNIVERSE_MDB2_CONNECT_OPTIONS);
    if (PEAR::isError($mdb2)) {
        echo $mdb2->getMessage() . "\n";
        $mdb2 = FALSE;
    }

    return $mdb2;
}

/**
 * Sends a database query of type SELECT, SHOW, EXPLAIN, DESCRIBE, ...
 *
 * Prints an error message to the server output in case of an error
 *
 * @param      string    $sql        SQL statement
 * @return     mixed     MB2 result object or FALSE for failure
 * @see        MDB2::query, dp_db_connect, dp_db_exec, dp_db_fetch_one,
 *             dp_db_fetch_row, dp_db_quote, dp_db_num_rows, dp_db_free
 * @ignore
 */
function dp_db_query($sql)
{
    global $DPUNIVERSE_MDB2_DSN, $DPUNIVERSE_MDB2_CONNECT_OPTIONS;

    if (!($mdb2 = &dp_db_connect())) {
        return FALSE;
    }

    $result =& $mdb2->query($sql);
    if (PEAR::isError($result)) {
        echo $result->getMessage() . "\n";
        return FALSE;
    }

    return $result;
}

/**
 * Sends a database query of type DELETE, INSERT, REPLACE, UPDATE, ...
 *
 * Prints an error message to the server output in case of an error
 *
 * @param      string    $sql        SQL statement
 * @return     mixed     number of affected rows or FALSE for failure
 * @see        MDB2::exec, dp_db_connect, dp_db_query, dp_db_quote,
 *             dp_db_next_id
 * @ignore
 */
function dp_db_exec($sql)
{
    global $DPUNIVERSE_MDB2_DSN, $DPUNIVERSE_MDB2_CONNECT_OPTIONS;

    if (!($mdb2 = &dp_db_connect())) {
        return FALSE;
    }

    $affected =& $mdb2->exec($sql);
    if (PEAR::isError($affected)) {
        echo $affected->getMessage() . "\n";
        return FALSE;
    }

    return $affected;
}

/**
 * Gets result data
 *
 * Prints an error message to the server output in case of an error
 *
 * @param      resource  $result     MB2 result object from dp_db_query()
 * @param      int       $row        row number from result, starts at 0
 * @param      int       $field      optional field name or offset, 0 by default
 * @return     mixed     string with contents of one cell, or FALSE on failure
 * @see        MDB2::fetchOne, dp_db_connect, dp_db_query, dp_db_fetch_row,
 *             dp_db_quote, dp_db_num_rows, dp_db_free
 * @ignore
 */
function dp_db_fetch_one($result, $row, $field = 0)
{
    $one =& $result->fetchOne($field, $row);
    if (PEAR::isError($one)) {
        echo $one->getMessage() . "\n";
        return FALSE;
    }

    return $one;
}

/**
 * Gets a result row as an enumerated array
 *
 * @param      resource  $result     MB2 result object from dp_db_query()
 * @return     mixed     numerical array of strings with row data, FALSE if
 *                       there are no more rows
 * @see        MDB2::fetchRow, dp_db_connect, dp_db_query, dp_db_fetch_one,
 *             dp_db_quote, dp_db_num_rows, dp_db_free
 * @ignore
 */
function &dp_db_fetch_row($result)
{
    $row =& $result->fetchRow();
    if (PEAR::isError($row)) {
        echo $row->getMessage() . "\n";
        return FALSE;
    }

    return $row;
}

/**
 * Escapes special characters in a string for use in a SQL statement
 *
 * Escapes the value given in the first argument. All other arguments are only
 * used by this MDB2 function and ignored by the MySQL equivalent.
 *
 * @param      string    $val           string that is to be escaped
 * @param      string    $type   	    optional type to convert to
 * @param      boolean   $quote   	    quote and escape value?
 * @param      boolean   $escWildcards  escape wildcards?
 * @return     mixed     escaped string, or FALSE on error
 * @see        MDB2::quote, dp_db_query, dp_db_exec
 * @ignore
 */
function dp_db_quote($val, $type = NULL, $quote = TRUE, $escWildcards = FALSE)
{
    global $DPUNIVERSE_MDB2_DSN, $DPUNIVERSE_MDB2_CONNECT_OPTIONS;

    if (!($mdb2 = &dp_db_connect())) {
        return FALSE;
    }

    return $mdb2->quote($val, $type, $quote, $escWildcards);
}

/**
 * Gets number of rows in result
 *
 * Prints an error message to the server output in case of an error
 *
 * @param      resource  $result     MB2 result object from dp_db_query()
 * @return     mixed     number of rows in a result set, or FALSE on failure
 * @see        MDB2::numRows, dp_db_query
 * @ignore
 */
function dp_db_num_rows($result)
{
    $num_rows =& $result->numRows();
    if (PEAR::isError($num_rows)) {
        echo $num_rows->getMessage() . "\n";
        return FALSE;
    }

    return $num_rows;
}

/**
 * Gets the next primary ID for an INSERT
 *
 * Prints an error message to the server output in case of an error
 *
 * @param      string    $table      table name
 * @param      string    $idColumn   field name
 * @return     mixed     integer with next id, or FALSE for failure
 * @see        dp_db_exec
 * @ignore
 */
function dp_db_next_id($table, $idColumn)
{
    global $DPUNIVERSE_MDB2_DSN, $DPUNIVERSE_MDB2_CONNECT_OPTIONS;

    if (!($mdb2 = &dp_db_connect())) {
        return FALSE;
    }

    $next_id =& $mdb2->nextID($table);
    if (PEAR::isError($next_id)) {
        echo $next_id->getMessage() . "\n";
        return FALSE;
    }

    return $next_id;
}

/**
 * Frees result memory
 *
 * @param      resource  $result     MB2 result object from dp_db_query()
 * @return     boolean   TRUE on success or FALSE on failure
 * @see        MDB2::free, dp_db_query, dp_db_fetch_one, dp_db_fetch_row
 * @ignore
 */
function dp_db_free($result)
{
    return is_object($result) && $result->free();
}
?>
