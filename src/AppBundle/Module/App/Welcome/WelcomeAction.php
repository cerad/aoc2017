<?php

namespace AppBundle\Module\App\Welcome;

use AppBundle\Common\ActionTrait;
use Symfony\Component\HttpFoundation\Request;

class WelcomeAction
{
    use ActionTrait;

    public function __invoke(Request $request)
    {
        // Verify not signed in
        if ($this->isGranted('ROLE_USER')) {
            return $this->redirectToRoute('app_home');
        }
        return null;
    }
}
