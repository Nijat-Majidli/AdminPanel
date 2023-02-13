<?php

include_once("class/Upload.class.php");
include_once("class/Database.class.php");

// Database::__connect();

// SELECT * FROM "corporate"
// Database::table("corporate");

// SELECT title, description, id FROM "corporate"
// Database::table("corporate")->select("title, description, id");
// Database::table("corporate")->select(["title, description", "id"]);



// SELECT * FROM "corporate" WHERE title LIKE "%a%" AND description="bilgi"
Database::table("corporate")
->select(["id", "title", "description"])
->whereRaw("title LIKE '%?%' AND description=?", ["a", "bilgi"]);


?>