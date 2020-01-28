<?php

//DDL for Assignment
//create table author(
//	 authorId binary(16) not null,
//  authorActivationToken char(32),
//  authorAvatarUrl varchar(255),
//  authorEmail varchar(128) not null,
//  authorHash char(97) not null,
//  authorUsername varchar(32) not null,
//  unique(authorEmail),
//  unique(authorUsername),
//  primary key(authorId)
//);
namespace Vlad11793\ObjectOrientated;
require_once("autoload.php");
require_once(dirname(__DIR__) . "/vendor/autoload.php");

use Ramsey\Uuid\Uuid;



class Author implements \JsonSerializable {
	//use ValidateDate;
	use ValidateUuid;

	/**
	 * id for this Author; this is the primary key
	 * @var Uuid $authorId
	 **/
	private $authorId;
	/**
	 * Activation Token for this Author
	 * @var string $authorActivationToken
	 **/
	private $authorActivationToken;
	/**
	 * Url for Avatar pic of Author
	 * @var $authorAvatarUrl
	 **/
	private $authorAvatarUrl;
	/**
	 * Author's Email  not null, unique(authorEmail),
	 * @var $authorEmail
	 **/
	private $authorEmail;
	/**
	 * Author's Hash
	 *Hash char(97) not null,
	 * @var $authorHash
	 **/
	private $authorHash;
	/**
	 * Author's Username
	 *Hash varchar(32) not null, unique(authorUsername)
	 * @var $authorUsername
	 **/
	private $authorUsername;

	/**
	 * constructor for this Author
	 *
	 * @param string|Uuid $newAuthorIdid of this Tweet or null if a new Tweet
	 * @param string $newAuthorAvatarUrl string containing url.
	 * @throws \InvalidArgumentException if data types are not valid
	 * @throws \RangeException if data values are out of bounds (e.g., strings too long, negative integers)
	 * @throws \TypeError if data types violate type hints
	 * @throws \Exception if some other exception occurs
	 * @Documentation https://php.net/manual/en/language.oop5.decon.php
	 **/

	public function __construct($newAuthorId, $newAuthorActivationToken, string $newAuthorAvatarUrl, $newAuthorEmail, $newAuthorHash, $newAuthorUsername) {
		try {
			$this->setAuthorId($newAuthorId);
			$this->setAuthorActivationToken($newAuthorActivationToken);
			$this->setAuthorAvatarUrl($newAuthorAvatarUrl);
			$this->setAuthorEmail($newAuthorEmail);
			$this->setAuthorHash($newAuthorHash);
			$this->setAuthorUsername($newAuthorUsername);

		}
			//determine what exception type was thrown
		catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}
	}

	/**
	 * mutator method for tweet id
	 *
	 * @param Uuid|string $newAuthorId new value of Auhor id
	 * @throws \RangeException if $newAuthord is not positive
	 * @throws \TypeError if $newAuthorId is not a uuid or string
	 **/

	public function setAuthorId($newAuthorId) : void {
		try {
			$uuid = self::validateUuid($newAuthorId);
		} catch(\InvalidArgumentException | \RangeException | \Exception | \TypeError $exception) {
			$exceptionType = get_class($exception);
			throw(new $exceptionType($exception->getMessage(), 0, $exception));
		}

		// convert and store the tweet id
		$this->authorId = $uuid;


		//$this -> authorId = $newAuthorId; Orignial Code
	}

	/**
	 * mutator method for account activation token
	 *
	 * @param string $newAuthorActivationToken
	 * @throws \InvalidArgumentException  if the token is not a string or insecure
	 * @throws \RangeException if the token is not exactly 32 characters
	 * @throws \TypeError if the activation token is not a string
	 */
	public function setAuthorActivationToken(?string $newAuthorActivationToken): void{
		if($newAuthorActivationToken === null){
			$this->authorActivationToken = null;
			return;
		}
		$newAuthorActivationToken = strtolower(trim($newAuthorActivationToken));
		if(ctype_xdigit($newAuthorActivationToken) === false){
				throw(new\RangeException("user activation is not valid"));
		}
		//make sure user activation token is only 32 characters
		if(strlen($newAuthorActivationToken) !== 32){
				throw(new\RangeException("user activation token has to be 32"));
		}
		$this -> authorActivationToken = $newAuthorActivationToken;
	}
	public function setAuthorAvatarUrl($newAuthorAvatarUrl){
		$this -> authorActivationToken = $newAuthorAvatarUrl;
	}
	/**
	 * mutator method for email
	 *
	 * @param string $newAuthorEmail new value of email
	 * @throws \InvalidArgumentException if $newEmail is not a valid email or insecure
	 * @throws \RangeException if $newEmail is > 128 characters
	 * @throws \TypeError if $newEmail is not a string
	 **/

	public function setAuthorEmail(string $newAuthorEmail) : void {
		// verify the email is secure
		$newAuthorEmail = trim($newAuthorEmail);
		$newAuthorEmail = filter_var($newAuthorEmail, FILTER_VALIDATE_EMAIL);
		if(empty($newAuthorEmail) === true) {
			throw(new \InvalidArgumentException("profile email is empty or insecure"));
		}
		// verify the email will fit in the database
		if(strlen($newAuthorEmail) > 97) {
			throw(new \RangeException("profile email is too large"));
		}
		// store the email
		$this -> authorEmail = $newAuthorEmail;
	}
	/**
	 * mutator method for profile hash password
	 *
	 * @param string $newAuthorHash
	 * @throws \InvalidArgumentException if the hash is not secure
	 * @throws \RangeException if the hash is not 128 characters
	 * @throws \TypeError if profile hash is not a string
	 */
	public function setAuthorHash(string $newAuthorHash): void{
		//enforce that hash is properly formatted
		$newAuthorHash = trim($newAuthorHash);
		if(empty($newAuthorHash) === true) {
			throw(new \InvalidArgumentException("profile password hash empty or insecure"));
		}
		//enforce the hash is an Argon hash
		$authorHashInfo = password_get_info($newAuthorHash);
		if($authorHashInfo["algoName"] !== "argon2i") {
				throw(new \InvalidArgumentException("profile hash is not a valid hash"));
		}
		//enforce that the hash is exactly 96 characters.
		if(strlen($newAuthorHash)!==96) {
				throw(new\RangeException("profile hash must be 96 characters"));
		}
		//store the hash
		$this -> authorHash = $newAuthorHash;
	}
	public function setAuthorUsername($newAuthorUsername){
		$this -> authorUsername = $newAuthorUsername;
	}

	/**
	 * accessor method for author id
	 *
	 * @return Uuid value of author id
	 **/

	public function getAuthorId(): Uuid {
		return $this->authorId;
	}
	public function getAuthorActivationToken(): ?string {
		return $this-> authorActivationToken;
	}
	public function getAuthorAvatarUrl(){
		return $this-> authorAvatarUrl;
	}
/**
 * accessor method for email
 *
 * @return string value of email
 **/
	public function getAuthorEmail(): string {
		return $this-> authorEmail;
	}

	/**
	 * accessor method for author Hash
	 *
	 * @return string value of hash
	 */
	public function getAuthorHash() : string {
		return $this-> authorHash;
	}
	public function getAuthorUsername(){
		return $this-> authorUsername;
	}

	public function jsonSerialize() : array{
				$fields = get_object_vars($this);
				$fields["authorId"] = $this->authorId->toString();
				return($fields);
	}
		// TODO: Implement jsonSerialize() method.}


}
