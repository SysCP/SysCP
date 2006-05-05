<?php

/*
 *  $Id: Record.php,v 1.10 2005/07/13 17:28:13 hlellelid Exp $
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

require_once 'creole/CreoleTypes.php';

// These classes must be included so the "instanceof" calls
// below will be able to function properly.
include_once 'jargon/QueryDataSet.php';
 
/**
 * A Record represents a row in the database. It contains a hash of 
 * values which represent the column values for each row.
 *
 * @author    Jon S. Stevens <jon@latchkey.com> (Village)
 * @author    Hans Lellelid <hans@xmpl.org> (Jargon)
 * @version   $Revision: 1.10 $
 * @package   jargon
 */
class Record {
    
    // saveType constants
    const ZOMBIE = -1;
    const UNKNOWN = 0;
    const INSERT = 1;
    const UPDATE = 2;
    const DELETE = 3;
    const BEFOREINSERT = 4;
    const AFTERINSERT = 5;
    const BEFOREUPDATE = 6;
    const AFTERUPDATE = 7;
    const BEFOREDELETE = 8;
    const AFTERDELETE = 9;
    
    /** an array of values strings, indexed by column name.*/
    private $values = array();
    
    /** array of modified (dirty) columns */
    private $dirtyCols = array();
    
    /** the parent DataSet for this Record */
    private $ds;
        
    /** this is the state of this record */
    private $saveType = 0;
    
    /**
     * Creates a new Record and sets the parent dataset to the passed in value.
     * 
     * If $addRecord is true, then an empty record is created.
     * 
     * @param DataSet $ds The parent / owning dataset.
     * @param boolean $addRecord Whether to create an empty record.
     */
    function __construct(DataSet $ds, $addRecord = false)
    {
        $this->setParentDataSet($ds);
        if (!$addRecord) {        
            $this->createValues($this->ds->resultSet());
        }
    }
    
    /**
     * Performs initialization for this Record.
     */
    private function initializeRecord() 
    {
        $this->values = array();
        $this->dirtyCols = array();
        $this->setSaveType(Record::UNKNOWN);                
    }
    
    /**
     * Creates the value objects for this Record. It is 1 based
     * 
     * @return void
     */
    private function createValues(ResultSet $rs)
    {
        $this->values = $rs->getRow();
    }

    /**
     * Shortcut method to delete this record.
     * @param Connection $conn
     */
    public function delete(Connection $conn = null)
    {
        $this->setSaveType(DELETE);
        $this->save($conn);
    }
    
    /**
     * Saves the data in this Record to the database.
     * @param Connection $conn
     * @return boolean True if the save completed. false otherwise.
     * @throws DataSetException
     */
    public function save(Connection $conn = null) 
    {
        $returnValue = false;

        if ($this->ds instanceof QueryDataSet) {
            throw new DataSetException("You cannot save a QueryDataSet. Please use a TableDataSet instead.");
        }

        if (!$this->needsToBeSaved()) {
            return $returnValue;
        }

        switch($this->saveType) {            
            case Record::INSERT:
                $returnValue = $this->doInsert($conn);
                break;
            case Record::UPDATE:
                $returnValue = $this->doUpdate($conn);
                break;                
            case Record::DELETE:
                $returnValue = $this->doDelete($conn);
                break;
            default:
                throw new DataSetException("Invalid or no-action saveType for Record.");
        }
                
        return (boolean) $returnValue;
    }

    /**
     * Performs a DELETE on databse using this Record as criteria.
     * @return int Number of rows affected by delete.
     * @throws DataSetException, SQLException
     */
    private function doDelete(Connection $conn = null) 
    {
        if ($conn === null) {
            $conn = $this->ds->connection();
        }
        
        $table = $this->ds->tableInfo();
        $stmt = null;
        try {
            $stmt = $conn->prepareStatement($this->getDeleteSql());
            $ps = 1;
            $kd = $this->ds->keydef();
            for ($i = 1, $kdsize = $kd->size(); $i <= $kdsize; $i++) {                
                $col = $kd->getAttrib($i);
                $val = $this->getValue($col);                    
                $setter = 'set' . CreoleTypes::getAffix( $table->getColumn($col)->getType() );
                $stmt->$setter($ps++, $val);
            }

            $ret = $stmt->executeUpdate();

            // note that the actual removal of the Record objects 
            // from the DataSet is done by the TDS::save() method.
            $this->setSaveType(Record::ZOMBIE);
            
            $stmt->close();
            
            if ($ret > 1) {
                throw new SQLException("There were " . $ret . " rows deleted with this records key value.");
            }
            
            return $ret;
            
        } catch (SQLException $e) {
            if ($stmt) $stmt->close();
            throw $e;
        }
    }

    /**
     * Saves the data in this Record to the database with an UPDATE statement.
     * @return SQL UPDATE statement
     * @throws DataSetException, SQLException
     */
    private function doUpdate(Connection $conn = null)
    {
        if ($conn === null) {
            $conn = $this->ds->connection();
        }
        
        $table = $this->ds->tableInfo();
        
        $stmt = null;
        try {
            $stmt = $conn->prepareStatement($this->getUpdateSql());
            
            $ps = 1;
            foreach($this->dirtyColumns() as $col) {
                $setter = 'set' . CreoleTypes::getAffix( $table->getColumn($col)->getType() );
                $stmt->$setter($ps++, $this->getValue($col));
            }

            $kd = $this->ds->keydef();
            for ($i = 1, $kdsize = $kd->size(); $i <= $kdsize; $i++) {
                $attrib = $kd->getAttrib($i);
                $setter = 'set' . CreoleTypes::getAffix( $table->getColumn($attrib)->getType() );
                $stmt->$setter($ps++, $this->getValue($attrib));
            }
            
            $ret = $stmt->executeUpdate();

            if ($this->ds->refreshOnSave()) {
                $this->refresh();
            } else {
                // Marks all of the values clean since they have now been saved
                $this->markRecordClean();
            }

            $this->setSaveType(Record::AFTERUPDATE);

            if ($ret > 1) {
                throw new SQLException ("There were " . $ret . " rows updated with this records key value.");
            }            
            return $ret;
        } catch (SQLException $e) {
            if ($stmt) $stmt->close();
            throw $e;
        }
        
    }

    /**
     * Saves the data in this Record to the database with an INSERT statement
     * @return int
     * @throws DataSetException, SQLException
     */
    private function doInsert(Connection $conn = null)
    {
        $stmt = null;

        try {
            $stmt = $conn->prepareStatement($this->getInsertSql());
            $ps = 1;
            foreach($this->dirtyColumns() as $col) {
                $val = $this->getValue($col);
                $setter = 'set' . CreoleTypes::getAffix( $table->getColumn($col)->getType() );
                $stmt->$setter($ps++, $val);
            }
            
            $ret = $stmt->executeUpdate();

            if ($this->ds->refreshOnSave()) {
                $this->refresh();
            } else {
                // Marks all of the values clean since they have now been saved
                $this->markRecordClean();
            }

            $this->setSaveType(Record::AFTERINSERT);

            if ($ret > 1) {
                // a little late again...
                throw new SQLException ("There were " . $ret . " rows inserted with this records key value.");
            }

            return $ret;
        } catch (SQLException $e) {
            if ($stmt) $stmt->close();
            throw $e;
        }        
    }

    /**
     * Builds the SQL UPDATE statement for this Record
     * @return string SQL UPDATE statement
     * @throws DataSetException
     */
    private function getUpdateSql() 
    {
        $kd = $this->ds->keydef();
        if ($kd === null || $kd->size() === 0) {
            throw new DataSetException("You must specify KeyDef attributes for this TableDataSet in order to create a Record for update.");
        } elseif ($this->recordIsClean()) {
            throw new DataSetException ("You must Record->setValue() on a column before doing an update.");
        }

        $set_sql = "";
        $where_sql = "";
        
        $comma = false;
        
        foreach($this->dirtyColumns() as $col)  {
            if (!$comma) {
                $set_sql .= $col . " = ?";
                $comma = true;
            } else {
                $set_sql .= ", " . $col . " = ?";
            }            
        }

        $comma = false;               
        for ($i = 1, $kdsize = $kd->size(); $i <= $kdsize; $i++) {        
            $attrib = $kd->getAttrib($i);
            if (! $this->valueIsClean ($attrib)) {
                throw new DataSetException ("The value for column '" . $attrib . "' is a key value and cannot be updated.");
            }
            if (!$comma) {
                $where_sql .= $attrib . " = ?";
                $comma = true;
            } else {
                $where_sql .= " AND " . $attrib . " = ?";
            }
        }
        return "UPDATE " . $this->ds->tableName() . " SET " . $set_sql . " WHERE " . $where_sql;
    }    
    

    /**
     * Builds the SQL DELETE statement for this Record.
     * @return string SQL DELETE statement
     * @throws DataSetException - if no keydef
     */
    private function getDeleteSql() 
    {
        $kd = $this->ds->keydef();
        
        if ($kd === null || $kd->size() === 0) {
            throw new DataSetException("You must specify KeyDef attributes for this TableDataSet in order to delete a Record.");
        }

        $where_sql = "";
        $comma = false;
        for ($i = 1, $kdsize = $kd->size(); $i <= $kdsize; $i++) {
            if (!$comma) {
                $where_sql .= $kd->getAttrib($i) . " = ?";               
                $comma = true;
            } else {
                $where_sql .= " AND " . $kd->getAttrib($i) . " = ?";
            }
        }
        
        return "DELETE FROM " . $this->ds->tableName() . " WHERE " . $where_sql;
    }

    /**
     * Builds the SQL INSERT statement for this Record
     * @return string SQL INSERT statement
     */
    private function getInsertSql() 
    {
        $fields_sql = "";
        $values_sql = "";                
        $comma = false;
        foreach($this->dirtyColumns() as $col) {
            if (!$comma) {
                $fields_sql .= $col;
                $values_sql .= "?";
                $comma = true;
            } else {
                $fields_sql .= ", " . $col;                    
                $values_sql .= ", ?";
            }
        }
        return "INSERT INTO " . $this->ds->tableName() . " ( " . $fields_sql . " ) VALUES ( " . $values_sql . " )";
    }       

    /**
     * Gets the value for specified column.
     * This function performs no type-conversion.
     * @return string The value object for specified column as string.
     * @throws DataSetException
     */
    public function getValue($col) 
    {
        if (!isset($this->values[$col])) {
            throw new DataSetException("Undefined column in Record: " . $col);
        }        
        return $this->values[$col];
    }   

    /**
     * Get the column names for current record.
     * @return array Column names.
     */ 
    public function columns()
    {
        return array_keys($this->values);
    }

    /**
     * Get the modified (dirty) columns.
     * Private right now because this is only used internally.  No
     * real reason why this couldn't be public, though ...
     * @return array
     */
    private function dirtyColumns()
    {
        return array_keys($this->dirtyCols);
    }

    /**
     * The number of columns in this object.
     * @return the number of columns in this object
     */
    public function size()
    {
        return count($this->values);
    }
    
    /**
     * Whether or not this Record is to be saved with an SQL insert statement
     * @return boolean True if saved with insert
     */
    public function toBeSavedWithInsert()
    {
        return ($this->saveType === Record::INSERT);
    }
    
    /**
     * Whether or not this Record is to be saved with an SQL update statement
     * @return boolean True if saved with update
     */
    public function toBeSavedWithUpdate()
    {
        return ($this->saveType === Record::UPDATE);
    }
    
    /**
     * Whether or not this Record is to be saved with an SQL delete statement
     * @return boolean True if saved with delete
     */
    public function toBeSavedWithDelete()
    {
        return ($this->saveType === Record::DELETE);
    }

    /**
      * Marks all the values in this record as clean.
      * @return void
      */
    public function markRecordClean()
    {
        $this->dirtyCols = array();
    }

    /**
     * Marks this record to be inserted when a save is executed.
     * @return void
     * @throws DataSetException - if DataSet is not TableDataSet
     */
    public function markForInsert() 
    {
        if ($this->ds instanceof QueryDataSet) {
            throw new DataSetException ("You cannot mark a record in a QueryDataSet for insert");
        }
        $this->setSaveType(Record::INSERT);
    }
    
    /**
     * Marks this record to be updated when a save is executed.
     * @return void
     * @throws DataSetException - if DataSet is not TableDataSet
     */
    public function markForUpdate()
    {
        if ($this->ds instanceof QueryDataSet) {
            throw new DataSetException ("You cannot mark a record in a QueryDataSet for update");
        }
        $this->setSaveType(Record::UPDATE);
    }
    /**
     * Marks this record to be deleted when a save is executed.
     * @return void
     * @throws DataSetException - if DataSet is not TableDataSet
     */
    public function markToBeDeleted()
    {
        if ($this->ds instanceof QueryDataSet) {
            throw new DataSetException ("You cannot mark a record in a QueryDataSet for deletion");
        }
        $this->setSaveType(Record::DELETE);
        // return this;
    }

    /**
     * Unmarks a record that has been marked for deletion.
     * <P>
     * WARNING: You must reset the save type before trying to save this record again.
     *
     * @see markForUpdate()
     * @see markForInsert()
     * @see markToBeDeleted()
     * @throws DataSetException
     */
    public function unmarkToBeDeleted() 
    {
        if ($this->saveType === Record::ZOMBIE) {
            throw new DataSetException ("This record has already been deleted!");
        }   
        $this->setSaveType(UNKNOWN);
        //return $this;
    }
    
    /**
     * Marks a value with a given column name as clean (unmodified).
     * @param string $col
     * @return void
     */
    public function markValueClean($col)
    {
        unset($this->dirtyCols[$col]);
    }

    /**
     * Marks a value with a given column as "dirty" (modified).
     * @param string $col
     * @return void
     */
    public function markValueDirty($col)
    {
        $this->dirtyCols[$col] = true;
    }
   
    /**
     * Sets the internal save type as one of the defined privates (ie: ZOMBIE)
     * @param int $type
     * @return void
     */
    public function setSaveType($type)
    {
        $this->saveType = $type;
    }

    /**
     * Gets the internal save type as one of the defined privates (ie: ZOMBIE)
     * @return int
     */
    public function getSaveType()
    {
        return $this->saveType;    
    }
    
    /**
     * Sets the value of col.
     * @return Record this object.
     * @throws DataSetException
     */
    public function setValue ($col, $value) 
    {
        $this->values[$col] = $value;
        $this->markValueDirty($col);
        return $this;
    }

    /**
     * Determines if this record is a Zombie. A Zombie is a record that has been deleted from the
     * database, but not yet removed from the DataSet.
     *
     * @return boolean
     */
    public function isAZombie()
    {
        return ($this->saveType === Record::ZOMBIE);
    }

    /**
     * If the record is not clean, needs to be saved with an Update, Delete or Insert, it returns true.
     * @return boolean
     */
    public function needsToBeSaved()
    {
        return (!$this->isAZombie() || !$this->recordIsClean() || $this->toBeSavedWithUpdate() ||
                $this->toBeSavedWithDelete() || $this->toBeSavedWithInsert());
    }

    /**
     * Determines whether or not a value stored in the record is clean.
     * @return true if clean
     * @throws DataSetException
     */
    public function valueIsClean($column) 
    {
        if (!isset($this->values[$column])) {
            throw new DataSetException("Undefined column: ".$column);
        }
        return !isset($this->dirtyCols[$column]);
    }

    /**
     * Goes through all the values in the record to determine if it is clean or not.
     * @return true if clean
     */
    public function recordIsClean()
    {
        return empty($this->dirtyCols);
    }
        
    /**
     * This method refreshes this Record's Value's. It can only be performed on
     * a Record that has not been modified and has been created with a TableDataSet
     * and corresponding KeyDef.
     *
     * @param Connection $conn
     * @throws DataSetException
     * @throws SQLException
     */
    public function refresh(Connection $conn = null)
    {
        if ($conn === null) {
            $conn = $this->ds->connection();
        }
        
        if ($this->toBeSavedWithDelete()) {
            return;
        } elseif ($this->toBeSavedWithInsert()) {
            throw new DataSetException("There is no way to refresh a record which has been created with addRecord().");
        } elseif ($this->ds instanceof QueryDataSet) {
            throw new DataSetException ("You can only perform a refresh on Records created with a TableDataSet.");
        }

        $stmt = null;
        try {        
            $stmt = $conn->prepareStatement ($this->getRefreshSql());
            $ps = 1;
            $kd = $this->ds->keydef();
            for ($i = 1, $kdsize = $kd->size(); $i <= $kdsize; $i++)
            {
                $val = $this->getValue($kd->getAttrib($i));
                if ($val == null) {
                    throw new DataSetException ("You cannot execute an update with a null value for a KeyDef.");
                }                    
                $setter = 'set' . CreoleTypes::getAffix( $table->getColumn($col)->getType() );
                $stmt->$setter($ps++, $val);
            }
            $rs = $stmt->executeQuery();
            $rs->next();            
            $this->initializeRecord();
            $this->createValues($rs);
        } catch (SQLException $e) {
            if ($stmt) $stmt->close();
            throw $e;
        }
    }

    /**
     * This builds the SELECT statement in order to refresh the contents of
     * this Record. It depends on a valid KeyDef to exist and it must have been
     * created with a TableDataSet.
     *
     * @return string The SELECT SQL
     * @throws DataSetException
     */
    public function getRefreshSql()
    {
        if ($this->ds->keydef() === null || $this->ds->keydef()->size() === 0) {
            throw new DataSetException("You can only perform a getRefreshQueryString on a TableDataSet that was created with a KeyDef.");            
        } elseif ($this->ds instanceof QueryDataSet) {
            throw new DataSetException("You can only perform a getRefreshQueryString on Records created with a TableDataSet.");
        }
        
        $sql1 = "";
        $sql2 = "";
        $comma = false;

        foreach($this->columns() as $col) {
            if (!$comma) {                
                $attribs_sql .= $col;
                $comma = true;
            } else {
                $attribs_sql .= ", " . $col;
            }
        }

        $comma = false;

        for ($i = 1, $kdsize = $kd->size(); $i <= $kdsize; $i++) {
            $attrib = $kd->getAttrib($i);

            if (!$this->valueIsClean($attrib)) {
                throw new DataSetException (
                        "You cannot do a refresh from the database if the value " .
                        "for a KeyDef column has been changed with a Record.setValue().");
            }
            
            if (!$comma) {
                $where_sql .= $attrib . " = ?";
                $comma = true;
            } else {
                $where_sql .= " AND " . $attrib . " = ?";
            }
        }
        
        return "SELECT " . $attribs_sql . " FROM " . $this->ds->tableName() . " WHERE " . $where_sql;
    }    

    /**
     * Gets the DataSet for this Record.
     *
     * @return DataSet
     */
    public function dataset()
    {
        return $this->ds;
    }

    /**
     * Sets the parent DataSet for this record.
     * @param DataSet $ds
     */
    public function setParentDataSet(DataSet $ds)
    {
        $this->ds = $ds;
    }
    
    /**
     * This returns a representation of this Record.
     * @return string
     */
    public function __toString()
    {
        $sb = "{";
        foreach($this->columns() as $col) {
            $sb .= "'" . $this->getValue($col) . "',";
        }
        $sb = substr($sb, 0, -1);
        $sb .= "}";
        return $sb;
    }
    
}
