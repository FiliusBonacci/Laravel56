@extends('layouts.app')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('css/mainStyle.css') }}">


<link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css" rel="stylesheet">


@endsection

@section('content')
<div class="container">
    <div class="row">
        <div class="col-md-8 col-md-offset-2">
            <div class="panel panel-default">
                <div class="panel-heading">Private Chat with {{ $user->name }}</div>
                <div class="panel-body">

                    <form id="group-chat" class="form-horizontal" role="form" method="POST" @submit.prevent="sendMessage">
                        {{ csrf_field() }}
                        <div id="messages">
                            <div v-if="messages.length">
                                <message v-for="message in messages" key="message.id" :sender="message.sender.name" :message="message.message" :createdat="message.created_at"></message>
                            </div>
                            <div v-else>
                                <div class="alert alert-warning">No chat yet!</div>
                            </div>
                        </div>
                        <span class="typing" v-if="isTyping"><i><span>@{{ isTyping }}</span> is typing</i></span>
                        <hr/>
                        <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }} chat-box">
                            <div class="col-md-10">
                                <textarea v-model="message" type="textarea" class="form-control" name="message" @keyup.enter="sendMessage" 
                                {{--  @keypress="userIsTyping({{$chatRoom->id}})"   --}}
                                required autofocus></textarea>

                                @if ($errors->has('email'))
                                <span class="help-block">
                                    <strong>{{ $errors->first('email') }}</strong>
                                </span>
                                @endif
                            </div>
                            <div class="col-md-2 chat-btn">
                                <button type="submit" class="btn btn-primary" :disabled="!message">
                                    Send
                                </button>
                            </div>
                        </div>
                    </form>
                </div>


            </div>
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
  

    <script>
    var fetchChatURL = "{{ route('chat.private.fetch', $conversationId) }}";
    var postChatURL = "{{ route('chat.private.store', $conversationId) }}";

    var app = new Vue({
	el: '#app',
	data: {
		messages: '',
		message: '',
		isTyping: '',
		onlineUsers: []
	},
	methods: {
		sendMessage: function(event) {
			if(this.message.trim() == '' || this.message.trim == null) {
				return;
			}
			var th = this;
			axios.post(postChatURL, {
				'message': th.message,
			})
			.then(function (response) {
				th.message = '';
				th.messages.push(response.data);
				th.adjustChatContainer();
			})
			.catch(function (error) {
				console.log(error);
			})
		},
		fetchChat: function() {
			var th = this;
			axios.get(fetchChatURL)
			.then(function (response) {
				th.messages = response.data;
				th.adjustChatContainer();
			})
			.catch(function (error) {
				console.log(error);
			})
		},
		sendGroupMessage: function() {
			var th = this;
			axios.post(groupMessageRoute, {
				'message': th.message,
			})
			.then(function (response) {
				th.message = '';
			})
			.catch(function (error) {
				console.log(error);
			})
		},
		updateChat: function(res) {
			this.messages.push(res.message);
		}, 
		adjustChatContainer: function() {
			var chatContainer = document.getElementById('messages');
			if(chatContainer) {
				chatContainer.scrollTop = chatContainer.scrollHeight;
			}
		},
		userIsTyping: function(chatRoomId) {
			window.Echo.private(`typing-room-${chatRoomId}`)
			.whisper('typing', {
				name: window.Laravel.user.name
			});
		},
	},
	mounted: function() {
		if(fetchChatURL) {
			this.fetchChat();
		}
	},
	updated: function() {
		this.adjustChatContainer();
	},
})
    
    
    
    
    </script>






@endsection
