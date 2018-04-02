<?php 
namespace App\Models;

use Core\BaseModel;
use PDO;

class Indicador extends BaseModel
{
    protected $table = "indicador";
    private $pdo;
    
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
    }
    
    public function select()
    {
        $sql  = "";
        $sql .= "SELECT ";
        $sql .= "{$this->table}.id,";
        $sql .= "{$this->table}.indicador, ";
        $sql .= "{$this->table}.situation, ";
        $sql .= "{$this->table}.id_temporalidad, ";
        $sql .= "temporalidad.temporalidad, ";
        $sql .= "{$this->table}.id_tipo, ";
        $sql .= "tipo.tipo, ";
        $sql .= "{$this->table}.id_pilar, ";
        $sql .= "pilar.pilar, ";
        $sql .= "{$this->table}.id_pais, ";
        $sql .= "{$this->table}.id_area, ";
        $sql .= "{$this->table}.id_sede, ";
        $sql .= "{$this->table}.id_creator, ";
        $sql .= "{$this->table}.id_updater, ";
        $sql .= "{$this->table}.date_insert, ";
        $sql .= "{$this->table}.date_update ";
        $sql .= "FROM {$this->table} ";
        $sql .= "INNER JOIN temporalidad ON temporalidad.id = {$this->table}.id_temporalidad ";
        $sql .= "INNER JOIN tipo ON tipo.id = {$this->table}.id_tipo ";
        $sql .= "INNER JOIN pilar ON pilar.id = {$this->table}.id_pilar ";
        $sql .= "WHERE {$this->table}.deleted = 0 ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $stmt->closeCursor();
        return $result;
    }
    
    public function ListaTemporalidad()
    {
        $sql  = "";
        $sql .= "SELECT * FROM temporalidad ";
        $sql .= "WHERE deleted = 0 ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $stmt->closeCursor();
        return $result;
    }
    
    public function ListaTipo()
    {
        $sql  = "";
        $sql .= "SELECT * FROM tipo ";
        $sql .= "WHERE deleted = 0 ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $stmt->closeCursor();
        return $result;
    }
    
    public function ListaPilar()
    {
        $sql  = "";
        $sql .= "SELECT * FROM pilar ";
        $sql .= "WHERE deleted = 0 ";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->fetchAll();
        $stmt->closeCursor();
        return $result;
    }
    
    public function GuardarIndicador($aParam)
    {
        $sql  = "";
        $sql .= "INSERT INTO {$this->table} (";
        $sql .= "id, ";
        $sql .= "indicador, ";
        $sql .= "id_temporalidad, ";
        $sql .= "id_tipo, ";
        $sql .= "id_pilar, ";
        $sql .= "id_pais, ";
        $sql .= "id_area, ";
        $sql .= "id_sede, ";
        $sql .= "id_creator, ";
        $sql .= "id_updater, ";
        $sql .= "date_insert, ";
        $sql .= "date_update, ";
        $sql .= "situation, ";
        $sql .= "deleted) VALUES (";
        $sql .= " NULL, ";
        $sql .= "'". $aParam['indicador']."', ";
        $sql .= "'". $aParam['temporalidad']."', ";
        $sql .= "'". $aParam['tipo']."', ";
        $sql .= "'". $aParam['pilar']."', ";
        $sql .= "'". $aParam['pais']."', ";
        $sql .= "'". $aParam['area']."', ";
        $sql .= "'". $aParam['sede']."', ";
        $sql .= "'". $_SESSION['Planificacion']['user_id']."', ";
        $sql .= " 0, ";
        $sql .= " NOW(), ";
        $sql .= " '0000-00-00 00:00:00', ";
        $sql .= "'". $aParam['status']."', ";
        $sql .= " 0)";
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute();
        $result = $stmt->rowCount();
        $stmt->closeCursor();
        
        return $result;
    }
    
}
