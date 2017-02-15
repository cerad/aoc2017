<?php
namespace AppBundle\Module\Project;

class ProjectContact
{
    public $name;
    public $email;

    public $phone;
    public $subject;

    static public function create($name,$email,$phone = null,$subject = null)
    {
        $contact = new self();
        $contact->name    = $name;
        $contact->email   = $email;
        $contact->phone   = $phone;
        $contact->subject = $subject;
        return $contact;
    }
    static public function createFromArray(array $data)
    {
        $contact = new self();
        $contact->name    = isset($data['name'])    ? $data['name']    : null;
        $contact->email   = isset($data['email'])   ? $data['email']   : null;
        $contact->phone   = isset($data['phone'])   ? $data['phone']   : null;
        $contact->subject = isset($data['subject']) ? $data['subject'] : null;
        return $contact;
    }
}