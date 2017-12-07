<?php declare(strict_types=1);

namespace Authenticator;

use Dibi\Connection;
use Dibi\Fluent;
use Nette\Security\AuthenticationException;
use Nette\Security\IAuthenticator;
use Nette\Security\Passwords;
use Nette\Security\Identity;
use Nette\SmartObject;


/**
 * Class DibiAuthenticator
 *
 * @author  geniv
 * @package Authenticator
 */
class DibiAuthenticator implements IAuthenticator
{
    use SmartObject;

    // define constant table names
    const
        TABLE_NAME = 'identity';

    /** @var Connection database connection from DI */
    private $connection;
    /** @var string table names */
    private $tableIdentity;


    /**
     * DibiAuthenticator constructor.
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
     * Get list.
     *
     * @return Fluent
     */
    public function getList(): Fluent
    {
        return $this->connection->select('id, login, hash, username, email, role, active, added')
            ->from($this->tableIdentity);
    }


    /**
     * Insert user.
     *
     * @param        $username
     * @param        $password
     * @param string $role
     * @param bool   $active
     * @return mixed
     * @throws \Dibi\Exception
     */
    public function insertUser($username, $password, $role = 'guest', $active = true)
    {
        $args = [
            'login'     => $username,
            'hash'      => $this->getHash($password),
            'role'      => $role,
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
    public function getHash(string $password): string
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
    public function authenticate(array $credentials): Identity
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

                    return new Identity($result['id'], $result['role'], $arr);
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
