@if(session()->has('notif'))
  @php
      $notif = session('notif');
  @endphp
  <div id="session-message" data-mensaje="{{ $notif['message'] }}" data-icon="{{ $notif['icon'] }}"></div>
@endif