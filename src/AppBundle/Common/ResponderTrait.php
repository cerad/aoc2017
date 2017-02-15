<?php
namespace AppBundle\Common;

use AppBundle\Module\App\Base\BaseTemplate; // Need a BaseTemplateInterface ???
use Symfony\Component\HttpFoundation\Response;

trait ResponderTrait
{
    use RouteTrait;
    use RenderEscapeTrait;

    /** @var  BaseTemplate */
    protected $baseTemplate;

    public function setBaseTemplate(BaseTemplate $baseTemplate)
    {
        $this->baseTemplate = $baseTemplate;
    }
    public function renderBaseTemplate($content)
    {
        $this->baseTemplate->setContent($content);
        return $this->baseTemplate->render();
    }

    /**
     * @param $content string
     * @param $status  integer
     * @return Response
     */
    protected function newResponse($content = null, $status = 200)
    {
        return new Response($content,$status);
    }

}