<?php
namespace AppBundle\Common;

use Symfony\Component\DependencyInjection\ContainerAwareTrait;

trait ActionTrait
{
    use ContainerAwareTrait;
    use RouteTrait;
    use SecurityTrait;

    protected function getCurrentProject()
    {
        return $this->container->getParameter('app_project');
    }
    protected function getCurrentProjectInfo()
    {
        return $this->container->getParameter('app_project')['info'];
    }
    protected function getCurrentProjectKey()
    {
        return $this->container->getParameter('app_project_key');
    }
    protected function escape($content)
    {
        return htmlspecialchars($content, ENT_COMPAT);
    }
    /** ================================================================
     * This is good because mailer is a heavy object and only want to create when needed
     * 
     * @return \Swift_Mailer 
     */
    protected function getMailer() 
    {
        return $this->container->get('mailer');
    }
    // Current registered person's id if logged in, null otherwise
    protected function getUserRegPersonId()
    {
        $user = $this->getUser();
        return $user ? $user->getRegPersonId() : null;
    }
}