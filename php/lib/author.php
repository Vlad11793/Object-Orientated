<?php
namespace Vlad11793\ObjectOrientated;

require_once (dirname(__DIR__) . "/Classes/autoload.php");


use Vlad11793\ObjectOrientated\Author;
//use Ramsey\Uuid\Uuid;

$hash = password_hash("password", PASSWORD_ARGON2I, ["time_cost" => 7]);
var_dump($hash);
$newAuthor = new Author("ff81b6a8-7186-454a-9c75-0c5b95039b1d", "24234234324234234234234234234343", "ProfilePic.com", "Vlad93@gmail.com", $hash, "Vlad117");

echo ($newAuthor-> getAuthorId()),($newAuthor-> getAuthorActivationToken()),($newAuthor-> getAuthorAvatarUrl()),($newAuthor-> getAuthorEmail()),($newAuthor-> getAuthorHash()),($newAuthor-> getAuthorUsername());
var_dump($newAuthor);

//No idea why cant call class Author.php

$returnObject = new Author();
$returnObject->getAuthorbyAuthorId();
var_dump($returnObject);

$returnArray = new Author();
$returnArray->getAuthorsByAuthorId();
var_dump($returnArray);