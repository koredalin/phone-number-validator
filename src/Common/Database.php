<?php

namespace App\Common;

use App\Common\Interfaces\DbInstanceInterface;

/**
 * Description of Batabase
 *
 * @author Hristo
 */
class Database implements DbInstanceInterface
{
    public function generate(): \PDO
    {
        try {
            $pdo = new \PDO(DB_DSN, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $e) {
            echo $e->getMessage();
        }
        
        return $pdo;
    }
}
