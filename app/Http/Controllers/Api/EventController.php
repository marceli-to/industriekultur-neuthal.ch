<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Statamic\Facades\Entry;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserEventRegistration;
use App\Notifications\OwnerEventRegistration;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
  public function register(Request $request)
  {
    $event = Entry::find($request->input('event_id'));

    $validationResult = $this->validateRequest($request, $event);

    if ($validationResult !== TRUE)
    {
      return $validationResult;
    }

    $slug = $event->title . ' ' . $request->input('firstname') . ' ' . $request->input('name');

    // build data
    $data = [
      'title' => $event->title,
      'event_id' => $event->id,
      'name' => $request->input('name'),
      'firstname' => $request->input('firstname'),
      'email' => $request->input('email'),
    ];

    $entry = Entry::make()
      ->collection('event_registrations')
      ->slug($slug)
      ->data($data)
      ->save();
    
    Notification::route('mail', $request->input('email'))
      ->notify(new UserEventRegistration($data)
    );

    Notification::route('mail', env('MAIL_TO'))
      ->notify(new OwnerEventRegistration($data)
    );

    return response()->json(['message' => 'Store successful']);
  }

  protected function validateRequest(Request $request, $event)
  {
    $validationRules = $this->getValidationRules($event);

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
        if (strpos($field, 'additional_individuals.') === 0) {
          $parts = explode('.', $field);
          $index = $parts[1];
          $subfield = $parts[2];
          $formattedErrors['additional_individuals'][$index][$subfield] = $messages[0];
        } 
        else {
          $formattedErrors[$field] = $messages[0];
        }
      }

      return response()->json(['errors' => $formattedErrors], 422);
    }

    return TRUE;
  }

  protected function getValidationRules($event)
  {
    $validationRules = [
      'name' => 'required',
      'firstname' => 'required',
      'email' => 'required|email|regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/',
    ];

    // Set validation messages
    $validationMessages = [
      'name.required' => 'Name ist erforderlich',
      'firstname.required' => 'Vorname ist erforderlich',
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