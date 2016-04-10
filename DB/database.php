<?php
namespace DB;

class Database
{
    public $id = 0;
    private $dsn       = 'mysql:dbname=chess;host=localhost';
    private $user       = 'root';
    private $password   = '';
    private $connect;

    public function __construct() {

        $this->connect = new \stdClass();

        try{
            $this->connect = new \PDO($this->dsn, $this->user, $this->password);
            $this->connect->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);


        } catch (\PDOException $e) {
            echo 'Connection failed: ' . $e->getMessage();
        }

    }
    public function getGame($id)
    {
        try{
            $sth = $this->connect->prepare("SELECT * FROM games WHERE id = ?");
            $sth->execute(array($id));
        } catch(\PDOException $e){
            echo 'could not get game ' . $e->getMessage();
        }

        $result = $sth->fetch(\PDO::FETCH_ASSOC);

        return $result['game'];
    }
}
?>
