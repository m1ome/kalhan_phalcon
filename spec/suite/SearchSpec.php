<?php

use Spec\Helper\Call;

describe("GET - /api/users/search/{name}", function() {

	before(function() {
		$this->controller = new Call('User');
	});

	it("should find user by name", function() {

		$user = $this->controller->run('search', array('Pavel'));
		expect($user)->toBeA('array');
		expect($user)->toContainKey(array('error', 'result'));
		expect($user['error'])->toBe(0);
		expect($user['result'])->toBeA('array');
		expect($user['result'][0])->toContainKey(['id', 'name', 'age', 'type']);

	});

	it("should not find user if it can't", function() {

		$user = $this->controller->run('search', array('Pvavel'));
		expect($user)->toBeA('array');
		expect($user)->toContainKey(array('error', 'result'));
		expect($user['error'])->toBe(0);
		expect($user['result'])->toBe(array());
		expect(count($user['result']))->toBe(0);

	});

});