@if (session()->get('notif.message'))
<div class="alert alert-{{ session()->get('notif.style') }} alert-dismissible">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <span class='fa fa-{{ session()->get('notif.icon') }}'></span> {{ session()->get('notif.message') }}
</div>
@endif

@if ($errors->any())
<div class="alert alert-danger">
    {{-- <ul style="list-style-type: none;"> --}}
        @foreach ($errors->all() as $error)
        <span class='fa fa-times-circle'></span> {{ $error }}<br>
        @endforeach
    {{-- </ul> --}}
</div>
@endif