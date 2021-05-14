<?php
// gestionaire collection
// https://code.tutsplus.com/en/tutorials/pdo-vs-mysqli-which-should-you-use--net-24059

class Model
{
  /********************************/
  // Définition des attributs
  /********************************/

  private $connection;

  private $driver;
  private $host;
  private $bd_name;

  private $id;
  private $mdp;

  private static $instance = null;

  /********************************/
  // Déclaration du constructeur
  /********************************/

  function __construct()
  {
    if(isset($_GET['driver'])) {
      $this->driver = $_GET['driver'];
    }
    if(isset($_GET['host'])) {
      $this->host = $_GET['host'];
    }
    if(isset($_GET['name'])) {
      $this->bd_name = $_GET['name'];
    }
    if(isset($_GET['id'])) {
      $this->id = $_GET['id'];
    }
    if(isset($_GET['mdp'])) {
      $this->mdp = $_GET['mdp'];
    }
    echo "Sent Information : ";
    echo $this->driver.",".$this->host.",".$this->bd_name.",".$this->id.",".$this->mdp."<br/>";
  }

  /********************************/
  // Déclaration setters et getters
  /********************************/

  public function get_host() {
    return $this->host;
  }

  public function set_host() {
    return $this->host;
  }

  public function get_driver() {
    return $this->driver;
  }

  public function set_driver() {
    return $this->driver;
  }

  public function get_bd_name() {
    return $this->bd_name;
  }

  public function set_bd_name() {
    return $this->bd_name;
  }

  public function get_id() {
    return $this->id;
  }

  public function set_id() {
    return $this->id;
  }

  public function get_mdp() {
    return $this->mdp;
  }

  public function set_mdp() {
    return $this->mdp;
  }

  /********************************/
  // Déclaration des méthodes
  /********************************/

  function get_available_drivers() {
    echo "Available Drivers : ";
    foreach (PDO::getAvailableDrivers() as $key => $value) {
      echo $value." , ";
    };
    echo "</br>";
  }

  function get_php_info() {
    phpinfo();
  }

  // Fonction permettant de seconnecter à une database

  function connect() {
    echo "Test Connection"."</br>";
    switch ($this->driver) {
    case "mysql":
        echo "Connection to MySQL"."<br/>";
        $this->connect_mysql();
        break;
    case "oci":
        echo "Connection to Oracle"."<br/>";
        $this->connect_oracle();
        break;
    }
  }

  function connect_mysql() {
    try {
      $this->connection = new PDO($this->driver.":host=".$this->host.";dbname=".$this->bd_name,$this->id,$this->mdp);
      $this->connection->query("SET NAMES 'utf8'");
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      echo "Connected to database"."</br>";
    } catch(PDOException $e) {
      echo 'ERROR: '.$e->getMessage();
    }
  }

  function connect_oracle() {
    try {
      $port = "1521";

      // Permet de faire le lien avec une base de donnée Oracle
      // en utilisant la mise en forme du tnsnames.ora
      $lien_base =
      "oci:dbname=(DESCRIPTION =
      (ADDRESS_LIST =
        (ADDRESS =
          (PROTOCOL = TCP)
          (Host = ".$this->host .")
          (Port = ".$port."))
      )
      (CONNECT_DATA =
        (SERVICE_NAME = ".$this->bd_name.")
      )
      )";

      $this->connection = new PDO($lien_base,$this->id,$this->mdp);
      $this->connection->query("SET NAMES 'utf8'");
      $this->connection->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
      echo "Connected to database"."</br>";
    } catch(PDOException $e) {
      echo 'ERROR: '.$e->getMessage();
    }
  }

  function disconnect() {
    $this->connection = null;
  }

  function ask_db($command)
  {
    $requete = $this->connection->prepare($command);
    $requete->execute();
    $result = $requete->fetchAll(PDO::FETCH_ASSOC);
    $nb_row = $requete->rowCount();
    echo '<table id="result_table">';

    if($nb_row != 0) {
      echo '<tr>';
      foreach ($result[0] as $key => $value) {
        echo '<td>'.$key.'</td>';
      }
      echo '<tr/>';
    }

    foreach($result as $row) {
      echo '<tr>';
      foreach ($row as $key => $value) {
        echo '<td>'.$value.'</td>';
      }
      echo '<tr/>';
    }
    echo '</table>';
  }
}
?>
