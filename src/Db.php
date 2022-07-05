<?php

namespace kryten;

use PDO;
use PDOException;
use stdClass;
use PDOStatement;

/**
 * Database class
 *
 * @author kryten
 */
class Db
{

    /**
     * PDO Database connection
     * @var resource|null
     */
    private $connection = null;

    /**
     * Attempt connection to database
     * @throws PDOException
     */
    public function __construct()
    {
        $host = 'localhost';
        $db = 'test';
        $user = 'test_user';
        $pass = 'password';
        $charset = 'utf8mb4';

        $dsn = "mysql:host=$host;dbname=$db;charset=$charset";
        $options = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];
        
        $this->connection = new PDO($dsn, $user, $pass, $options);
    }

    /**
     * Checks RFID card and fetches employee details
     * Returns null if no card found or more than one employee returned
     * @param string $rfid
     * @return stdClass|null
     */
    public function checkRfidCard(string $rfid, ?PDOStatement $pdoStatement = null): ?stdClass
    {
        $query = "SELECT `employees`.`employee_id`, `employees`.`name`, `departments`.`department` 
                    FROM `rfid_cards` 
                    LEFT JOIN `employees` ON `employees`.`employee_id` = `rfid_cards`.`employee_id`
                    LEFT JOIN `employee_to_department` ON `employee_to_department`.`employee_id` = `employees`.`employee_id`
                    LEFT JOIN `departments` ON `departments`.`department_id` = `employee_to_department`.`department_id`
                    WHERE `rfid_cards`.`rfid` = :rfid;";

        if ($pdoStatement === null) {
            $pdoStatement = $this->connection->prepare($query);
        }
        $pdoStatement->execute(['rfid' => $rfid]);
        $result = $pdoStatement->fetchAll();
        if (empty($result)) {
            return null;
        }
        
        $employeeId = null;
        $employee = new stdClass();
        $employee->full_name = '';
        $employee->department = [];
        foreach ($result as $row) {
            if ($employeeId === null) {
                $employeeId = $row['employee_id'];
                $employee->full_name = $row['name'];
            }
            if ($employeeId === $row['employee_id']) {
                $employee->department[] = $row['department'];
            } else {
                return null; // more than one employee found!
            }
        }
        return $employee;
    }

}
