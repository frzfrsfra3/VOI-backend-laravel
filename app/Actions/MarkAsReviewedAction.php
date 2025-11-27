<?php

namespace App\Actions;

use App\Repository\ContactRepository;
use Illuminate\Http\Request;

class MarkAsReviewedAction
{
    public function __construct(
        protected ContactRepository $contact
    ) {}

    public function __invoke($id,Request $request)
    {
       

        return $this->contact->MarkAsReviewed($id,$request);
    }
}
