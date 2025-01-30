<x-mail::message>
  <div class="text-base">
    Neue Anmeldung
  </div>
  <br>
  <div class="text-base">
    <strong>Veranstaltung</strong><br>
    {{ $data['title'] }}
  </div>
  <br>
  <div class="text-base">
    <strong>Datum</strong><br>
    {{ $data['date'] }}
  </div>
  <br>
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
  @if ($data['email'])
    <div class="text-base">
      <strong>E-Mail</strong><br>
      {{ $data['email'] }}
    </div>
    <br>
  @endif
  @if ($data['number_people'])
    <div class="text-base">
      <strong>Anzahl Personen</strong><br>
      {{ $data['number_people'] }}
    </div>
    <br>
  @endif
  <footer>
    <br>Museum Neuthal<br>Textil- & Industriekultur<br>Im Neuthal 6<br>8344 Bäretswil
  </footer>
</x-mail::message>