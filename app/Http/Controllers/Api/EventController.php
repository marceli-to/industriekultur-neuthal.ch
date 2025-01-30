<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Statamic\Facades\Entry;
use Illuminate\Support\Facades\Notification;
use App\Notifications\UserConfirmation;
use App\Notifications\OwnerInformation;
use Illuminate\Support\Facades\Validator;

class EventController extends Controller
{
  public function get($eventId)
  {
    $event = Entry::find($eventId, 'de');
    return response()->json([
      'title' => $event->title,
      'has_salutation' => $event->has_salutation,
      'requires_salutation' => $event->requires_salutation,
      'has_name' => $event->has_name,
      'requires_name' => $event->requires_name,
      'has_firstname' => $event->has_firstname,
      'requires_firstname' => $event->requires_firstname,
      'has_email' => $event->has_email,
      'requires_email' => $event->requires_email,
      'has_phone' => $event->has_phone,
      'requires_phone' => $event->requires_phone,
      'has_street' => $event->has_street,
      'requires_street' => $event->requires_street,
      'has_zip' => $event->has_zip,
      'requires_zip' => $event->requires_zip,
      'has_location' => $event->has_location,
      'requires_location' => $event->requires_location,
      'has_remarks' => $event->has_remarks,
      'requires_remarks' => $event->requires_remarks,
      'has_number_people' => $event->has_number_people,
      'requires_number_people' => $event->requires_number_people,
      'has_number_adults' => $event->has_number_adults,
      'requires_number_adults' => $event->requires_number_adults,
      'has_number_teenagers' => $event->has_number_teenagers,
      'requires_number_teenagers' => $event->requires_number_teenagers,
      'has_number_kids' => $event->has_number_kids,
      'requires_number_kids' => $event->requires_number_kids,
    ]);
  }

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
      'date' => $event->event_date->format('d.m.Y'),
      'name' => $request->input('name'),
      'firstname' => $request->input('firstname'),
      'email' => $request->input('email'),
      'number_of_people' => $request->input('number_of_people'),
    ];

    $entry = Entry::make()
      ->collection('event_registrations')
      ->slug($slug)
      ->data($data)
      ->save();
    
    
    Notification::route('mail', $request->input('email'))
      ->notify(new UserConfirmation($data)
    );

    Notification::route('mail', env('MAIL_TO'))
      ->notify(new OwnerInformation($data)
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
        $formattedErrors[$field] = $messages[0];
      }

      return response()->json(['errors' => $formattedErrors], 422);
    }

    return TRUE;
  }

  protected function getValidationRules($event)
  {
    if ($event->has_salutation && $event->requires_salutation) {
      $validationRules['salutation'] = 'required';
    }

    if ($event->has_name && $event->requires_name) {
      $validationRules['name'] = 'required';
    }

    if ($event->has_firstname && $event->requires_firstname) {
      $validationRules['firstname'] = 'required';
    }

    if ($event->has_email && $event->requires_email) {
      $validationRules['email'] = 'required|email|regex:/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
    } 

    if ($event->has_phone && $event->requires_phone) {
      $validationRules['phone'] = 'required';
    }

    if ($event->has_street && $event->requires_street) {
      $validationRules['street'] = 'required';
    }

    if ($event->has_zip && $event->requires_zip) {
      $validationRules['zip'] = 'required';
    }

    if ($event->has_location && $event->requires_location) {
      $validationRules['location'] = 'required';
    }

    if ($event->has_number_people && $event->requires_number_people) {
      $validationRules['number_people'] = 'required|integer|min:1';
    }

    if ($event->has_number_adults && $event->requires_number_adults) {
      $validationRules['number_adults'] = 'required|integer|min:1';
    }

    if ($event->has_number_teenagers && $event->requires_number_teenagers) {
      $validationRules['number_teenagers'] = 'required|integer|min:1';
    }

    if ($event->has_number_kids && $event->requires_number_kids) {
      $validationRules['number_kids'] = 'required|integer|min:1';
    }

    if ($event->has_number_adults) {
      // only if is submitted
      $validationRules['number_adults'] = 'nullable|integer|min:1';
    }

    if ($event->has_number_teenagers) {
      $validationRules['number_teenagers'] = 'nullable|integer|min:1';
    }

    if ($event->has_number_kids) {
      $validationRules['number_kids'] = 'nullable|integer|min:1';
    }

    if ($event->has_remarks && $event->requires_remarks) {
      $validationRules['remarks'] = 'required';
    }

    // Set validation messages
    $validationMessages = [
      'salutation.required' => 'Anrede ist erforderlich',
      'name.required' => 'Name ist erforderlich',
      'firstname.required' => 'Vorname ist erforderlich',
      'email.required' => 'E-Mail-Adresse ist erforderlich',
      'email.email' => 'E-Mail-Adresse muss gültig sein',
      'email.regex' => 'E-Mail-Adresse muss gültig sein',
      'phone.required' => 'Telefon ist erforderlich',
      'street.required' => 'Straße ist erforderlich',
      'zip.required' => 'PLZ ist erforderlich',
      'location.required' => 'Ort ist erforderlich',
      'number_people.required' => 'Anzahl Personen ist erforderlich',
      'number_people.integer' => 'Anzahl Personen muss eine Zahl sein',
      'number_people.min' => 'Anzahl Personen muss mindestens 1 sein',
      'number_adults.required' => 'Anzahl Erwachsene ist erforderlich',
      'number_adults.integer' => 'Anzahl Erwachsene muss eine Zahl sein',
      'number_adults.min' => 'Anzahl Erwachsene muss mindestens 1 sein',
      'number_teenagers.required' => 'Anzahl Jugendliche ist erforderlich',
      'number_teenagers.integer' => 'Anzahl Jugendliche muss eine Zahl sein',
      'number_teenagers.min' => 'Anzahl Jugendliche muss mindestens 1 sein',
      'number_kids.required' => 'Anzahl Kinder ist erforderlich',
      'number_kids.integer' => 'Anzahl Kinder muss eine Zahl sein',
      'number_kids.min' => 'Anzahl Kinder muss mindestens 1 sein',
      'remarks.required' => 'Bemerkungen sind erforderlich',
    ];
    
    return [
      'rules' => $validationRules,
      'messages' => $validationMessages,
    ];
  }
}