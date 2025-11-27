<?php
namespace App\Repository;

use App\Abstract\BaseRepositoryImplementation;
use App\Models\Contact;
use App\Interfaces\ContactInterface;
use App\ApiHelper\ApiResponseCodes;
use App\ApiHelper\ApiResponseHelper;
use App\ApiHelper\Result;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\ApiHelper\ErrorResult;
class ContactRepository extends BaseRepositoryImplementation implements ContactInterface
{
    public function model()
    {
        return Contact::class;
    }

    public function saveMessage($data)
    {
        return $this->create([
            'name'     => $data['name'],
            'email'    => $data['email'],
            'message'  => $data['message'],
            'subject'  => $data['subject'],
           
        ]);
    }

    public function MarkAsReviewed($id,$data) 
    {
        $validator = Validator::make(
            ['status' => $data['status']],
            ['status' => 'required|string']
        );
        if ($validator->fails()) {
            return ApiResponseHelper::validationError(
                $validator->errors(),
                $validator->errors()->first()
            );
        }
        $contact=$this->model->findOrFail($id);
       $contact['status']='reviewed';
        $contact->update(['status'=>'reviewed']);
        $contact->save();
        // dd($contact);
        return ApiResponseHelper::sendResponse(
            new Result([
                'user' => $contact->only(['id','name','email','status']),
                
            ], 'Contact marked as reviewed!')
        );

    }

    // Implement any additional methods here
  public function createContact(Request $request)
{
    $startTime = microtime(true);
    $rules = [
  
    ];

    $validator = Validator::make($request->all(), $rules);

    if ($validator->fails()) {
        // Extract the first validation message
        $firstErrorMessage = $validator->errors()->first();

        return ApiResponseHelper::sendErrorResponse(
            new ErrorResult(
                $validator->errors(),
                $firstErrorMessage, // Use the first validation message here
                null,
                false,
                400
            ),
            400
        );
    }

    $r = $this->create($validator->validated());

    return ApiResponseHelper::sendResponse(new Result($r, 'Contact created successfully'));
}

public function getcontactsbyadmin() {
    $contacts = $this->model->get();
    return ApiResponseHelper::sendResponse(new Result($contacts, 'All Contact Messages '));

}

}