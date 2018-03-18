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
				<ul>
					<div id="output" ></div>
				</ul>
               
           </div>
		   
		   <div class="" id="inputRow">
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


</div>



@endsection


@section('scripts')
    <script type="text/javascript">
    function scrollToBottom(element) {
	    var elem = document.getElementById('chat-window');
	    elem.scrollTop = elem.scrollHeight;
    }
    </script>
	
	<script>
		Notification.requestPermission();
		function notifyMe(message) {
		  // Let's check if the browser supports notifications
		  if (!("Notification" in window)) {
			alert("This browser does not support desktop notification");
		  }

		  // Let's check whether notification permissions have already been granted
		  else if (Notification.permission === "granted") {
			// If it's okay let's create a notification
			var notification = new Notification(message);
		  }

		  // Otherwise, we need to ask the user for permission
		  else if (Notification.permission !== "denied") {
			Notification.requestPermission(function (permission) {
			  // If the user accepts, let's create a notification
			  if (permission === "granted") {
				var notification = new Notification(message);
			  }
			});
		  }

		  // At last, if the user has denied notifications, and you 
		  // want to be respectful there is no need to bother them any more.
		}
	</script>
	
   <script src="https://cdnjs.cloudflare.com/ajax/libs/socket.io/2.0.4/socket.io.js"></script>
   <script type="text/javascript">
    var socket = io.connect('http://rysz4rd.nazwa.pl:8890'); //{{--Request::url()--}}:8890
    var message = document.getElementById('message');
	
	var user = "{{ str_random(10) }}";



    btn = document.getElementById('sendBtn');
    output = document.getElementById('output');

    // Emit events
    btn.addEventListener('click', function(){
        socket.emit('messageWasSend', {
            message: message.value,
			user: user,

    });
        message.value = "";
    });

    // Listen for events
    socket.on('messageWasSend', function(data){
        output.innerHTML += '<li><strong>' + '</strong>' + data.message + '</li>';

        // scroll bottom
        var chatwindow = document.getElementById('chat-window');
        scrollToBottom(chatwindow);
		
		if(data.user !== user) 
		{
			// play sound
			var audio = new Audio( '{{ asset('mp3/chime.mp3') }} ');
			audio.play();
			notifyMe(data.message);
		} 
		
    });


    function sendMessage(event) {
        var key = event.keyCode;

        if(key == 13){
            document.getElementById("sendBtn").click();
        }
    };

  </script>




@endsection
