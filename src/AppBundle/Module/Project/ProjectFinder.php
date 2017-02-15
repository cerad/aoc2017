<?php
namespace AppBundle\Module\Project;

use Symfony\Component\Yaml\Yaml;

class ProjectFinder
{
    public function find($projectKey)
    {
        // Fake database
        $data = Yaml::parse(file_get_contents(__DIR__ . '/projects/' . $projectKey . '.yml'));
        $info = $data['project']['info'];

        $project = new Project();

        $project->key = $info['key'];

        $project->abbv   = $info['abbv'];
        $project->title  = $info['title'];
        $project->desc   = $info['desc'];
        $project->prefix = $info['prefix'];

        $project->support = ProjectContact::createFromArray($info['support']);

        return $project;
    }
}