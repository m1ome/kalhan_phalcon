<?php

use Spec\Helper\Call;
use Kahlan\Plugin\Stub;

describe("POST - /api/users/", function() {

	before(function() {
		$this->controller = new Call('User');
		$this->name = 'Username#110';
		$this->ids = [];
	});

	after(function() {
		foreach($this->ids as $id) {
			$user = \Api\Models\Users::findFirst($id);
			$user->delete();
		}
	});

	it("should add user", function() {

		$this->controller->setRequest([
			'name' => $this->name,
			'age'  => 20,
			'type' => 'admin'
		]);
		$user = $this->controller->run('create');
		expect($user)->toBeA('array');
		expect($user)->toContainKey(array('error', 'result'));
		expect($user['error'])->toBe(0);
		expect($user['result'])->toBeA('string');
		expect($user['result'])->toBeGreaterThan(0);

		$this->ids[] = $user['result'];

	});

	it("should add user with a different name", function() {

		$this->controller->setRequest([
			'name' => $this->name + rand(),
			'age'  => 20,
			'type' => 'admin'
		]);
		$user = $this->controller->run('create');
		expect($user)->toBeA('array');
		expect($user)->toContainKey(array('error', 'result'));
		expect($user['error'])->toBe(0);
		expect($user['result'])->toBeA('string');
		expect($user['result'])->toBeGreaterThan(0);

		$this->ids[] = $user['result'];

	});

	describe("Errors", function() {

		it("shouldn't create a user with a same name", function() {

			$this->controller->setRequest([
				'name' => $this->name,
				'age'  => 20,
				'type' => 'admin'
			]);
			$user = $this->controller->run('create');

			expect($user)->toBeA('array');
			expect($user)->toContainKey(array('error', 'result'));
			expect($user['error'])->toBe('VALIDATION_ERROR');
			expect($user['result'])->toBeA('array');

		});


		it("shouldn't create a user with a bad type", function() {

			$this->controller->setRequest([
				'name' => $this->name + rand(),
				'age'  => 20,
				'type' => 'adminko'
			]);
			$user = $this->controller->run('create');

			expect($user)->toBeA('array');
			expect($user)->toContainKey(array('error', 'result'));
			expect($user['error'])->toBe('VALIDATION_ERROR');
			expect($user['result'])->toBeA('array');

		});

		it("shouldn't create a user with a bad age", function() {

			$this->controller->setRequest([
				'name' => $this->name + rand(),
				'age'  => -100,
				'type' => 'admin'
			]);
			$user = $this->controller->run('create');

			expect($user)->toBeA('array');
			expect($user)->toContainKey(array('error', 'result'));
			expect($user['error'])->toBe('VALIDATION_ERROR');
			expect($user['result'])->toBeA('array');

		});

		it("should handle database errors", function() {

			Stub::on('Api\Models\Users')->method('create', function($data=null, $whitelist=null) {
			  return false;
			});

			$this->controller->setRequest([
				'name' => $this->name + rand(), 
				'age'  => 20,
				'type' => 'admin'
			]);
			$user = $this->controller->run('create');

			expect($user)->toBeA('array');
			expect($user)->toContainKey(array('error', 'result'));
			expect($user['error'])->toBe('CREATE_ERROR');
			expect($user['result'])->toBeA('string');

		});

	});

});