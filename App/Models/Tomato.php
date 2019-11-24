<?php

namespace App\Models;

use PDO;

class Tomato extends \Core\Model
{
    public static function getAll()
    {
        $db = static::getDB();
        $stmt = $db->query('SELECT * FROM tomatoes');
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
