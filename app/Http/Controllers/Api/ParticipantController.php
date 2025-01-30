<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Statamic\Facades\Entry;
use Illuminate\Support\Facades\Notification;
use App\Notifications\Participant\OwnerInformation;
use App\Notifications\Participant\UserConfirmation;
use Illuminate\Support\Facades\Validator;

class ParticipantController extends Controller
{
  public function submission(Request $request)
  {
    $validationResult = $this->validateRequest($request);

    if ($validationResult !== TRUE)
    {
      return $validationResult;
    }

    $slug = $request->input('firstname') . '-' . $request->input('name') . '-' . $request->input('email');

    // build data
    $data = [
      'date_submission' => date('d.m.Y', time()),
      'title' => $request->input('firstname') . ' ' . $request->input('name') . ', ' . $request->input('email'),
      'salutation' => $request->input('salutation') ?? null,
      'name' => $request->input('name'),
      'firstname' => $request->input('firstname'),
      'street' => $request->input('street'),
      'zip' => $request->input('zip'),
      'location' => $request->input('location'),
      'email' => $request->input('email'),
      'phone' => $request->input('phone') ?? null,
      'message' => $request->input('message'),
    ];

    $entry = Entry::make()
      ->collection('patron_submissions')
      ->slug($slug)
      ->data($data)
      ->save();

    Notification::route('mail', env('MAIL_TO'))
      ->notify(new OwnerInformation($data)
    );

    Notification::route('mail', $data['email'])
      ->notify(new UserConfirmation($data)
    );

    return response()->json(['message' => 'Store successful']);
  }

  protected function validateRequest(Request $request)
  {
    $validationRules = $this->getValidationRules();

    $validator = Validator::make(
      $request->all(),
      $validationRules['rules'],
      $validationRules['messages']
    );

    if ($validator->fails())
    {
      $errors = $validator->errors();
      $formattedErrors = [];

      foreach ($errors->messages() as $field => $messages)
      {
        $formattedErrors[$field] = $messages[0];
      }

      return response()->json(['errors' => $formattedErrors], 422);
    }

    return TRUE;
  }

  protected function getValidationRules()
  {
    $validationRules = [
      'name' => 'required',
      'firstname' => 'required',
      'street' => 'required',
      'zip' => 'required',
      'location' => 'required',
      'email' => 'required|email|regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
    ];

    // Set validation messages
    $validationMessages = [
      'name.required' => 'Name ist erforderlich',
      'firstname.required' => 'Vorname ist erforderlich',
      'street.required' => 'Strasse ist erforderlich',
      'zip.required' => 'PLZ ist erforderlich',
      'location.required' => 'Ort ist erforderlich',
      'email.required' => 'E-Mail-Adresse ist erforderlich',
      'email.email' => 'E-Mail-Adresse muss gültig sein',
      'email.regex' => 'E-Mail-Adresse muss gültig sein',
    ];
    
    return [
      'rules' => $validationRules,
      'messages' => $validationMessages,
    ];
  }
}