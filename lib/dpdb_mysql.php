<?php
/**
 * MySQL database functions
 *
 * A very simple layer over MySQL functions, providing the same interface as
 * dpdb_mdb2.php (which is a layer over the MDB2 database abstraction layer).
 * Either this file or dpdb_mdb2.php is included, based on settings in
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
 * @version    Subversion: $Id: dpdb_mysql.php 293 2007-08-25 23:11:20Z ls $
 * @link       http://dutchpipe.org/manual/package/DutchPIPE
 * @see        dpuniverse-ini.php, dpdb_mdb2.php
 * @since      DutchPIPE 0.4.0
 */

/**
 * MySQL link identifier
 */
$grMySqlConnection = NULL;

/**
 * Connects to the database server and sets it the current database
 *
 * Prints an error message to the server output in case of an error
 *
 * @return     boolean   MySQL link identifier on success, FALSE on failure
 * @see        DPUNIVERSE_MYSQL_HOST, PUNIVERSE_MYSQL_USER,
 *             DPUNIVERSE_MYSQL_PASSWORD, http://www.php.net/mysql_connect,
 *             http://www.php.net/mysql_select_db, dp_db_query, dp_db_exec,
 *             dp_db_fetch_one, dp_db_fetch_row, dp_db_quote, dp_db_num_rows,
 *             dp_db_next_id, dp_db_free
 */
function &dp_db_connect()
{
    global $grMySqlConnection;

    if ($grMySqlConnection) {
        return $grMySqlConnection;
    }

    $connection = mysql_connect(DPUNIVERSE_MYSQL_HOST, DPUNIVERSE_MYSQL_USER,
        DPUNIVERSE_MYSQL_PASSWORD);
    if (!is_resource($connection)) {
        echo sprintf(dp_text("Could not connect: %s [error number %d]\n"),
            mysql_error(), mysql_errno());
        $rval = FALSE;
    } else {
        $grMySqlConnection =& $connection;

        if (!mysql_select_db(DPUNIVERSE_MYSQL_DB, $connection)) {
            echo sprintf(dp_text("Failed to select database: %s\n"),
                DPUNIVERSE_MYSQL_DB);
            $rval = FALSE;
        } else {
            return $connection;
        }
    }

    return $rval;
}

/**
 * Sends a MySQL query of type SELECT, SHOW, EXPLAIN, DESCRIBE, ...
 *
 * Prints an error message to the server output in case of an error
 *
 * @param      string    $sql        SQL statement
 * @return     mixed     a MySQL resource or FALSE on failure
 * @see        http://www.php.net/mysql_query, dp_db_connect, dp_db_exec,
 *             dp_db_fetch_one, dp_db_fetch_row, dp_db_quote, dp_db_num_rows,
 *             dp_db_free
 */
function dp_db_query($sql)
{
    if (!($link = &dp_db_connect())) {
        return FALSE;
    }

    $result = mysql_query($sql, $link);
    if (FALSE === $result) {
        echo mysql_error($link) . ' [error number ' . mysql_errno($link)
            . "]\n";
    }

    return $result;
}

/**
 * Sends a MySQL query of type DELETE, INSERT, REPLACE, UPDATE, ...
 *
 * Prints an error message to the server output in case of an error
 *
 * @param      string    $sql        SQL statement
 * @return     mixed     number of affected rows or FALSE on failure
 * @see        http://www.php.net/mysql_query, dp_db_connect, dp_db_query,
 *             dp_db_quote, dp_db_next_id
 */
function dp_db_exec($sql)
{
    if (!($link = &dp_db_connect())) {
        return FALSE;
    }

    $result = mysql_query($sql, $link);
    if (FALSE === $result
            || -1 === ($affected_rows = mysql_affected_rows($link))) {
        echo mysql_error($link) . ' [error number ' . mysql_errno($link)
            . "]\n";
        return FALSE;
    }

    return $affected_rows;
}

/**
 * Gets result data
 *
 * Prints an error message to the server output in case of an error
 *
 * @param      resource  $result     result resource from dp_db_query()
 * @param      int       $row        row number from result, starts at 0
 * @param      int       $field      optional field name or offset, 0 by default
 * @return     mixed     string with contents of one cell, or FALSE on failure
 * @see        http://www.php.net/mysql_result, dp_db_connect, dp_db_query,
 *             dp_db_fetch_row, dp_db_quote, dp_db_num_rows, dp_db_free
 */
function dp_db_fetch_one($result, $row, $field = 0)
{
    $one = mysql_result($result, $row, $field);
    if (FALSE === $one) {
        echo "dp_db_fetch_one: Failed to fetch data\n";
    }

    return $one;
}

/**
 * Gets a result row as an enumerated array
 *
 * @param      resource  $result     result resource from dp_db_query()
 * @return     mixed     numerical array of strings with row data, FALSE if
 *                       there are no more rows
 * @see        http://www.php.net/mysql_fetch_row, dp_db_connect, dp_db_query,
 *             dp_db_fetch_one, dp_db_quote, dp_db_num_rows, dp_db_free
 */
function &dp_db_fetch_row($result)
{
    $row = mysql_fetch_row($result);
    return $row;
}

/**
 * Escapes special characters in a string for use in a SQL statement
 *
 * Escapes the value given in the first argument. All other arguments are
 * ignored and only used, when given, by the MDB2 equivalent function.
 *
 * @param      string    $val        string that is to be escaped
 * @return     mixed     escaped string, or FALSE on error
 * @see        http://www.php.net/mysql_real_escape_string, dp_db_query,
 *             dp_db_exec
 */
function dp_db_quote($val, $type = NULL, $quote = TRUE, $escWildcards = FALSE)
{
    if (!($link = &dp_db_connect())
            || FALSE === ($val = mysql_real_escape_string($val, $link))) {
        return FALSE;
    }

    return "'" . $val . "'";
}

/**
 * Gets number of rows in result
 *
 * Prints an error message to the server output in case of an error
 *
 * @param      resource  $result     result resource from dp_db_query()
 * @return     mixed     number of rows in a result set, or FALSE on failure
 * @see        http://www.php.net/mysql_num_rows, dp_db_query
 */
function dp_db_num_rows($result)
{
    $num_rows = mysql_num_rows($result);
    if (FALSE === $num_rows) {
        echo "dp_db_num_rows: Failed to get number of rows\n";
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
 * @return     mixed     integer with next id, or FALSE on failure
 * @see        dp_db_exec
 */
function dp_db_next_id($table, $idColumn)
{
    if (!($link = &dp_db_connect())) {
        return FALSE;
    }

    $result = mysql_query($sql = "SELECT MAX($idColumn) FROM $table", $link);
    if (!$result) {
        echo mysql_error($link) . ' [error number ' . mysql_errno($link)
            . "]\n";
        return FALSE;
    }
    $max_id = mysql_result($result, 0, 0);
    if (FALSE === $max_id) {
        echo "dp_db_fetch_one: Failed to fetch next id\n";
        return FALSE;
    }
    mysql_free_result($result);

    return $max_id + 1;
}

/**
 * Frees result memory
 *
 * @param      resource  $result     result resource from dp_db_query()
 * @return     boolean   TRUE on success or FALSE on failure
 * @see        http://www.php.net/mysql_free_result, dp_db_query,
 *             dp_db_fetch_one, dp_db_fetch_row
 */
function dp_db_free($result)
{
    return is_resource($result) && mysql_free_result($result);
}
?>
