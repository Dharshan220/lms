@extends('layouts.app')

@section('title', 'AI Tutor - Nano Spark')

@section('content')
@push('styles')
<style>
    .chat-container { height: calc(100vh - 160px); min-height: 500px; }
    .chat-sidebar { border-right: 1px solid #e9ecef; }
    .chat-history-item { cursor: pointer; transition: background 0.2s; border-left: 3px solid transparent; }
    .chat-history-item:hover, .chat-history-item.active { background: #f8f9fa; border-left-color: #667eea; }
    .chat-messages { overflow-y: auto; scroll-behavior: smooth; }
    .chat-bubble { max-width: 75%; padding: 12px 16px; border-radius: 18px; margin-bottom: 12px; word-wrap: break-word; line-height: 1.5; }
    .chat-bubble.user { background: linear-gradient(135deg, #667eea, #764ba2); color: #fff; margin-left: auto; border-bottom-right-radius: 4px; }
    .chat-bubble.ai { background: #f1f3f5; color: #212529; margin-right: auto; border-bottom-left-radius: 4px; }
    .chat-bubble.ai pre { background: #1e1e2e; color: #cdd6f4; padding: 12px; border-radius: 8px; overflow-x: auto; font-size: 0.85rem; }
    .chat-bubble.ai code { font-family: 'Fira Code', 'Consolas', monospace; font-size: 0.85rem; }
    .chat-bubble.ai p code { background: #e9ecef; padding: 2px 6px; border-radius: 4px; color: #e83e8c; }
    .typing-indicator { display: flex; gap: 4px; padding: 8px 0; }
    .typing-indicator span { width: 8px; height: 8px; border-radius: 50%; background: #adb5bd; animation: typing 1.4s infinite ease-in-out; }
    .typing-indicator span:nth-child(2) { animation-delay: 0.2s; }
    .typing-indicator span:nth-child(3) { animation-delay: 0.4s; }
    @keyframes typing { 0%,60%,100%{transform:translateY(0);opacity:0.4} 30%{transform:translateY(-8px);opacity:1} }
    .chat-tabs .nav-link { font-size: 0.85rem; font-weight: 600; }
    .chat-tabs .nav-link.active { background: #667eea; color: #fff; border-color: #667eea; }
    .chat-input-area { border-top: 1px solid #e9ecef; }
    .chat-input-area textarea { resize: none; border-radius: 20px; }
    .sidebar-overlay { display: none; }
    @media (max-width: 767.98px) {
        .chat-sidebar { position: fixed; left: -280px; top: 0; bottom: 0; width: 280px; z-index: 1050; background: #fff; transition: left 0.3s; }
        .chat-sidebar.show { left: 0; }
        .sidebar-overlay { display: none; position: fixed; inset: 0; background: rgba(0,0,0,0.5); z-index: 1040; }
        .sidebar-overlay.show { display: block; }
        .chat-bubble { max-width: 90%; }
    }
</style>
@endpush

<div class="px-3 px-md-4 py-4">
    {{-- Header --}}
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div class="d-flex align-items-center gap-3">
            <button class="btn btn-outline-secondary d-md-none" onclick="toggleChatSidebar()">
                <i class="bi bi-list"></i>
            </button>
            <div class="rounded bg-primary bg-opacity-10 d-flex align-items-center justify-content-center" style="width:48px;height:48px;">
                <i class="bi bi-robot text-primary" style="font-size:1.4rem;"></i>
            </div>
            <div>
                <h4 class="fw-bold mb-0">AI Tutor</h4>
                <p class="text-muted mb-0" style="font-size:0.8rem;">Your personal AI learning assistant</p>
            </div>
        </div>
    </div>

    {{-- Chat Type Tabs --}}
    <ul class="nav nav-pills chat-tabs gap-2 mb-3">
        <li class="nav-item">
            <a class="nav-link active rounded-pill" href="#" onclick="switchChatType('tutor', this)">
                <i class="bi bi-mortarboard me-1"></i> AI Tutor
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill" href="#" onclick="switchChatType('coding', this)">
                <i class="bi bi-code-slash me-1"></i> Coding Assistant
            </a>
        </li>
        <li class="nav-item">
            <a class="nav-link rounded-pill" href="#" onclick="switchChatType('quiz', this)">
                <i class="bi bi-question-circle me-1"></i> Quiz Generator
            </a>
        </li>
    </ul>

    <div class="card border-0 shadow-sm">
        <div class="card-body p-0">
            <div class="chat-container d-flex">
                {{-- Sidebar Overlay --}}
                <div class="sidebar-overlay" id="sidebarOverlay" onclick="toggleChatSidebar()"></div>

                {{-- Sidebar: Chat History --}}
                <div class="chat-sidebar d-none d-md-flex flex-column" style="width:280px; flex-shrink:0;">
                    <div class="p-3 border-bottom">
                        <button class="btn btn-primary btn-sm w-100 rounded-pill" onclick="startNewChat()">
                            <i class="bi bi-plus-lg me-1"></i> New Chat
                        </button>
                    </div>
                    <div class="flex-grow-1 overflow-auto p-2">
                        @if(isset($chats) && $chats->count())
                            @foreach($chats as $chat)
                                <div class="chat-history-item rounded p-2 mb-1">
                                    <div class="d-flex align-items-center gap-2">
                                        <i class="bi bi-chat-dots text-muted" style="font-size:0.85rem;"></i>
                                        <div class="min-width-0 flex-grow-1">
                                            <small class="fw-semibold text-truncate d-block">{{ Str::limit($chat->message, 30) }}</small>
                                            <small class="text-muted" style="font-size:0.7rem;">{{ $chat->created_at?->diffForHumans() }}</small>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-chat-dots text-muted" style="font-size:1.5rem;"></i>
                                <p class="text-muted mt-2 mb-0" style="font-size:0.8rem;">No chats yet</p>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Main Chat Area --}}
                <div class="flex-grow-1 d-flex flex-column">
                    {{-- Messages --}}
                    <div class="chat-messages flex-grow-1 p-3" id="chatMessages">
                        <div class="chat-bubble ai">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-robot"></i>
                                <small class="fw-bold">Nano AI</small>
                            </div>
                            <div>Hello! I'm your AI tutor. I can help you with your courses, explain concepts, generate quizzes, and more. What would you like to learn today?</div>
                        </div>

                        @if(isset($chats) && $chats->count())
                            @foreach($chats as $chat)
                                <div class="chat-bubble user">
                                    <div>{{ nl2br(e($chat->message)) }}</div>
                                </div>
                                <div class="chat-bubble ai">
                                    <div class="d-flex align-items-center gap-2 mb-1">
                                        <i class="bi bi-robot"></i>
                                        <small class="fw-bold">Nano AI</small>
                                    </div>
                                    <div>{!! nl2br(e($chat->response)) !!}</div>
                                </div>
                            @endforeach
                        @endif

                        {{-- Typing Indicator --}}
                        <div class="chat-bubble ai" id="typingIndicator" style="display:none;">
                            <div class="d-flex align-items-center gap-2 mb-1">
                                <i class="bi bi-robot"></i>
                                <small class="fw-bold">Nano AI</small>
                            </div>
                            <div class="typing-indicator">
                                <span></span><span></span><span></span>
                            </div>
                        </div>
                    </div>

                    {{-- Input Area --}}
                    <div class="chat-input-area p-3">
                        <form id="chatForm" onsubmit="sendMessage(event)">
                            @csrf
                            <input type="hidden" name="chat_type" id="chatType" value="tutor">
                            <input type="hidden" name="chat_id" id="chatId" value="{{ $activeChatId ?? '' }}">
                            <div class="d-flex gap-2 align-items-end">
                                <div class="flex-grow-1 position-relative">
                                    <textarea name="message" class="form-control" id="chatInput" rows="1"
                                        placeholder="Type your question here..."
                                        style="border-radius:20px; padding-right:50px; max-height:120px;"
                                        onkeydown="handleKeyDown(event)" oninput="autoResize(this)"></textarea>
                                    <button type="button" class="btn position-absolute" style="right:8px;bottom:8px;padding:4px 8px;color:#667eea;" onclick="document.getElementById('chatInput').focus()">
                                        <i class="bi bi-paperclip"></i>
                                    </button>
                                </div>
                                <button type="submit" class="btn btn-primary rounded-circle flex-shrink-0" id="sendBtn" style="width:44px;height:44px;">
                                    <i class="bi bi-send-fill"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const chatMessages = document.getElementById('chatMessages');
    const chatForm = document.getElementById('chatForm');
    const chatInput = document.getElementById('chatInput');
    const typingIndicator = document.getElementById('typingIndicator');
    const sendBtn = document.getElementById('sendBtn');

    function scrollToBottom() {
        chatMessages.scrollTop = chatMessages.scrollHeight;
    }

    function autoResize(el) {
        el.style.height = 'auto';
        el.style.height = Math.min(el.scrollHeight, 120) + 'px';
    }

    function handleKeyDown(e) {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            chatForm.dispatchEvent(new Event('submit'));
        }
    }

    function switchChatType(type, el) {
        document.getElementById('chatType').value = type;
        document.querySelectorAll('.chat-tabs .nav-link').forEach(l => l.classList.remove('active'));
        el.classList.add('active');

        const placeholders = {
            tutor: 'Ask your AI tutor anything...',
            coding: 'Paste your code or describe your coding problem...',
            quiz: 'Enter a topic to generate quiz questions...'
        };
        chatInput.placeholder = placeholders[type] || placeholders.tutor;
    }

    function startNewChat() {
        @if(route('ai.chat.index'))
            window.location.href = '{{ route("ai.chat.index") }}';
        @endif
    }

    function loadChat(chatId) {
        @if(route('ai.chat.index'))
            window.location.href = '{{ route("ai.chat.index") }}?chat=' + chatId;
        @endif
    }

    function toggleChatSidebar() {
        const sidebar = document.querySelector('.chat-sidebar');
        const overlay = document.getElementById('sidebarOverlay');
        sidebar.classList.toggle('show');
        overlay.classList.toggle('show');
    }

    function sendMessage(e) {
        e.preventDefault();
        const message = chatInput.value.trim();
        if (!message) return;

        const userBubble = document.createElement('div');
        userBubble.className = 'chat-bubble user';
        userBubble.innerHTML = '<div>' + message.replace(/</g, '&lt;').replace(/>/g, '&gt;').replace(/\n/g, '<br>') + '</div>';

        const typingEl = document.getElementById('typingIndicator');
        chatMessages.insertBefore(userBubble, typingEl);
        chatInput.value = '';
        chatInput.style.height = 'auto';
        typingEl.style.display = 'block';
        scrollToBottom();
        sendBtn.disabled = true;

        const formData = new FormData(chatForm);
        fetch('{{ route("ai.chat.send") }}', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                'Accept': 'application/json',
                'X-Requested-With': 'XMLHttpRequest'
            },
            body: formData
        })
        .then(r => r.json())
        .then(data => {
            typingEl.style.display = 'none';
            const aiBubble = document.createElement('div');
            aiBubble.className = 'chat-bubble ai';
            const response = data.response || data.message || 'Sorry, I could not process your request.';
            let formatted = response.replace(/```(\w+)?\n([\s\S]*?)```/g, '<pre><code>$2</code></pre>');
            formatted = formatted.replace(/`([^`]+)`/g, '<code>$1</code>');
            formatted = formatted.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
            formatted = formatted.replace(/\n/g, '<br>');
            aiBubble.innerHTML = '<div class="d-flex align-items-center gap-2 mb-1"><i class="bi bi-robot"></i><small class="fw-bold">Nano AI</small></div><div>' + formatted + '</div>';
            chatMessages.insertBefore(aiBubble, typingEl);
            scrollToBottom();
            if (data.chat_id) document.getElementById('chatId').value = data.chat_id;
        })
        .catch(() => {
            typingEl.style.display = 'none';
            const errBubble = document.createElement('div');
            errBubble.className = 'chat-bubble ai';
            errBubble.innerHTML = '<div class="d-flex align-items-center gap-2 mb-1"><i class="bi bi-robot"></i><small class="fw-bold">Nano AI</small></div><div>Sorry, something went wrong. Please try again.</div>';
            chatMessages.insertBefore(errBubble, typingEl);
            scrollToBottom();
        })
        .finally(() => { sendBtn.disabled = false; chatInput.focus(); });
    }

    scrollToBottom();
</script>
@endpush
@endsection
