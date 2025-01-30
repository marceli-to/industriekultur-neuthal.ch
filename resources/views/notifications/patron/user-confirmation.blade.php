<x-mail::message>
  <div class="text-base">
    Guten Tag<br><br>Besten Dank für Ihre Kontaktaufnahme.<br><br>Wir werden uns so bald wie möglich bei Ihnen melden.<br><br>Freundliche Grüsse<br><br>Museum Neuthal Textil- & Industriekultur<br><br>Im Neuthal 6<br>8344 Bäretswil<br>T direkt: +41 52 397 10 20<br>www.neuthal-industriekultur.ch
  </div>
  <br>
  <div class="text-base">
    <strong>Ihre Angaben</strong><br>
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
</x-mail::message>
