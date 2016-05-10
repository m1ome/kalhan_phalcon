<?php

use Spec\Helper\Call;
use Kahlan\Plugin\Stub;

describe("PUT - /api/users/{id}", function() {

	before(function() {
		$this->controller = new Call('User');
		$this->user = Api\Models\Users::findFirst();
	});

	it("should update user", function() {
		$this->controller->setRequest([
			'name' => $this->user->name + rand(),
			'age'  => $this->user->age,
			'type' => $this->user->type
		]);
		$user = $this->controller->run('update', array($this->user->id));

		expect($user)->toBeA('array');
		expect($user)->toContainKey(array('error', 'result'));
		expect($user['result'])->toBe($this->user->id);

	});

	describe("Errors", function() {

		it("shouldn't update a user with a same name", function() {
			$user = Api\Models\Users::findFirst();

			$this->controller->setRequest([
				'name' => $user->name,
				'age'  => 20,
				'type' => 'admin'
			]);
			$user = $this->controller->run('update', array($user->id));
			// var_dump($user);

			expect($user)->toBeA('array');
			expect($user)->toContainKey(array('error', 'result'));
			expect($user['error'])->toBe('VALIDATION_ERROR');
			expect($user['result'])->toBeA('array');

		});


		it("shouldn't update a user with a bad type", function() {

			$this->controller->setRequest([
				'name' => $this->user->name + rand(),
				'age'  => 20,
				'type' => 'adminko'
			]);
			$user = $this->controller->run('update', array($this->user->id));

			expect($user)->toBeA('array');
			expect($user)->toContainKey(array('error', 'result'));
			expect($user['error'])->toBe('VALIDATION_ERROR');
			expect($user['result'])->toBeA('array');

		});

		it("shouldn't update a user with a bad age", function() {

			$this->controller->setRequest([
				'name' => $this->user->name + rand(),
				'age'  => -100,
				'type' => 'admin'
			]);
			$user = $this->controller->run('update', array($this->user->id));

			expect($user)->toBeA('array');
			expect($user)->toContainKey(array('error', 'result'));
			expect($user['error'])->toBe('VALIDATION_ERROR');
			expect($user['result'])->toBeA('array');

		});

		it("should fall off database errors", function() {
			$user = Api\Models\Users::findFirst();

			Stub::on('Api\Models\Users')->method('update', function($data=null, $whitelist=null) {
			  return false;
			});

			$this->controller->setRequest([
				'name' => $user->name + rand(), 
				'age'  => 20,
				'type' => 'admin'
			]);
			$user = $this->controller->run('update', array($user->id));

			expect($user)->toBeA('array');
			expect($user)->toContainKey(array('error', 'result'));
			expect($user['error'])->toBe('UPDATE_ERROR');
			expect($user['result'])->toBeA('string');

		});

		it("should fall off with a unknown user", function() {

			$this->controller->setRequest([
				'name' => 'Some rand name' + rand(), 
				'age'  => 20,
				'type' => 'admin'
			]);
			$user = $this->controller->run('update', array(0));

			expect($user)->toBeA('array');
			expect($user)->toContainKey(array('error', 'result'));
			expect($user['error'])->toBe('UNKNOWN_USER');
			expect($user['result'])->toBeA('array');

		});

	});

});