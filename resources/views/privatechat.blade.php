@extends('layout.master')

@section('content')
<div class="container-fluid" style="height: 100vh; padding: 0;">
    <div class="d-flex flex-column" style="height: 100%;">
        <!-- Chat Header -->
        <div style="padding: 15px; background-color: #f8f9fa; border-bottom: 1px solid #dee2e6;">
            <h5 style="margin: 0;">Chat with {{ $friend->name }}</h5>
        </div>

        <!-- Messages Container -->
        <div class="chat-box ms-3" style="
            flex-grow: 1;
            padding: 20px;
            background-color: #fff;
            margin-bottom: 0;
        ">
            @foreach ($messages as $message)
                <div class="message" style="
                    margin: 10px 0;
                    display: flex;
                    justify-content: {{ $message->userid === auth()->id() ? 'flex-end' : 'flex-start' }};
                ">
                    <div class="message-content" style="
                        max-width: 60%;
                        padding: 12px 16px;
                        border-radius: 15px;
                        background-color: {{ $message->userid === auth()->id() ? '#dcf8c6' : '#e0e0e0' }};
                        box-shadow: 0 1px 2px rgba(0,0,0,0.1);
                    ">
                        <p style="margin: 0;">{{ $message->message }}</p>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Message Input Form -->
        <div style="
            padding: 15px;
            background-color: #f8f9fa;
            border-top: 1px solid #dee2e6;
        ">
            <form action="{{ route('messages.send') }}" method="POST" style="display: flex; gap: 10px;">
                @csrf
                <input type="hidden" name="receiverid" value="{{ $friend->id }}">
                <input type="text"
                    name="message"
                    placeholder="Type your message..."
                    required
                    style="
                        flex: 1;
                        padding: 12px 20px;
                        border-radius: 20px;
                        border: 1px solid #dee2e6;
                        outline: none;
                    "
                >
                <button type="submit"
                    class="btn btn-primary"
                    style="
                        padding: 12px 25px;
                        border-radius: 20px;
                        border: none;
                    "
                >Send</button>
            </form>
        </div>
    </div>
</div>
@endsection
