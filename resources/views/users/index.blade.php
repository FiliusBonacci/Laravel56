@extends('layouts.app')

@section('content')
<div class="container">
   <h1>Users</h1>


   {{--  {{ $users }}  --}}


    @foreach ($users as $user)
        <li><a href="{{route('chat_with', $user->id)}}">{{$user->name}}</a></li>
    @endforeach


</div>
@endsection
