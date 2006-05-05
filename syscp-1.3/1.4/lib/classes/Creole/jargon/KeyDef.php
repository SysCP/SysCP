<?php

/*
 *  $Id: KeyDef.php,v 1.3 2004/03/20 04:16:51 hlellelid Exp $
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

/**
 * A KeyDef is a way to define the key columns in a table.
 * 
 * The KeyDef is generally used in conjunction with a TableDataSet. 
 * Essentially a KeyDef is what forms the WHERE clause for an UPDATE or DELETE.
 * 
 * In order to use the KeyDef, you simply use it like this:
 * <code>
 * $kd = new KeyDef()
 * $kd->addAttrib("key_column_a");
 * 
 * $tds = new TableDataSet($conn, "table", $kd);
 * $tds->fetchRecords();
 * $rec = $tds->getRecord(0);
 * $rec->setValue("column_name", "new value" );
 * $rec->save();
 * $tds->close();
 * </code>
 * 
 * In the above example, Record 0 is retrieved from the database table 
 * and the following update statement is generated:
 * <pre>
 * UPDATE table SET column_name=? WHERE key_column_a=?
 * </pre>
 * 
 * @see TableDataSet::doUpdate()
 * @see TableDataSet::doDelete()
 * @see TableDataSet::refresh()
 *
 * @author    Jon S. Stevens <jon@latchkey.com> (Village)
 * @author    Hans Lellelid <hans@xmpl.org> (Jargon)
 * @version   $Revision: 1.3 $
 * @package   jargon
 */
class KeyDef {
    
    /** @var array Array of key column names */
    private $cols;        
        
    /** Number of columns. */
    private $size;

    /**
     * Construct a keydef.
     * 
     * Accepts a variable number of arguments -- a list
     * of coluns to use for keydef:
     * <code>
     *   $kd = new KeyDef('id'); // id col is pkey
     *   $kd = new KeyDef('key1', 'key2'); // multi-col pkey
     * </code>
     */
    public function __construct()
    {
        $this->cols = func_get_args();
        $this->size = count($this->cols);
    }
    
    /**
     * Adds the named attribute to the KeyDef.
     * @param string $name
     * @return KeyDef The modified class.
     */
    public function addAttrib($name)
    {
        $this->cols[] = $name;
        $this->size++;
        return $this;
    }

    /**
     * Determines if the KeyDef contains the requested Attribute.
     * @param string $name
     * @return boolean True if the attribute has been defined. false otherwise.
     */
    public function containsAttrib($name)
    {
        return in_array($name, $this->cols, true);
    }

    /**
     * getAttrib is 1 based. Setting pos to 0 will attempt to return pos 1.
     * @param int $pos 1-based position of attrib.
     * @return string Value of attribute at pos as String. null if value is not found.
     */
    public function getAttrib($pos)
    {
        if ($pos === 0) $pos = 1;
        return @$this->cols[$pos - 1];
    }

    /**
     * Returns number of columns in KeyDef.
     * @return int The number of elements in the KeyDef that were set by addAttrib()
     * @see addAttrib()
     */
    public function size()
    {
        return $this->size;
    }
    
}
