<?php

namespace AppBundle\Module\App\Welcome;

use AppBundle\Common\ActionTrait;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class WelcomeAction
{
    use ActionTrait;

    public function __invoke(Request $request)
    {
        return new Response('Welcome ' . $this->getCurrentRouteName());
    }
}
