<?php

namespace Authenticator\Drivers;

use Dibi\Connection;
use Dibi\Fluent;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Passwords;
use Nette\Security\Identity;
use Nette\SmartObject;


/**
 * Class DibiDriver
 *
 * @author  geniv
 * @package Authenticator\Drivers
 */
class DibiDriver implements IAuthenticator
{
    use SmartObject;

    // define constant table names
    const
        TABLE_NAME = 'identity';

    /** @var Connection database connection from DI */
    private $connection;
    /** @var string table names */
    private $tableIdentity;
    /** @var array */
    private $columns = ['id', 'login', 'hash', 'username', 'email', 'id_role', 'active', 'added'];


    /**
     * DibiDriver constructor.
     *
     * @param array      $parameters
     * @param Connection $connection
     */
    public function __construct(array $parameters, Connection $connection)
    {
        $this->connection = $connection;
        // define table names
        $this->tableIdentity = $parameters['tablePrefix'] . self::TABLE_NAME;
    }


    /**
     * Get columns.
     *
     * @return array
     */
    public function getColumns()
    {
        return $this->columns;

    }


    /**
     * Set columns.
     *
     * @param $columns
     * @return $this
     */
    public function setColumns(array $columns)
    {
        $this->columns = $columns;
        return $this;
    }


    /**
     * Get list.
     *
     * @return Fluent
     */
    public function getList()
    {
        return $this->connection->select($this->columns)->from($this->tableIdentity);
    }


    /**
     * Insert user.
     *
     * @param      $login
     * @param      $password
     * @param null $role
     * @param bool $active
     * @return \Dibi\Result|int
     * @throws \Dibi\Exception
     */
    public function insertUser($login, $password, $role = null, $active = true)
    {
        // insert to base colums
        $args = [
            'login'     => $login,
            'hash'      => $this->getHash($password),
            'id_role'   => $role,
            'active'    => $active,
            'added%sql' => 'NOW()',
        ];
        return $this->connection->insert($this->tableIdentity, $args)->execute();
    }


    /**
     * Delete user.
     *
     * @param $id
     * @return Result|int
     * @throws \Dibi\Exception
     */
    public function deleteUser($id)
    {
        return $this->connection->delete($this->tableIdentity)
            ->where(['id' => $id])
            ->execute();
    }


    /**
     * Get hash.
     *
     * @param string $password
     * @return string
     */
    public function getHash($password)
    {
        return Passwords::hash($password);
    }


    /**
     * Authenticate.
     *
     * @param array $credentials
     * @return Identity
     * @throws AuthenticationException
     */
    public function authenticate(array $credentials)
    {
        list($login, $password) = $credentials;

        $result = $this->getList()
            ->where(['login' => $login, 'active' => true])
            ->fetch();

        if ($result) {
            if (Passwords::verify($password, $result['hash'])) {
                if ($result['active']) {
                    $arr = $result->toArray();
                    unset($arr['hash']);

                    return new Identity($result['id'], $result['id_role'], $arr);
                } else {
                    throw new AuthenticationException('Not active account.', self::NOT_APPROVED);
                }
            } else {
                throw new AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);
            }
        } else {
            throw new AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);
        }
    }
}
