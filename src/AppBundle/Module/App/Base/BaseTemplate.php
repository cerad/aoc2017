<?php
namespace AppBundle\Module\App\Base;

use AppBundle\Common\RenderEscapeTrait;

class BaseTemplate
{
    use RenderEscapeTrait;

    private $content = null;

    private $project = [
        'abbv'    => 'AOC2017',
        'title'   => 'AYSO Open Cup',
        'support' => [
            'name'    => 'support_name',
            'email'   => 'support_email',
            'phone'   => 'support_phone',
            'subject' => 'support_subject'
        ],
    ];
    private $showHeaderImage = true;

    public function setContent($content)
    {
        $this->content = $content;
    }
    public function render()
    {
        return <<<EOT
<!DOCTYPE html>
<html lang="en">
{$this->renderHead()}
<body>
  {$this->renderBanner()}
  <div class="container">
    {$this->content}
  </div>
  {$this->renderFooter()}
</body>
</html>
EOT;
    }
    /*  DOC & Header  */
    protected function renderHead()
    {
        return <<<EOT

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{$this->escape($this->project['abbv'])}</title>
    <link rel="shortcut icon" type="image/vnd.microsoft.icon" href="/images/favicon.ico">
    <link rel="apple-touch-icon" type="image/png" href="/images/apple-touch-icon-72x72.png"><!-- iPad -->
    <link rel="apple-touch-icon" type="image/png" sizes="114x114" href="/images/apple-touch-icon-114x114.png"><!-- iPhone4 -->
    <link rel="icon" type="image/png" href="/images/apple-touch-icon-114x114.png"><!-- Opera Speed Dial, at least 144?114 px -->
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/normalize/4.1.1/normalize.min.css" media="all" />
    <link rel="stylesheet" type="text/css" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
    <link rel="stylesheet" type="text/css" href="/css/bootstrap.vertical-tabs.min.css">
    <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-fileinput/4.3.2/css/fileinput.min.css" media="all" rel="stylesheet" />
    <link rel="stylesheet" type="text/css" href="/css/zayso.css" media="all" />
</head>
EOT;
    }
    protected function renderBanner()
    {
        if (!$this->showHeaderImage) {
            $html = <<<EOT
<div id="banner">
  <h1>
    <a href="http://www.aysonationalgames.org/" target="_blank"><img src="/images/National_Games.png" height="30" alt="National Games"></a>
      {$this->escape($this->project['title'])}
  </h1>
</div>
EOT;
        }
        else {
            $html = <<<EOT
<div class="skBanners">
  <a href="http://www.aysonationalgames.org/" target="_blank"><img class="width-90" src="/images/header-ipad_01.png"></a>
        <center class="skFont  width-90">AYSO WELCOMES YOU TO PALM BEACH COUNTY, FLORIDA, JULY 5-10, 2016</center>
</div>
EOT;
        }

        return $html;

    }

    /* Footer item go here */
    protected function renderFooter()
    {
        return
            <<<EOT
<div class="cerad-footer">
  <br />
  <hr>
  <p> zAYSO - For assistance contact {$this->project['support']['name']} at
      <a href="mailto:{$this->project['support']['email']}?subject={$this->project['support']['subject']}">{$this->project['support']['email']}</a>
      or {$this->project['support']['phone']} 
  </p>
</div>    
<div class="clear-both"></div>
</div>
EOT;
    }

}