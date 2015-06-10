<?php namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Contracts\Bus\SelfHandling;
use App\APIDTO\APIResponse as APIResponse;
use Hash, Auth;

class Checking extends Command implements SelfHandling {

	/**
	 * Create a new command instance.
	 *
	 * @return void
	 */
	public function __construct($model, $credentials)
	{
		$this->credentials 	= $credentials;
		$this->model 		= $model;
	}

	/**
	 * Execute the command.
	 *
	 * @return void
	 */
	public function handle()
	{
		if (!is_array($this->credentials))
		{
			throw new InvalidArgumentException("Credential must be type of Array");
		}

		// find user
		$user = $this->model;
		foreach ($this->credentials as $k => $v)
		{
			if (!str_is('password', strtolower($k)))
			{
				if ($k=='email')
				{
					$user = $user->Email($v);
				}
				elseif (is_string($v))
				{
					$user = $user->where($k, $v);
				}
			}
		}

		$user = $user->first();

		// if user not found return false
		if (!$user)
		{
			$response = new APIResponse($this->credentials, ['User Not Registered'], 1);
			return $response->toJson();
		}
		
		// check for password
		if (Hash::check($this->credentials['password'], $user->password))
		{
			$response = new APIResponse($user->toArray(), null, 1);
			return $response->toJson();
		}
		else
		{
			$response = new APIResponse($this->credentials, ['Mismatch Password'], 1);
			return $response->toJson();
		}
	}

}
