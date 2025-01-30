<x-mail::message>
  <div class="text-base">
    Anfrage Museum Neuthal
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
  @if ($data['phone'])
    <div class="text-base">
      <strong>Telefon</strong><br>
      {{ $data['phone'] }}
    </div>
    <br>
  @endif
  @if ($data['message'])
    <div class="text-base">
      <strong>Mitteilung</strong><br>
      {!! nl2br($data['message']) !!}
    </div>
    <br>
  @endif
  <div class="text-base">
    <strong>Newsletter</strong><br>
    {{ $data['newsletter'] ? 'Ja' : 'Nein' }}
  </div>
  <br>
  <footer>
    <br>Museum Neuthal<br>Textil- & Industriekultur<br>Im Neuthal 6<br>8344 Bäretswil
  </footer>
</x-mail::message>