<?php

/*
 *  $Id: Query.php,v 1.9 2005/07/13 17:28:13 hlellelid Exp $
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS
 * "AS IS" AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT
 * LIMITED TO, THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR
 * A PARTICULAR PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT
 * OWNER OR CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL,
 * SPECIAL, EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT
 * LIMITED TO, PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE,
 * DATA, OR PROFITS; OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY
 * THEORY OF LIABILITY, WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT
 * (INCLUDING NEGLIGENCE OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE
 * OF THIS SOFTWARE, EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * This software consists of voluntary contributions made by many individuals
 * and is licensed under the LGPL. For more information please see
 * <http://creole.phpdb.org>.
 */

/**
 * Class for representing a SQL query for RETRIEVING results from a database.
 * 
 * Note that this class is for retrieving results and not performing updates.
 * 
 * Eventually this class may include methods which allow building of queries.  Currently this
 * just provides some convenience functions like getRows(), getCol() (based on PEAR::DB methods)
 * and getDataSet().
 * 
 * This class is extended by PagedQuery, a convenience class for handling paged queries. 
 * 
 * @author    Hans Lellelid <hans@xmpl.org>
 * @version   $Revision: 1.9 $
 * @package   jargon 
 */
class Query {
    
    /** @var Connection */
    protected $conn;
    
    /** @var string The SQL query. */
    protected $sql;
        
    /** @var int Max rows to return (0 means all) */
    protected $max = 0;
    
    /** @var int Start row (offset) */
    protected $start = 0;
    
    /**
     * Create a new Query.
     * @param Connection $conn
     * @param string $sql
     */
    public function __construct(Connection $conn, $sql = null)
    {
        $this->conn = $conn;
        $this->sql = $sql;
    }
    
    /**
     * Sets the SQL we are using.
     * @param string $sql
     */
    public function setSql($sql)
    {
        $this->sql = $sql;
    }
    
    /**
     * Sets the start row or offset.
     * @param int $v
     */
    public function setStart($v)
    {
        $this->start = $v;
    }
    
    /**
     * Sets max rows (limit).
     * @param int $v
     * @return void
     */
    public function setMax($v)
    {
        $this->max = $v;
    }
    
    /**
     * Gets array of rows (hashes).
     * @return array string[][] Array of row hashes.
     * @throws SQLException
     */
    public function getRows() 
    {
        $stmt = $this->conn->createStatement();
        if ($this->max) $stmt->setLimit($this->max);
        if ($this->start) $stmt->setOffset($this->start);
        $rs = $stmt->executeQuery($this->sql);
        $results = array();
        while($rs->next()) {
            $results[] = $rs->getRow();
        }
        $rs->close();
        $stmt->close();
        return $results;
    }

    /**
     * Gets first rows (hash).
     * Frees resultset.
     * @return array string[] First row.
     * @throws SQLException
     */
    public function getRow() 
    {
        $stmt = $this->conn->createStatement();
        if ($this->max) $stmt->setLimit($this->max);
        if ($this->start) $stmt->setOffset($this->start);
        $rs = $stmt->executeQuery($this->sql);
        $rs->next();
        $results = $rs->getRow();        
        $rs->close();
        $stmt->close();
        return $results;
    }
    
    /**
     * Gets array of values for first column in result set.
     * @return array string[] Array of values for first column.
     * @throws SQLException
     */
    public function getCol() 
    {
        $stmt = $this->conn->createStatement();
        if ($this->max) $stmt->setLimit($this->max);
        if ($this->start) $stmt->setOffset($this->start);
        $rs = $stmt->executeQuery($this->sql);
        $results = array();
        while($rs->next()) {
            $results[] = array_shift($rs->getRow());
        }
        $rs->close();
        $stmt->close();
        return $results;
    }
    
    /**
     * Gets value of first column of first returned row.
     * @return string Value for first column in first row.
     * @throws SQLException
     */
    public function getOne() 
    {
        $stmt = $this->conn->createStatement();
        if ($this->max) $stmt->setLimit($this->max);
        if ($this->start) $stmt->setOffset($this->start);
        $rs = $stmt->executeQuery($this->sql);
        $rs->next();
        $res = array_shift($rs->getRow());
        $rs->close();
        $stmt->close();
        return $res;
    }
    
    /**
     * Fetch the entire result set of a query and return it as an
     * associative array using the first column as the key.
     * 
     * Note: column names are not preserved when using this function.
     * 
     * <code>
     * For example, if the table 'mytable' contains:
     *
     *   ID      TEXT       DATE
     * --------------------------------
     *   1       'one'      944679408
     *   2       'two'      944679408
     *   3       'three'    944679408
     *
     * $q = new Query("SELECT id, text FROM mytable") 
     * $q->getAssoc() returns:
     *    array(
     *      '1' => array('one'),
     *      '2' => array('two'),
     *      '3' => array('three'),
     *    )
     * 
     * ... or call $q->getAssoc($scalar=true) to avoid wrapping results in an array (only
     * applies if only 2 cols are returned):
     *  array(
     *      '1' => 'one',
     *      '2' => 'two',
     *      '3' => 'three',
     *    )
     * </code>
     * Keep in mind that database functions in PHP usually return string
     * values for results regardless of the database's internal type.
     *
     * @param boolean $scalar Used only when the query returns
     * exactly two columns.  If TRUE, the values of second column are not
     * wrapped in an array.  Default here is false, in order to assure expected
     * behavior.
     * 
     * @return array Associative array with results from the query.
     * @author Lukas Smith <smith@backendmedia.com> (MDB)
     */
    public function getAssoc($scalar = false)
    {
        $stmt = $this->conn->createStatement();
        if ($this->max) $stmt->setLimit($this->max);
        if ($this->start) $stmt->setOffset($this->start);
        $rs = $stmt->executeQuery($this->sql);
        
        $numcols = null;
        $results = array();
        while($rs->next()) {
            $fields = $rs->getRow();
            if ($numcols === null) {
                $numcols = count($fields);
            }
            if (!$scalar || ($numcols > 2)) {
                $results[ array_shift($fields) ] = array_values($fields);
            } else {
                $results[ array_shift($fields) ] = array_shift($fields);
            }
        }
        
        $rs->close();
        $stmt->close();
        
        return $results;
    }
    
    /**
     * Gets a QueryDataSet representing results of this query.
     * 
     * The QueryDataSet that is returned will be ready to use (records will already
     * have been fetched).  Currently only QueryDataSets are returned, so you will
     * not be able to manipulate (update/delete/insert) Record objects in returned
     * DataSet.
     * 
     * <code>
     * $q = new Query("SELECT * FROM author");
     * $q->setLimit(10);
     * $qds = $q->getDataSet();
     * foreach($q->getDataSet() as $rec) {
     *     $rec->getValue("name");
     * }
     * </code>
     * 
     * @return QueryDataSet QDS containing the results.
     */
    public function getDataSet()
    {
        include_once 'jargon/QueryDataSet.php';
        $qds = new QueryDataSet($this->conn, $this->sql);
        $qds->fetchRecords($this->start, $this->max);
        return $qds;
    }
} 

