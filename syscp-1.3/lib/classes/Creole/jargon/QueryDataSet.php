<?php
/*
 *  $Id: QueryDataSet.php,v 1.4 2005/07/13 17:28:13 hlellelid Exp $
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
 * 
 * This product includes software based on the Village framework,  
 * http://share.whichever.com/index.php?SCREEN=village.
 */
 
include_once 'jargon/DataSet.php';
 
/**
 * This class is used to represent the results of a SQL select statements on the database.
 * It should not be used for doing modifications via update/delete/insert statements. If you 
 * would like to perform those functions, please use a TableDataSet.
 * 
 * <code>
 * $qds = new QueryDataSet($conn, "SELECT * from my_table");
 * $qds->fetchRecords(10); // fetch the first 10 records
 * foreach($qds as $rec) {
 *   $value = $rec->getValue("column");
 * }
 * $qds->close();
 * </code>
 * 
 * @author    Jon S. Stevens <jon@latchkey.com> (Village)
 * @author    Hans Lellelid <hans@xmpl.org> (Jargon)
 * @version   $Revision: 1.4 $
 * @package   jargon
 */
class QueryDataSet extends DataSet {

    /**
     * Creates a new QueryDataSet based on a connection and a select string
     *
     * This class can be instantiated with a couple signatures:
     *    - new QueryDataSet($conn, "SELECT * FROM mytable");
     *    - new QueryDataSet($rs);
     * 
     * @param  mixed $p1 Connecton or ResultSet (depending on signature)
     * @param  string $selectStmt SELECT SQL (only if $p1 is Connection)
     */
    public function __construct($p1, $selectSql = null) 
    {
        if ($selectSql !== null) {
            $this->conn = $p1;
            $this->selectSql = $selectSql;
        } else {
            $this->resultSet = $p1;
        }
    }

    /**
     * get the Select String that was used to create this QueryDataSet
     *
     * @return     a select string
     */
    public function getSelectSql()
    {
        return $this->selectSql;
    }
    
}
