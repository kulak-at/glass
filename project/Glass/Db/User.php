<?php

namespace Increment\Db;

class User extends Abstrct {
	
	/**
	 * Returns hash for password
	 * @return string Hashed password
	 */
	private function hashed($pass) {
		return hash("sha256", $this->_getConfig('salt') . $pass );
	}
	
	/**
	 * Returns user's data or FALSE
	 * @param int $id if of an user.
	 * @return array user's data
	 */
	public function getUser($id) {
		$sql = 'SELECT * FROM users
				WHERE id=:id';
		$stmt = $this->db->prepare($sql);
		$stmt->execute(array(
			'id' => $id
		));
		$row = $stmt->fetch();
		return $row;
	}
	
	/**
	 * Returns user's data or FALSE
	 * @param string $email user's email.
	 * @return array user's data
	 */
	public function getUserByMail($mail) {
		$sql = 'SELECT * FROM users
				WHERE mail=:mail';
		$stmt = $this->db->prepare($sql);
		$stmt->execute(array(
			'mail' => $mail
		));
		$row = $stmt->fetch();
		return $row;
	}
	
	/**
	 * Returns user's data or FALSE
	 * @param string $email user's email.
	 * @param string $password user's password (in plain text)
	 * @return array user's data
	 */
	public function getAuthorizedUser($mail, $password) {
		$sql = 'SELECT * FROM users
				WHERE mail=:mail AND password=:password';
		$stmt = $this->db->prepare($sql);
		$stmt->execute(array(
			'mail' => $mail,
			'password' => $this->hashed($password)
		));
		$row = $stmt->fetch();
		return $row;
	}
	
	/**
	 * Checks if user with given id has such password.
	 * @param int $id user's id
	 * @param string $password plain text password
	 * @return boolean if password is correct.
	 */
	public function checkPassword($id, $password) {
		$sql = 'SELECT * FROM users
				WHERE id=:id AND password=:password';
		$stmt = $this->db->prepare($sql);
		$stmt->execute(array(
			'id' => $id,
			'password' => $this->hashed($password)
		));
		$row = $stmt->fetch();
		return $row != false;
	}
	
	/**
	 * Changes user's password.
	 * @param int $user_id id of user
	 * @param string $old_pass current password
	 * @param string $pass new password
	 * @return boolean TRUE if password was changed, FALSE otherwise.
	 */
	public function changePassword($user_id, $old_pass, $pass) {
		$sql = 'UPDATE users 
				SET password=:pass
				WHERE id=:user_id AND password=:old_pass';
		$stmt = $this->db->prepare($sql);
		$stmt->execute(array(
			'pass' => $this->hashed($pass),
			'user_id' => $user_id,
			'old_pass' => $this->hashed($old_pass)
		));
		return $stmt->rowCount() != 0;
	}
	
	/**
	 * Creates a new user account.
	 * @param string $name name
	 * @param string $surname surname
	 * @param string $mail e-mail
	 * @param string $pass password
	 * @return boolean TRUE if account was created, FALSE otherwise.
	 */
	public function createAccount($name, $surname, $mail, $pass) {
		$sql = 'INSERT INTO users (mail, name, surname, password)
				VALUES (:mail, :name, :surname, :pass)';
		$stmt = $this->db->prepare($sql);
		$res = $stmt->execute(array(
			'mail' => $mail,
			'name' => htmlspecialchars($name),
			'surname' => htmlspecialchars($surname),
			'pass' => $this->hashed($pass)
		));
		return $res;
	}
	
  /**
   * Deletes user from database by mail.
   * @param string $mail user mail
   * @return TRUE on one or more deletions, FALSE on failure
   */
  public function removeUserByMail($mail) {
    $sql = 'DELETE FROM users
            WHERE mail = :mail';
		$stmt = $this->db->prepare($sql);
		$stmt->execute(array(
			'mail' => $mail
		));
		return $stmt->rowCount() != 0;
  }
	
	
	/**
	* Validate an email address.
	* Returns true if the email address has the email 
	* address format and the domain exists.
	* @param string $email Email address (raw input)
	* @author Douglas Lovell
	* @link http://www.linuxjournal.com/article/9585?page=0,3
	*/
	public function isValidEmail($email)
	{
		$isValid = true;
		$atIndex = strrpos($email, "@");
		if (is_bool($atIndex) && !$atIndex)
		{
			$isValid = false;
		}
		else
		{
			$domain = substr($email, $atIndex+1);
			$local = substr($email, 0, $atIndex);
			$localLen = strlen($local);
			$domainLen = strlen($domain);
			if ($localLen < 1 || $localLen > 64)
			{
				// local part length exceeded
				$isValid = false;
			}
			else if ($domainLen < 1 || $domainLen > 255)
			{
				// domain part length exceeded
				$isValid = false;
			}
			else if ($local[0] == '.' || $local[$localLen-1] == '.')
			{
				// local part starts or ends with '.'
				$isValid = false;
			}
			else if (preg_match('/\\.\\./', $local))
			{
				// local part has two consecutive dots
				$isValid = false;
			}
			else if (!preg_match('/^[A-Za-z0-9\\-\\.]+$/', $domain))
			{
				// character not valid in domain part
				$isValid = false;
			}
			else if (preg_match('/\\.\\./', $domain))
			{
				// domain part has two consecutive dots
				$isValid = false;
			}
			else if (!preg_match('/^(\\\\.|[A-Za-z0-9!#%&`_=\\/$\'*+?^{}|~.-])+$/', str_replace("\\\\","",$local)))
			{
				// character not valid in local part unless 
				// local part is quoted
				if (!preg_match('/^"(\\\\"|[^"])+"$/',
					str_replace("\\\\","",$local)))
				{
					$isValid = false;
				}
			}
			if ($isValid && !(checkdnsrr($domain,"MX") || checkdnsrr($domain,"A")))
			{
				// domain not found in DNS
				$isValid = false;
			}
		}
		return $isValid;
	}
	
	
	
}
