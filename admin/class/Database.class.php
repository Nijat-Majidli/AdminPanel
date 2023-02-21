<?php

class Database extends Upload 
{
    // We create some constants and static properties
    const HOST="localhost";
    const DATABASE="adminpanel";
    const USERNAME="root";
    const PASSWORD="";
    protected static $connection;
    public static $table;
    public static $select="*";
    public static $whereRawKey;
    public static $whereRawValue;
    public static $whereKey;
    public static $whereValue=array();
    public static $orderBy=null;
    public static $limit=null;
    public static $join="";
    public static $leftJoin="";


    // Constructor method calls __connect() method
    function __construct() { self::__connect(); }     
    

    // Creates database connection
    public static function __connect()
    {
        try
        {
            // $db = new PDO("mysql:host=localhost;dbname=adminpanel;charset=utf8", "root", "");
            self::$connection = new PDO("mysql:host=".self::HOST.";dbname=".self::DATABASE.";charset=utf8", self::USERNAME, self::PASSWORD);
            
            return "Database connection successfully established !";
        } 
        catch(PDOException $error)
        {
            // (Object) converts array $data into an object 
            $data = (Object) [
                "title"=>"Connection Error",
                "code"=>$error->getCode(),
                "description"=> "Database connection failed.",  
                "message"=>$error->getMessage()
            ];

            return self::errorPage("errorPage", $data);
            exit();
        }
    }


    // Displays error page if database connection fails
    public static function errorPage($pagename, $errorData)
    {
        $fileHref = "errors/".$pagename.".php";     // $fileHref = "errors/errorPage.php" 
        
        if(file_exists($fileHref))
        {
            include_once($fileHref);
        }
        elseif(file_exists("../".$fileHref))
        {
            include_once("../".$fileHref);
        }
    }


    // Returns a new Database class with public static $table = $tableName
    public static function table($tableName)
    {
        self::$table = $tableName;
        self::$select = "*";
        self::$whereRawKey=null;
        self::$whereRawValue=null;
        self::$whereKey=null;
        self::$whereValue=array();
        self::$orderBy=null;
        self::$limit=null;
        self::$join="";
        self::$leftJoin="";

        return new self;    // self keyword refers to Database class
    }


    // Returns a new Database class with public static $select = $columns
    public static function select($columns)
    {
        self::$select = (is_array($columns)) ? implode(", ", $columns) : $columns;
        return new self;    
    }


    // Returns a new Database class with public static $whereRawKey = $RawKey and $whereRawValue = $RawValue
    public static function whereRaw($RawKey, $RawValue)
    {
        self::$whereRawKey = $RawKey;
        self::$whereRawValue = $RawValue;
        return new self;
    }

    
    // Returns a new Database class with public static $whereKey = $param and $whereValue = $param2 ou $param3
    public static function where($param1, $param2=null, $param3=null)
    {
        if(is_array($param1))
        {
            $keyList = array();

            foreach($param1 as $key=>$value)
            {
                $keyList[] = $key;
                self::$whereValue[] = $value;   
            }

            self::$whereKey = implode("=? AND ", $keyList)."=?";
        }
        else if($param2!=null && $param3==null)
        {
            self::$whereValue[] = $param2;
            self::$whereKey = $param1."=?";
        }
        else if($param3!=null)
        {
            self::$whereValue[] = $param3;
            self::$whereKey = $param1.$param2."?"; 
        }
        
        return new self;
    }


    // 
    public static function orderBy($parameter)
    {
        self::$orderBy = $parameter[0]." ".(!empty($parameter[1]) ? $parameter[1] : "ASC");
        return new self;
    }


    // 
    public static function limit($start, $end=null)
    {
        self::$limit = $start.($end!=null ? ",".$end : "");
        return new self;
    }


    public static function join($tableName, $thisColumn, $joinColumn)
    {
        self::$join.="INNER JOIN ".$tableName." ON ".self::$table.".".$thisColumn."=".$tableName.".".$joinColumn." "; 
        return new self;
    }


    public static function leftJoin($tableName, $thisColumn, $joinColumn)
    {
        self::$leftJoin.="LEFT JOIN ".$tableName." ON ".self::$table.".".$thisColumn."=".$tableName.".".$joinColumn." "; 
        return new self;
    }


    public static function get()
    {
        $SQL = "SELECT ".self::$select." FROM ".self::$table." ";
        $SQL.= !empty(self::$join) ? self::$join : " ";
        $SQL.= !empty(self::$leftJoin) ? self::$leftJoin : " ";
        
        $WHERE = null;

        if(!empty(self::$whereKey) && !empty(self::$whereRawKey))
        {
            $SQL.= "WHERE ".self::$whereKey." AND ".self::$whereRawKey." ";
            $WHERE = array_merge(self::$whereValue, self::$whereRawValue);
        }
        else
        {
            if(!empty(self::$whereKey))
            {
                $SQL.= "WHERE ".self::$whereKey." ";
                $WHERE = self::$whereValue;
            }   
            if(!empty(self::$whereRawKey))
            {
                $SQL.= "WHERE ".self::$whereRawKey." ";
                $WHERE = self::$whereRawValue;
            }
        }

        $SQL.= !empty(self::$orderBy) ? "ORDER BY ".self::$orderBy." " : "";
        $SQL.= !empty(self::$limit) ? "LIMIT ".self::$limit : "";

        if($WHERE!=null)
        {
            $Entity = self::$connection->prepare($SQL);
            $Entity->execute($WHERE);
        }
        else
        {
            $Entity = self::$connection->query($SQL);
        }
        
        $Result = $Entity->fetchAll(PDO::FETCH_ASSOC);
        
        if($Result)
        {
            $data = [];
            
            foreach($Result as $item)
            {
                $data[] = (object) $item;
            }

            return $data;
        }
        else
        {
            return false;
        }

        
    }




}


?>