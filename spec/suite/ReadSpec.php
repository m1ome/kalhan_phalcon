<?php

use Spec\Helper\Call;

describe("GET - /api/users/{name}", function() {

	before(function() {
		$this->controller = new Call('User');
	});

	it("should find user by id", function() {

		$user = $this->controller->run('read', array(1));
		expect($user)->toBeA('array');
		expect($user)->toContainKey(array('error', 'result'));
		expect($user['error'])->toBe(0);
		expect($user['result'])->toBeA('array');
		expect($user['result'])->toContainKey(['id', 'name', 'age', 'type']);

	});

	it("should not find user if it can't", function() {

		$user = $this->controller->run('read', array(0));
		expect($user)->toBeA('array');
		expect($user)->toContainKey(array('error', 'result'));
		expect($user['error'])->toBe(0);
		expect($user['result'])->toBe(array());
		expect(count($user['result']))->toBe(0);

	});

});