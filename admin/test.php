<?php

include_once("class/Upload.class.php");
include_once("class/Database.class.php");

// Database::__connect();

// SELECT * FROM "corporate"
// Database::table("corporate");

// SELECT title, description, id FROM "corporate"
// Database::table("corporate")->select("title, description, id");
// Database::table("corporate")->select(["title, description", "id"]);

// SELECT * FROM corporate WHERE title=? AND id=? AND description=?
// Database::table("corporate")->where(["title"=>"bilgi", "id"=>3, "description"=>"aciklama"]);
// echo Database::$whereKey;

// SELECT * FROM corporate WHERE id=3
// Database::table("corporate")->where("id", 3);
// echo Database::$whereKey;

// SELECT * FROM corporate WHERE id > 3
// Database::table("corporate")->where("id", '>', 3);
// echo Database::$whereKey;

// SELECT * FROM corporate WHERE title LIKE %a% ORDER BY id DESC LIMIT 1,5
// Database::table("corporate")->where("title", " LIKE ", "%a%")->orderBy(["id", "DESC"])->limit(1,5);
// echo Database::$orderBy;
// echo Database::$limit;

// SELECT * FROM corporate INNER JOIN categories ON corporate.category=categories.ID;
// Database::table("corporate")->join("categories", "category", "ID");
// echo Database::$join;


// SELECT corporate.id, corporate.title, corporate.description, categories.title AS categoryName 
// FROM corporate INNER JOIN categories ON corporate.category=categories.ID WHERE corporate.id=? 
// AND corporate.title LIKE "%a%" ORDER BY corporate.title DESC LIMIT 0,10
// $result = Database::table('corporate')
//             ->select(["corporate.id", "corporate.title", "corporate.description", "categories.title AS categoryName"])
//             ->join("categories", "category", "ID")
//             ->leftJoin("brands", "brandID", "ID")
//             ->where("corporate.id", 2)
//             ->whereRaw("corporate.description LIKE ?", ["%a%"])
//             ->orderBy(['corporate.title', 'ASC'])
//             ->limit(0,10)
//             ->first();

// $add = Database::table('corporate')
//         ->create([
//             "title"=>"Kalite Politikasi",
//             "description"=>"Kalite Aciklama Alani",
//             "category"=>1
//         ]);
// 
// if($add)
//     echo "Successfully added";
// else 
//     echo "Error occured";

// $update = Database::table("corporate")
//             ->where("ID", 4)
//             ->update([
//                 "title"=>"Guncelleme Basligi",
//                 "description"=>"Guncel Makale",
//                 "category"=>1
//             ]);

// if($update)
//     echo "Successfully updated";
// else 
//     echo "Error occured";


// $delete = Database::table("corporate")
//             ->where("ID", 4)
//             ->delete();

// if($delete)
//     echo "Successfully deleted";
// else 
//     echo "Error occured";


echo Database::table("corporate")->primaryID("categories");



?>