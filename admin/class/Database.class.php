<?php

class Database extends Upload 
{
    const HOST="localhost";
    const DATABASE="adminpanel";
    const CHARSET="utf8";
    const USERNAME="root";
    const PASSWORD="";
    protected static $connection;

    // Constructor method calls __connect() method
    function __construct() { self::__connect(); }     
    

    // Creates database connection
    public static function __connect()
    {
        try
        {
            // $db = new PDO("mysql:host=localhost;dbname=adminpanel;charset=utf8", "root", "");
            self::$connection = new PDO("mysql:host=".self::HOST.";dbname=".self::DATABASE.";charset=".self::CHARSET."", self::USERNAME, self::PASSWORD);
            
            echo "Database connection successfully established !";
        } 
        catch(PDOException $error)
        {
            // (Object) transforms array $errorData into an object :
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

}


?>