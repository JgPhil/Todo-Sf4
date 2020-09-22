<?php

namespace App\Tests;

use App\Entity\User;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\BrowserKit\Cookie;

class LogUtils
{
	const FIREWALL =  'main';
	private $client;
	private $entityManager;
	public $session;

	public function __construct($client)
	{
		$this->client = $client;
		$this->entityManager = $this->client->getContainer()->get('doctrine')->getManager();
		$this->session = $this->client->getContainer()->get('session');
		
	}

	public function login($type)
	{

		if (empty($type)) { // anonymous user
			return;
		}

		[$credentials, $user] = $this->getUser($type);

		$token = new UsernamePasswordToken($user, $credentials['password'], self::FIREWALL, $user->getRoles());

		$this->session->set('_security_' . self::FIREWALL, serialize($token));
		$this->session->save();
		$cookie = new Cookie($this->session->getName(), $this->session->getId());
		$this->client->getCookieJar()->set($cookie);
	}
	

	public function getUser($type)
	{
		$credentials = ['username' => $type, 'password' => $type];
		$user = $this->entityManager
			->getRepository(User::class)
			->findOneBy([
				'username' => $credentials['username']
			]);
		return [$credentials, $user];
	}
}
