<?php
namespace AppBundle\Common;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

trait SecurityTrait
{
    /** @var  TokenStorageInterface */
    protected $tokenStorage;

    /** @var  AuthorizationCheckerInterface */
    protected $authorizationChecker;

    /** @var  AuthenticationUtils */
    protected $authenticationUtils;

    public function setSecurityServices(
        TokenStorageInterface         $tokenStorage,
        AuthorizationCheckerInterface $authorizationChecker,
        AuthenticationUtils           $authenticationUtils)
    {
        $this->tokenStorage         = $tokenStorage;
        $this->authorizationChecker = $authorizationChecker;
        $this->authenticationUtils  = $authenticationUtils;
    }
    /** ================================================================
     * Mostly for authen errors
     * 
     * @return AuthenticationUtils 
     */
    protected function getAuthenticationUtils() 
    {
        return $this->authenticationUtils;
    }
    /** 
     * Pulled from Symfony Framework Base Controller
     * Adjusted style to make PHPStorm happy
     *
     * Get a user from the Security Token Storage.
     *
     * @return mixed
     *
     * @throws \LogicException If SecurityBundle is not available
     *
     * @see TokenInterface::getUser()
     */
    protected function getUser()
    {
        $token = $this->tokenStorage->getToken();
        
        if (!$token) {
            return null;
        }

        if (!is_object($user = $token->getUser())) {
            // e.g. anonymous authentication
            return null;
        }

        return $user;
    }
    /** Copied directly from Symfony Framework Base Controller
     * Checks if the attributes are granted against the current authentication token and optionally supplied object.
     *
     * @param mixed $attributes The attributes
     * @param mixed $object     The object
     *
     * @return bool
     *
     * @throws \LogicException
     */
    protected function isGranted($attributes, $object = null)
    {
        return $this->authorizationChecker->isGranted($attributes, $object);
    }
}