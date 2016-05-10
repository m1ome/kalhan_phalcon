<?php

use Spec\Helper\Call;

describe("GET - /api/users", function() {

	before(function() {
		$this->controller = new Call('User');
	});

	it("should show user list", function() {
		$list = $this->controller->run('index');

		expect($list)->toBeA('array');
		expect($list)->toContainKey('error');
		expect($list['error'])->toBe(0);
		expect($list)->toContainKey('result');
		expect($list['result'])->toBeA('array');

		foreach($list['result'] as $user) {
			expect($user)->toBeA('array');
			expect($user)->toContainKey(['id', 'name', 'age', 'type']);
		}
	});

});