<?php
class Database{
    private $host= "localhost";
    private $db_name= "yaman";
    private $username= "root";
    private $password= "";
    public $con;
    public function getconnection(){
        try{
                     $this->con = new PDO(
                "mysql:host=$this->host;dbname=$this->db_name", // DSN (Data Source Name)
                $this->username,   // DB username
                $this->password    // DB password
            );

                  $this->con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->con->exec("set names utf8");
        }catch(PDOException $e){
            echo "Connection error:" . $exception->getMessage();
        }
        return $this-> con;
        }
    }
?>
