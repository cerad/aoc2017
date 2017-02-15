<?php
namespace AppBundle\Module\Project;

class Project
{
    public $key;
    public $status;

    public $abbv;
    public $title;
    public $desc;
    public $prefix;

    /** @var  ProjectContact */
    public $support;

}