<x-mail::message>
  <div class="text-base">
    Anmeldung Gönner
  </div>
  <br>
  @if ($data['salutation'])
    <div class="text-base">
      <strong>Anrede</strong><br>
      {{ $data['salutation'] }}
    </div>
    <br>
  @endif
  @if ($data['firstname'])
    <div class="text-base">
      <strong>Vorname</strong><br>
      {{ $data['firstname'] }}
    </div>
    <br>
  @endif
  @if ($data['name'])
    <div class="text-base">
      <strong>Name</strong><br>
      {{ $data['name'] }}
    </div>
    <br>
  @endif
  @if ($data['street'])
    <div class="text-base">
      <strong>Strasse/Nr.</strong><br>
      {{ $data['street'] }}
    </div>
    <br>
  @endif
  @if ($data['zip'])
    <div class="text-base">
      <strong>PLZ</strong><br>
      {{ $data['zip'] }}
    </div>
    <br>
  @endif
  @if ($data['location'])
    <div class="text-base">
      <strong>Ort</strong><br>
      {{ $data['location'] }}
    </div>
    <br>
  @endif
  @if ($data['email'])
    <div class="text-base">
      <strong>E-Mail</strong><br>
      {{ $data['email'] }}
    </div>
    <br>
  @endif
  @if ($data['phone'])
    <div class="text-base">
      <strong>Telefon</strong><br>
      {{ $data['phone'] }}
    </div>
    <br>
  @endif
  @if ($data['message'])
    <div class="text-base">
      <strong>Bemerkungen</strong><br>
      {!! nl2br($data['message']) !!}
    </div>
    <br>
  @endif
  <footer>
    <br>Museum Neuthal<br>Textil- & Industriekultur<br>Im Neuthal 6<br>8344 Bäretswil
  </footer>
</x-mail::message>