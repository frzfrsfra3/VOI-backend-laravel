<?php

namespace App\Actions;

use App\Repository\ContactRepository;
use Illuminate\Http\Request;

class getContactMessagesAction
{
    public function __construct(
        protected ContactRepository $contact
    ) {}

    public function __invoke()
    {
  
        return $this->contact->getcontactsbyadmin();
    }
}
