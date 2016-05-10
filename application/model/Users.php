<?php
namespace Api\Models;

use Phalcon\Mvc\Model\Message;
use Phalcon\Mvc\Model\Validator\Uniqueness;
use Phalcon\Mvc\Model\Validator\InclusionIn;

class Users extends \Phalcon\Mvc\Model
{
    public function validation()
    {
        $this->validate(
            new InclusionIn(
                array(
                    "field"  => "type",
                    "domain" => array(
                        "admin",
                        "user",
                        "moderator"
                    )
                )
            )
        );

        $this->validate(
            new Uniqueness(
                array(
                    "field"   => "name",
                    "message" => "User name should be unique"
                )
            )
        );

        if ($this->age < 0) {
            $this->appendMessage(new Message("Age cannot be less that zero"));
        }

        if ($this->validationHasFailed() == true) {
            return false;
        }
    }

    public static function findByName($name)
    {
        return Users::find(array(
            'conditions' => "name LIKE '%" . $name . "%'"
        ));
    }
}