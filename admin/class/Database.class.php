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


    // Constructor method calls __connect() method
    function __construct() { self::__connect(); }     
    

    // Creates database connection
    public static function __connect()
    {
        try{
            // $db = new PDO("mysql:host=localhost;dbname=adminpanel;charset=utf8", "root", "");
            self::$connection = new PDO("mysql:host=".self::HOST.";dbname=".self::DATABASE.";charset=utf8", self::USERNAME, self::PASSWORD);
            
            return "Database connection successfully established !";
        } 
        catch(PDOException $error){
            // By using (Object) syntaxe we convert array $data into an object 
            $data =(Object) [
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
        self::$whereRawKey = null;
        self::$whereRawValue = null;
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

    

}


?>