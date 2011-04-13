<?php
require __DIR__ . '/../src/User.php';
require __DIR__ . '/../src/UserMapper.php';

class UserMapperTest extends PHPUnit_Extensions_Database_TestCase
{
    protected $db;
        
    protected function getConnection()
    {
        $this->db = new PDO('sqlite:' . __DIR__ . '/../example-test.db');
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        return $this->createDefaultDBConnection($this->db, 'testdb');
    }

    protected function getDataSet()
    {
        return $this->createFlatXMLDataSet(__DIR__ . '/../data/user.xml');
    }

    /**
     * @expectedException OutOfBoundsException
     */
    public function testFindByIdWithOutOfBoundsIdThrowsException() 
    {
        $userMapper = new UserMapper($this->db);
        $user = $userMapper->findById(2);
    }

    public function testFindByIdWithValidIdReturnsUser()
    {
        $userMapper = new UserMapper($this->db);
        $user = $userMapper->findById(1);
        $this->assertTrue($user instanceof User);
        $this->assertEquals('ade', $user->getUsername());
    }

    public function testInsertingUserInsertsUser()
    {
        $userMapper = new UserMapper($this->db);
        $user = new User();
        $user->setUsername('terry_tibbs');
        $userMapper->insert($user); 
        $this->assertEquals(2, $this->getConnection()->getRowCount('user'));

        $expected = $this->createFlatXmlDataSet(__DIR__ . "/../data/expectedUser.xml");

        $actual = new PHPUnit_Extensions_Database_DataSet_QueryDataSet($this->getConnection());
        $actual->addTable('user');

        $this->assertDataSetsEqual($expected, $actual);
    }

    public function testRemovingUserRemovesUser()
    {
        $userMapper = new UserMapper($this->db);
        $user = $userMapper->findById(1);
        $userMapper->delete($user);

        $this->assertEquals(0, $this->getConnection()->getRowCount('user'));
    }
}
