<?php
namespace Api\Controllers;

use Phalcon\Mvc\Controller;
use Api\Models\Users;

class UserController extends Controller 
{
	public function index()
	{
		$users = \Api\Models\Users::find();
		echo json_encode([
			'error' => 0,
			'result' => $users->toArray()
		]);
	}

	public function search($name)
	{
		$users = \Api\Models\Users::findByName($name);
		echo json_encode([
			'error' => 0,
			'result' => $users->toArray()
		]);
	}

	public function read($id)
	{
		$user = \Api\Models\Users::findFirst($id);
		echo json_encode([
			'error' => 0,
			'result' => ($user) ? $user->toArray() : []
		]);
	}

	public function create()
	{
		$userData = $this->di->get('request')->getJsonRawBody();

		$user = new \Api\Models\Users();
		$user->name = $userData->name;
		$user->age  = $userData->age;
		$user->type = $userData->type;

		if ($user->validation() === false) {
			echo json_encode([
				'error' => 'VALIDATION_ERROR',
				'result' => array_map(function($item) {
					return $item->getMessage();
				}, $user->getMessages())
			]);
		} else {
			if ($user->create()) {
				echo json_encode([
					'error' => 0,
					'result' => $user->id
				]);
			} else {
				echo json_encode([
					'error' => 'CREATE_ERROR',
					'result' => 'Database create error'
				]);
			}
		}
	}

	public function update($id)
	{
		$userData = $this->di->get('request')->getJsonRawBody();

		$user = \Api\Models\Users::findFirst($id);
		if (!$user) {
			echo json_encode([
				'error' => 'UNKNOWN_USER',
				'result' => []
			]);
		} else {
			$user->name = $userData->name;
			$user->age  = $userData->age;
			$user->type = $userData->type;

			if ($user->validation() === false) {
				echo json_encode([
					'error' => 'VALIDATION_ERROR',
					'result' => array_map(function($item) {
						return $item->getMessage();
					}, $user->getMessages())
				]);
			} else {
				if ($user->update()) {
					echo json_encode([
						'error' => 0,
						'result' => $user->id
					]);
				} else {
					echo json_encode([
						'error' => 'UPDATE_ERROR',
						'result' => 'Database create error'
					]);
				}
			}	
		}
	}

	public function delete($id)
	{
		$user = \Api\Models\Users::findFirst($id);
		if (!$user) {
			echo json_encode([
				'error' => 'UNKNOWN_USER',
				'result' => []
			]);
			return;
		}

		if ($user->delete()) {
			echo json_encode([
				'error' => 0,
				'result' => []
			]);
		} else {
			echo json_encode([
				'error' => 'USER_DELETE_ERROR',
				'result' => []
			]);
		}
	}
}