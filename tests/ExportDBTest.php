<?php

namespace PHPForms\Tests;

class ExportDBTest extends \PHPUnit\Framework\TestCase
{
    /**
     * PDO instance
     *
     * @var \PDO
     */
    protected static $pdo;

    /**
     * Database details
     *
     * @return void
     */
    protected static $db = [
        'table' => 'testing',
        'host' => '127.0.0.1',
        'db'   => 'phpforms_test',
        'user' => 'root',
        'pass' => '',
        'charset' => 'utf8',
        'opt' => [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ],
    ];

    /**
     * Create PDO instance
     *
     * @return void
     */
    public static function setUpBeforeClass()
    {
        $dsn = 'mysql:host='.self::$db['host'].';dbname='.self::$db['db'].';charset='.self::$db['charset'].'';
        self::$pdo = new \PDO($dsn, self::$db['user'], self::$db['pass'], self::$db['opt']);
    }

    public function testWeCanSaveDataToDatabase()
    {
        $data = [
            'name'    => 'Mehdi',
            'email'   => 'email@example.com',
            'age'     => '19',
            'message' => 'This is a test',
        ];

        // Expected data is $data + id
        $expected_data = array_merge($data, ['id' => 1]);

        $db = new \PHPForms\ExportDB(self::$pdo, self::$db['table'], $data);

        $this->assertEquals(true, $db->export());

        // Get data back
        $stmt = self::$pdo->query('SELECT * FROM `'.self::$db['table'].'`');
        $fetched_data = $stmt->fetch(\PDO::FETCH_ASSOC);


        $this->assertEquals($expected_data, $fetched_data);
    }

    public function testWeCanSetTable()
    {
        $data = [
            'name'    => 'Mehdi',
            'email'   => 'email@example.com',
            'age'     => '19',
            'message' => 'This is a test',
        ];

        // Expected data is $data + id
        $expected_data = array_merge($data, ['id' => 1]);

        $db = new \PHPForms\ExportDB(self::$pdo, null, $data);

        $db->setTable(self::$db['table']);

        $this->assertEquals(true, $db->export());

        // Get data back
        $stmt = self::$pdo->query('SELECT * FROM `'.self::$db['table'].'`');
        $fetched_data = $stmt->fetch(\PDO::FETCH_ASSOC);


        $this->assertEquals($expected_data, $fetched_data);
    }

    public function testWeCanMapData()
    {
        $data = [
            'a_name'    => 'Mehdi',
            'an_email'   => 'email@example.com',
            'an_age'     => '19',
            'comment' => 'This is a test',
        ];

        $expected_data = [
            'id'      => '1',
            'name'    => 'Mehdi',
            'email'   => 'email@example.com',
            'age'     => '19',
            'message' => 'This is a test',
        ];

        $map = [
            'a_name'   => 'name',
            'an_email' => 'email',
            'an_age'   => 'age',
            'comment'  => 'message',
        ];

        $db = new \PHPForms\ExportDB(self::$pdo, self::$db['table'], $data, $map);

        $this->assertEquals(true, $db->export());

        // Get data back
        $stmt = self::$pdo->query('SELECT * FROM `'.self::$db['table'].'`');
        $fetched_data = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->assertEquals($expected_data, $fetched_data);
    }

    public function testWeCanSetMap()
    {
        $data = [
            'a_name'    => 'Mehdi',
            'an_email'   => 'email@example.com',
            'an_age'     => '19',
            'comment' => 'This is a test',
        ];

        $expected_data = [
            'id'      => '1',
            'name'    => 'Mehdi',
            'email'   => 'email@example.com',
            'age'     => '19',
            'message' => 'This is a test',
        ];

        $map = [
            'a_name'   => 'name',
            'an_email' => 'email',
            'an_age'   => 'age',
            'comment'  => 'message',
        ];

        $db = new \PHPForms\ExportDB(self::$pdo, self::$db['table'], $data);

        $db->setMap($map);

        $this->assertEquals(true, $db->export());

        // Get data back
        $stmt = self::$pdo->query('SELECT * FROM `'.self::$db['table'].'`');
        $fetched_data = $stmt->fetch(\PDO::FETCH_ASSOC);

        $this->assertEquals($expected_data, $fetched_data);
    }

    /**
     * Truncate the table
     *
     * @return void
     */
    public function tearDown()
    {
        // Truncate the table
        self::$pdo->query('TRUNCATE `'.self::$db['table'].'`');
    }

    /**
     * Destroy PDO instance
     *
     * @return void
     */
    public static function tearDownAfterClass()
    {
        self::$pdo = null;
    }
}
