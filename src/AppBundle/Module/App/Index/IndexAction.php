<?php

namespace AppBundle\Module\App\Index;

use AppBundle\Common\ActionTrait;
use Symfony\Component\HttpFoundation\Request;

class IndexAction
{
    use ActionTrait;

    public function __invoke(Request $request)
    {
        // Just a simple redirect based on login
        return $this->getUser() ? $this->redirectToRoute('app_home') : $this->redirectToRoute('app_welcome');
    }
}
