<?php

use Spec\Helper\Call;
use Kahlan\Plugin\Stub;

describe("DELETE - /api/users/{id}", function() {

	before(function() {
		$this->controller = new Call('User');
		$this->users = array();
	});

	beforeEach(function() {

		$this->user = new \Api\Models\Users();
		$this->user->name = 'Rand name' + rand();
		$this->user->age  = 10;
		$this->user->type = 'admin';
		$this->user->create();

		$this->users[] = $this->user->id;

	});

	afterEach(function() {

		foreach($this->users as $id) {
			$user = \Api\Models\Users::findFirst($id);
			if ($user) {
				$user->delete();
			}
		}

	});

	it("should delete user", function() {

		$user = $this->controller->run('delete', array($this->user->id));

		expect($user)->toBeA('array');
		expect($user)->toContainKey(array('error', 'result'));
		expect($user['error'])->toBe(0);
		expect($user['result'])->toBeA('array');

	});

	it("should throw on unknonw user", function() {

		$user = $this->controller->run('delete', array(0));

		expect($user)->toBeA('array');
		expect($user)->toContainKey(array('error', 'result'));
		expect($user['error'])->toBe('UNKNOWN_USER');
		expect($user['result'])->toBeA('array');

	});

});