<?php
namespace App\Actions\Newsletter;
use \Brevo\Client\Configuration;
use \Brevo\Client\Api\ContactsApi;
use \Brevo\Client\Model\CreateContact;
use \GuzzleHttp\Client;
use Exception;

class CreateSubscriber
{
  public function execute(array $data): bool
  {
    if (!env('BREVO_API_KEY') || !env('BREVO_LIST_ID')) {
      throw new Exception('Brevo API key or List ID not configured in environment');
    }

    $config = \Brevo\Client\Configuration::getDefaultConfiguration()->setApiKey('api-key', env('BREVO_API_KEY'));
    
    $apiInstance = new \Brevo\Client\Api\ContactsApi(
      new \GuzzleHttp\Client(),
      $config
    );

    $createContact = new \Brevo\Client\Model\CreateContact([
      'email' => $data['email'],
      'updateEnabled' => true,
      'attributes' => [
        'VORNAME' => $data['firstname'] ?? null,
        'NACHNAME' => $data['name'] ?? null,
      ],
      'listIds' => [(int)env('BREVO_LIST_ID')]
    ]);
    
    try {
      $apiInstance->createContact($createContact);
      return true;
    } catch (Exception $e) {
      \Log::error('Brevo API error: ' . $e->getMessage());
      return false;
    }
  }
}