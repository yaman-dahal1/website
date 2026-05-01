<?php
class Database{
    private $host= "localhost";
    private $db-name= "yaman";
    private $username= "root";
    private $password= "";
    public $con;
    public function getconnection(){
        try{
            $this->con= new PDO(
                "mysql:host= $this-> host; dbname=$this-> yaman"
                $this-> username,
                $this-> password
            );
                $this-> con-> setAttribute(PDO::ATTER_ERRMODE,PDO::ERRMODE_EXCEPTION);
                $this->con->exec("set names utf8");
        }catch(PDOException $e){
            echo "Connection error:" . $exception->getMessage();
        }
        return $this-> conn;
        }
    }
?>
