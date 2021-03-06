<?php 

namespace App\Menu;

use Knp\Menu\FactoryInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorage;

class UserMenuBuilder
{
    private $factory;
    private $tokenStorage;

    public function __construct(FactoryInterface $factory, TokenStorage $tokenStorage)
    {
        $this->factory = $factory;
        $this->tokenStorage = $tokenStorage;
    }

    public function createMenu()
    {
        $user = $this->tokenStorage->getToken()->getUser();

        $menu = $this->factory->createItem('root');
        if(isset($user)){
            $parent = $menu->addChild($user->getUsername(), ['uri' => '#']);            
        }
        $parent->setExtra('translation_domain', false); // Ne pas traduire le pseudo

        $parent->addChild('profile', ['route' => 'fos_user_profile_show']);

        $parent->addChild('logout', ['route' => 'fos_user_security_logout']);

        if ($user->hasRole('ROLE_USER')) {
            $menu->addChild('user', ['url' => '#']);
        }
      
        return $menu;
    }
}