<?php
declare(strict_types=1);
namespace Daniilprusakov\TudaykaBot\classes;
use PDO;
use PDOException;
use PDOStatement;


class Db
{
    private PDO $conn;

    public function __construct(array $config_db)
    {
        $dsn = "mysql:host={$config_db['host']};dbname={$config_db['dbname']};charset={$config_db['charset']}";
        try {
            $this->conn = new PDO($dsn, $config_db['user'], $config_db['password'], $config_db['options']);
        } catch(PDOException $e) {
            die("Ошибка подключения: " . $e->getMessage());
        }
    }

    /**
     * @param string $sql
     * @param array $params
     * @return PDOStatement
     */
    public function query(string $query, array $params = []): PDOStatement
    {
        $stmt = $this->conn->prepare($query);
        $stmt->execute($params);
        return $stmt;
    }

}