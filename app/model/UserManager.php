<?php

namespace App\Model;

use Nette\Object,
	Nette\Database,
	Nette\Security;


/**
 * Users management.
 */
class UserManager extends Object implements Security\IAuthenticator
{
	const
		TABLE_NAME = 'users',
		COLUMN_ID = 'id',
		COLUMN_NAME = 'username',
		COLUMN_PASSWORD_HASH = 'password',
		COLUMN_ROLE = 'role';


	/** @var Database\Context */
	private $database;


	public function __construct(Database\Context $database)
	{
		$this->database = $database;
	}


	/**
	 * Performs an authentication
	 *
	 * @param array $credentials
	 * @return Security\Identity|Security\IIdentity
	 * @throws Security\AuthenticationException
	 */
	public function authenticate(array $credentials)
	{
		list($username, $password) = $credentials;

		$row = $this->database->table(self::TABLE_NAME)->where(self::COLUMN_NAME, $username)->fetch();

		if (!$row) {
			throw new Security\AuthenticationException('The username is incorrect.', self::IDENTITY_NOT_FOUND);

		} elseif (!Security\Passwords::verify($password, $row[self::COLUMN_PASSWORD_HASH])) {
			throw new Security\AuthenticationException('The password is incorrect.', self::INVALID_CREDENTIAL);

		}

		if (Security\Passwords::needsRehash($row[self::COLUMN_PASSWORD_HASH])) {
			$row->update(array(
				self::COLUMN_PASSWORD_HASH => Security\Passwords::hash($password),
			));
		}

		$arr = $row->toArray();
		unset($arr[self::COLUMN_PASSWORD_HASH]);

		return new Security\Identity($row[self::COLUMN_ID], $row[self::COLUMN_ROLE], $arr);
	}


	/**
	 * Adds new user
	 *
	 * @param $username
	 * @param $password
	 */
	public function add($username, $password)
	{
		$this->database->table(self::TABLE_NAME)->insert(array(
			self::COLUMN_NAME => $username,
			self::COLUMN_PASSWORD_HASH => Security\Passwords::hash($password),
		));
	}

}
