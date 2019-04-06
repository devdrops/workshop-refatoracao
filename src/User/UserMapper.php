<?php

namespace Workshop\User;

class UserMapper
{
    private $persistence;

    public function __construct($persistence)
    {
        $this->persistence = $persistence;
    }

    public function findByCriteria(array $criteria)
    {
        $query = 'SELECT id, username FROM user_logins '
            .'WHERE username = :user '
            .'AND password = :pass;';

        $statement = $this->persistence
            ->prepare($query);
        $result = $statement->execute($criteria);

        return $statement->fetch();
    }
}
