<?php

namespace App\Actions;

use App\Repository\ContactRepository;
use Illuminate\Http\Request;

class SendContactMessageAction
{
    public function __construct(
        protected ContactRepository $contact
    ) {}

    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'name'    => 'required|max:255',
            'email'   => 'required|email',
            'message' => 'required|min:10',
            'subject'=>'required'
        ]);

        return $this->contact->saveMessage($validated);
    }
}
