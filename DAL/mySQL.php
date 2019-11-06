<?php

//////////////////////////////////////////////////////////////////////////////////////////////
//
// Module de génération de code sql et de gestion de base de données 
//
// Auteur : Nicolas Chourot dans le cadre du cours 420-KB9
// Date : 28 octobre 2019
//
//////////////////////////////////////////////////////////////////////////////////////////////
 
date_default_timezone_set('US/Eastern');

//////////////////////////////////////////////////////////////////////////////////////////////
//
// classe TableAccess
//
// Auteur : Nicolas Chourot dans le cadre du cours 420-KB9
// Date : 28 octobre 2019
////////////////////////////////////////////////////////////////////////////////////////////////
//
//  Cette classe offre les services de génération de requêtes SQL en se basant sur les
//  menbres de la classe dérivées.
//
//  Le type des membres est déterminé soit automatique soit par le commentaire
//  formaté au dessus de leur déclaration.
//
//  Typage automatique:
//
//  En se basant sur le type de la valeur affectée au membre lors de la construction:
//      - string sera considéré comme étant de type VARCHAR(65535)
//      - integer sera considéré comme étant de type INT
//      - double sera considéré comme étant de type DOUBLE
//
//  Si l'indentificateur d'un membre
//      - commence par _ il sera ignorer
//      - égal à "Id" il sera considéré comme la clé primaire
//      - contient "Id" il sera considéré comme une clé étrangère
//      - contient "Date" il sera considéré comme un champ de type DateTime
//      - égal à "Password" il sera traité comme une champ encrypté
//      - contient 'GUID' il sera considéré comme un champ contenant un code chiffré unique     
//  
//  Typage manuel :
//
//      Le commentaire qui précède le membre devra être du format : /** ... */
//      par exemple:
//          ...
//          /** VARCHAR(14) */
//          public $Cellphone;
//
//          /** VARCHAR(255) */
//          public $Email;
//
//          Si le commentaire contient NULL le champ pourra être null
//          
//////////////////////////////////////////////////////////////////////////////////////////////
abstract class TableAccess {
        protected $_dataBaseAccess = null;

        abstract public function init();

        protected function __construct($_dataBaseAccess) {
            $this->init();
            $this->_dataBaseAccess = $_dataBaseAccess;
            $this->create_if_Does_Not_Exist();
        }
        private function typeFromDocComment($docComment) {
            return str_replace('*','', str_replace('/','',$docComment));
        }
        private function getType($fieldName, $value) {
            $className = $this->className();
            $prop = new ReflectionProperty( $className, $fieldName);
            $docComment = trim($this->typeFromDocComment($prop->getDocComment()));
            if ($docComment) {
                $docCommentParts = explode(' ',$docComment);
                if (strpos($fieldName, 'Id')) {
                    if ($fieldName === 'Id') {
                        $type['type'] ='primary_key';
                    } else {
                        $type['type'] = 'foreign_key';
                    }
                } else {
                    $type['type'] = str_replace(' ', '', $docCommentParts[0]);
                }
                if ($fieldName !== 'Id') {
                    $type['null'] = isset($docCommentParts[1]) ? str_replace(' ', '', $docCommentParts[1]) === 'NULL' : false;
                } else {
                    $type['null'] = false;
                }
                return $type;
            }
            $phpType = gettype($value);
            if ($phpType === 'integer') {
                    if ($fieldName === 'Id')
                        $phpType ='primary_key';
                    else 
                        if (strpos($fieldName, 'Id'))
                            $phpType = 'foreign_key';
                        else
                            $phpType = 'INT';
            }
            if (strpos($fieldName, 'Date')) {
                $phpType ='date';
            }
            if ($fieldName === 'Password') {
                $phpType ='password';
            }
            if (strpos($fieldName, 'GUID')) {
                $phpType ='guid';
            }
            $type['type'] = $phpType;
            return $type;
        }
        private function Convert($target, $value) {
            $tType = gettype($target);
            if ($tType !== 'string') {
                $vType = gettype($value);
                if ( $vType === 'string') {
                    switch ($tType) {
                        case 'integer' : return intval($value);
                        case 'double' : return doubleval($value);
                    }
                }
            }
            return $value;
        }
        private function className() {
            return get_class($this);
        }
        private function excludedMember($memberName) {
            $firstCharacter = substr($memberName, 0, 1);
            return $firstCharacter === '_';    
        }
        public function create_Table() {
            $tableName = $this->tableName();
            $sql = 'CREATE TABLE ' . $tableName .' (';
            $primaryKey = null;
            $foreignKeys = [];
            foreach ($this as $key => $value) {
                if (!$this->excludedMember($key)) {
                    $type = $this->getType($key, $value);
                    $primaryKeyFlag = false;
                    switch($type['type']){
                        case 'primary_key': 
                            $primaryKey = "PRIMARY KEY ($key)";
                            $sqlType = 'INT'; 
                            $primaryKeyFlag = true;
                            break;
                        case 'foreign_key': 
                            $fkTable = str_replace('Id','',$key).'s ';
                            $foreignKeys[] = "FOREIGN KEY ($key) REFERENCES $fkTable(Id)";
                            $sqlType = 'INT';
                            break;
                        case 'string': 
                            $sqlType = 'VARCHAR(655335)'; 
                            break;
                        case 'password':
                            $hashSample = password_hash('sample', PASSWORD_DEFAULT);
                            $hashSampleLength = strlen($hashSample) + 1;
                            $sqlType = "VARCHAR($hashSampleLength)"; 
                            break;
                        case 'integer':
                            $sqlType = 'INT'; 
                            break;
                        case 'date':
                            $sqlType = 'DATETIME'; 
                            break;
                        case 'double':
                            $sqlType = 'DOUBLE'; 
                            break;
                        case 'guid':
                            $guidSample = com_create_guid();
                            $guidSampleLength = strlen($guidSample) + 1;
                            $sqlType = "VARCHAR($guidSampleLength)";
                            break;
                        default:
                            $sqlType = $type['type'];
                    }
                    $sql .= "$key $sqlType ";
                    if ($primaryKeyFlag)
                        $sql .= "AUTO_INCREMENT ";
                    if (!isset($type['null']))
                        $sql .= "NOT NULL";
                    else
                        if (!$type['null'])
                            $sql .= "NOT NULL";
                    $sql .= ', ';
                }
            } 
            $sql .= $primaryKey.', ';
            foreach($foreignKeys as $fk)   {
                $sql .= $fk.', ';
            }
            $sql = rtrim($sql,', ');
            $sql .=');';
            //echo $sql.'<br><br>';
            return $sql;
        }
        public function tableName() {
            return $this->className();
        }
        private function prepareForSQL(&$value) {
            $phpType = gettype($value);
            if ($phpType === 'string')
                $value = "'".str_replace("'", "''", $value)."'";
        }
        public function bind($values) {
            foreach ($this as $key => $value) {
                if (!$this->excludedMember($key)) {
                    $this->$key = $this->convert($value, $values[$key]);
                }
            }
        }
        private function create_if_Does_Not_Exist () {
            $tableName = $this->tableName();
            $sql = "select * from $tableName";
            $exist = $this->_dataBaseAccess->querySqlCmd($sql);
            if (!$exist) {
                $this->_dataBaseAccess->nonQuerySqlCmd($this->create_Table());
            }
        }
        public function get($id = '') {
            if ($id !== '') {
                $data = $this->selectById($id);
                return $data[0];
            } else {
                $data = $this->selectAll();
                return $data;
            }
        }
        public function selectAll($orderBy = null) {
            $tableName = $this->tableName();
            $sql = "SELECT * FROM $tableName";
            if (isset($orderBy)) 
                $sql .= " $orderBy";
            $data = $this->_dataBaseAccess->querySqlCmd($sql );
            return $data;
        }
        public function selectById($id) {
            $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
            $tableName = $this->tableName();
            $sql = "SELECT * FROM $tableName WHERE Id = $id";
            $data = $this->_dataBaseAccess->querySqlCmd($sql);
            return $data;
        }
        public function selectWhere($criteria = null) {
            $tableName =$this->tableName();
            $sql = "SELECT * FROM $tableName WHERE $criteria";
            $data = $this->_dataBaseAccess->querySqlCmd($sql);
            return $data;
        }
        public function insert($data) {
            if (isset($data)) {
                $this->bind($data);
                $tableName = $this->tableName();
                $sql = 'INSERT INTO ' . $tableName .' ('; 
                foreach ($this as  $key => $value) {
                    if (!$this->excludedMember($key)) {
                        if ($key !== 'Id'){
                            $sql .= $key.', ';
                        }
                    }
                }
                $sql = rtrim($sql,', ').') values ( ';
                foreach ($this as $key => $value) {
                    if (!$this->excludedMember($key)) {
                        if ($key !== 'Id') {
                            $phpType = gettype($value);
                            if ($key === 'Password') {
                                $value = password_hash($value, PASSWORD_DEFAULT);
                            }
                            $this->prepareForSQL($value);
                            $sql .= $value.', ';
                        }
                    }
                }
                $sql = rtrim($sql,', ').')';
                return $this->_dataBaseAccess->nonQuerySqlCmd($sql);
            }
            return 0;
        }
        public function update($data) {
            if (isset($data)) {
                $this->bind($data);
                $tableName = $this->tableName();
                $sql = 'UPDATE ' . $tableName .' set '; 
                foreach ($this as $key => $value) {
                    if (!$this->excludedMember($key)) {
                        if (($key !== 'Id') && ($key !== 'Password')) {
                            $this->prepareForSQL($value);
                            $sql .= $key.' = '.$value.', ';
                        }
                    }
                }
                $sql = rtrim($sql,', ');
                $sql .= ' WHERE Id = '.$this->Id;
                $this->_dataBaseAccess->nonQuerySqlCmd($sql);
            }
        }
        public function update_Including_Password($data) {
            if (isset($data)) {
                $this->bind($data);
                $tableName = $this->tableName();
                $sql = 'UPDATE ' . $tableName .' set '; 
                foreach ($this as $key => $value) {
                    if (!$this->excludedMember($key)) {
                        if ($key !== 'Id') {
                            $phpType = gettype($value);
                            if ($key === 'Password') {
                                $value = password_hash($value, PASSWORD_DEFAULT);
                            }
                            $this->prepareForSQL($value);
                            $sql .= $key.' = '.$value.', ';
                        }
                    }
                }
                $sql = rtrim($sql,', ');
                $sql .= ' WHERE Id = '.$this->Id;
                $this->_dataBaseAccess->nonQuerySqlCmd($sql);
            }
        }
        public function delete($id) {
            if (isset($id)) {
                $id = filter_var($id, FILTER_SANITIZE_NUMBER_INT);
                $tableName = $this->tableName();
                $sql = "DELETE FROM $tableName WHERE Id = $id";
                $this->_dataBaseAccess->nonQuerySqlCmd($sql);
            }
        }
        public function deleteWhere($criteria) {
            $tableName = $this->tableName();
            $sql = "DELETE FROM $tableName WHERE $criteria";
            $this->_dataBaseAccess->nonQuerySqlCmd($sql);
        }
        public function deleteAll() {
            $tableName =$this->tableName();
            $sql = "DELETE FROM $tableName";
            $this->_dataBaseAccess->nonQuerySqlCmd($sql);
        }
        public static function JoinHelper(  $tableX, 
                                            $JoinTable, 
                                            $tableY, 
                                            $selection, 
                                            $criteria) {
            $fkX = mb_substr($tableX, 0, -1).'Id';
            $fkY = mb_substr($tableY, 0, -1).'Id';
            $sql = "SELECT $selection 
                    FROM $tableX, $JoinTable, $tableY 
                    WHERE $JoinTable.$fkX = $tableX.Id 
                    AND $JoinTable.$fkY = $tableY.Id 
                    AND $criteria";
            return $sql;
        }
    }

//////////////////////////////////////////////////////////////////////////////////////////////
//
// classe _DataBaseAccess
//
// Auteur : Nicolas Chourot dans le cadre du cours 420-KB9
// Date : 28 octobre 2019
////////////////////////////////////////////////////////////////////////////////////////////////
//
//  Cette classe permet d'établir une connection à une base de données MySql.
//  Elle permet aussi d'éxécuter des requêtes SQL avec ou sans transaction.
//
////////////////////////////////////////////////////////////////////////////////////////////////
final class DataBaseAccess {
        private static $_instance = null;
        private $host;
        private $username;
        private $password;
        private $dbName;
        private $autoCommit;
        private $conn;

        // Ici les réglages pourraient êtres stockés dans un fichier de constantes globales de l'application
        public function __construct($dbName) {    
            $this->host = 'localhost'; 
            $this->username = 'root'; 
            $this->password = ''; 
            $this->dbName = $dbName;
            $this->autoCommit = true; 
            $this->conn = null; 
        } 
        public static function getInstance($dbName) {
            if(is_null(self::$_instance)) {
                self::$_instance = new DataBaseAccess($dbName);  
            }
            return self::$_instance;
        }
        private function hostConnect() {
            if ($this->conn === null){
                $this->conn = new PDO(  "mysql:host=$this->host;", 
                                        $this->username, 
                                        $this->password);
                $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->conn->beginTransaction();
            }
        }
        private function DBConnect() {
            if ($this->conn === null){
                try {
                    if (!$this->exist()) {
                        $this->create();
                        $this->conn = null;
                    }
                    $this->conn = new PDO(  "mysql:host=$this->host;  
                                            dbname=$this->dbName;", 
                                            $this->username, 
                                            $this->password);
                    $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                    $this->conn->beginTransaction();
                } 
                catch (PDOException $e) {
                    var_dump($e);
                }
            }
        }
        private function disconnect() {
            if ($this->autoCommit)
                $this->conn = null;
        }
        private function commit() {
            if ( $this->conn !== null) {
                if ($this->autoCommit) {
                    $this->conn->commit();
                    $this->disconnect();
                }
            }
        }
        private function rollBack() {
            if ( $this->conn !== null) {
                $this->autoCommit = true;
                $this->conn->rollBack();
                $this->disconnect();
            }
        }       
        public function exist() {
            if ($this->conn !== null)
                return true;
            else {
                try {
                    $conn = new PDO("mysql:host=$this->host; dbname=$this->dbName;", $this->username, $this->password);
                    if ($conn !== null) {
                        $conn = null;
                        return true;
                    }
                }
                catch (PDOException $e) {
                    return false;
                }
            }
            return false;
        }
        public function beginTransaction() {
            $this->autoCommit = false;
        }
        public function endTransaction() {
            $this->autoCommit = true;
            $this->commit();
        }
        public function create() {
            $success = true;
            try {
                $this->hostConnect();
                $this->conn->exec("CREATE DATABASE $this->dbName");
            }
            catch(PDOException $e) {
                $this->rollBack();
                $success = false;
            }
            $this->commit();    
            return $success;
        }
        public function Delete() {
            $success = true;
            try {
                $this->hostConnect();
                $this->conn->exec("DROP DATABASE $this->dbName");  
            }
            catch(PDOException $e) {
                $this->rollBack();
                $success = false;
            }
            $this->commit();    
            return $success;
        }
        public function lastInsertedId() {
            if ($this->conn !== null){
                return $this->conn->lastInsertId();
            }
            return 0;
        }
        public function nonQuerySqlCmd($sql) {
            $recordsAffected = 0;
            try {
                $this->DBConnect();
                $this->conn->exec($sql);
                $this->commit();
                $recordsAffected = $this->lastInsertedId();
            }
            catch(PDOException $e) {
                $this->rollBack();
                $recordsAffected = 0;
            }
            return $recordsAffected;
        }
        public function querySqlCmd($sql) {
            try {
                $this->DBConnect();
                $rows = $this->conn->query($sql)->fetchAll();
            }
            catch(PDOException $e) {
                return [];
            }
            $this->disconnect();  
            return $rows;
        }
    }
 

?>