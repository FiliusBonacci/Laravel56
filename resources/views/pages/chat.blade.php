@extends('layouts.app')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/mainStyle.css') }}">


<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">
 <link href="{{ asset('assets/emoji-picker/lib/css/emoji.css') }}" rel="stylesheet">

@endsection

@section('content')
<div class="container">
    <div class="row">

        <div class="col align-self-center">
           <h1>Chat</h1>



           <div id="chat-window" class="card">
               <div id="output"></div>
           </div>
           <input id="message" type="text" placeholder="Message" class="form-control"
                    onkeyup="sendMessage(event)"
                    data-emojiable="true"
            />
           <button id="sendBtn"
                   class="btn btn-primary"
           >Send</button>

       </div>
   </div>


</div>



@endsection


@section('scripts')
    <script type="text/javascript">
    function scrollToBottom(element) {
            if(element.scrollY!=0)
            {
                setTimeout(function() {
                   element.scrollTop = element.scrollHeight;
                    scrollToBottom();
                }, 100);
            }
    }
    </script>
   <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>
   <script type="text/javascript">
    var socket = io.connect('localhost:8890'); //{{--Request::url()--}}:8890
    var message = document.getElementById('message');



    btn = document.getElementById('sendBtn');
    output = document.getElementById('output');

    // Emit events
    btn.addEventListener('click', function(){
        socket.emit('messageWasSend', {
            message: message.value,

    });
        message.value = "";
    });

    // Listen for events
    socket.on('messageWasSend', function(data){
        output.innerHTML += '<p><strong>' + '</strong>' + data.message + '</p>';

        // scroll bottom
        var chatwindow = document.getElementById('chat-window');
        scrollToBottom(chatwindow);
    });


    function sendMessage(event) {
        var key = event.keyCode;

        if(key == 13){
            document.getElementById("sendBtn").click();
        }
    };

  </script>




@endsection
