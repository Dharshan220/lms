<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>AI Tutor - Nano Spark LMS</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Space+Mono:wght@400;700&family=IBM+Plex+Sans:wght@300;400;500;600&family=JetBrains+Mono:wght@400;500;700&display=swap" rel="stylesheet">
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    <style>
        *, *::before, *::after {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            background: #050505;
            color: #FFFFFF;
            font-family: 'IBM Plex Sans', sans-serif;
            height: 100vh;
            overflow: hidden;
            display: flex;
            flex-direction: column;
        }

        .chat-container {
            display: flex;
            flex-direction: column;
            height: 100vh;
            max-width: 900px;
            margin: 0 auto;
            width: 100%;
        }

        .chat-header {
            background: #121212;
            border-bottom: 1px solid #1a1a1a;
            padding: 16px 24px;
            display: flex;
            align-items: center;
            gap: 14px;
            flex-shrink: 0;
        }

        .chat-header .robot-icon {
            width: 44px;
            height: 44px;
            background: linear-gradient(135deg, #FFD400 0%, #FFC000 100%);
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
        }

        .chat-header .robot-icon svg {
            width: 24px;
            height: 24px;
            fill: #050505;
        }

        .chat-header .branding {
            display: flex;
            flex-direction: column;
        }

        .chat-header .branding h1 {
            font-family: 'Space Mono', monospace;
            font-size: 18px;
            font-weight: 700;
            color: #FFD400;
            line-height: 1.2;
        }

        .chat-header .branding span {
            font-size: 12px;
            color: #888888;
            font-family: 'IBM Plex Sans', sans-serif;
        }

        .chat-header .status-dot {
            width: 8px;
            height: 8px;
            background: #00ff88;
            border-radius: 50%;
            margin-left: auto;
            animation: pulse-dot 2s ease-in-out infinite;
        }

        .chat-header .status-text {
            font-size: 12px;
            color: #888888;
            font-family: 'JetBrains Mono', monospace;
        }

        @keyframes pulse-dot {
            0%, 100% { opacity: 1; }
            50% { opacity: 0.4; }
        }

        .chat-messages {
            flex: 1;
            overflow-y: auto;
            padding: 24px;
            display: flex;
            flex-direction: column;
            gap: 20px;
            scroll-behavior: smooth;
        }

        .chat-messages::-webkit-scrollbar {
            width: 6px;
        }

        .chat-messages::-webkit-scrollbar-track {
            background: transparent;
        }

        .chat-messages::-webkit-scrollbar-thumb {
            background: #333;
            border-radius: 3px;
        }

        .chat-messages::-webkit-scrollbar-thumb:hover {
            background: #555;
        }

        .message {
            display: flex;
            gap: 12px;
            max-width: 80%;
            animation: message-in 0.3s ease-out;
        }

        @keyframes message-in {
            from {
                opacity: 0;
                transform: translateY(10px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }

        .message.bot {
            align-self: flex-start;
        }

        .message.user {
            align-self: flex-end;
            flex-direction: row-reverse;
        }

        .message-avatar {
            width: 34px;
            height: 34px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            flex-shrink: 0;
            margin-top: 2px;
        }

        .message.bot .message-avatar {
            background: #1a1a1a;
            border: 1px solid #2a2a2a;
        }

        .message.user .message-avatar {
            background: #2a2200;
            border: 1px solid #FFD40044;
        }

        .message-avatar svg {
            width: 18px;
            height: 18px;
        }

        .message.bot .message-avatar svg {
            fill: #FFD400;
        }

        .message.user .message-avatar svg {
            fill: #FFD400;
        }

        .message-content {
            padding: 14px 18px;
            border-radius: 16px;
            line-height: 1.65;
            font-size: 14px;
            font-family: 'IBM Plex Sans', sans-serif;
        }

        .message.bot .message-content {
            background: #121212;
            border: 1px solid #1a1a1a;
            color: #CFCFCF;
            border-top-left-radius: 4px;
        }

        .message.user .message-content {
            background: linear-gradient(135deg, #FFD400 0%, #FFC000 100%);
            color: #050505;
            font-weight: 500;
            border-top-right-radius: 4px;
        }

        .message-content pre {
            background: #0a0a0a;
            border: 1px solid #1a1a1a;
            border-radius: 8px;
            padding: 14px;
            overflow-x: auto;
            margin: 10px 0;
            font-family: 'JetBrains Mono', monospace;
            font-size: 13px;
            line-height: 1.5;
            color: #FFD400;
        }

        .message-content code {
            font-family: 'JetBrains Mono', monospace;
            background: #1a1a1a;
            padding: 2px 6px;
            border-radius: 4px;
            font-size: 13px;
            color: #FFD400;
        }

        .message-content pre code {
            background: transparent;
            padding: 0;
            border-radius: 0;
            color: #FFD400;
        }

        .message-content p {
            margin-bottom: 8px;
        }

        .message-content p:last-child {
            margin-bottom: 0;
        }

        .message-content ul, .message-content ol {
            margin: 8px 0;
            padding-left: 20px;
        }

        .message-content li {
            margin-bottom: 4px;
        }

        .message-content strong {
            color: #FFD400;
            font-weight: 600;
        }

        .message.user .message-content strong {
            color: #050505;
        }

        .typing-indicator {
            display: flex;
            gap: 5px;
            padding: 6px 0;
        }

        .typing-indicator span {
            width: 7px;
            height: 7px;
            background: #FFD400;
            border-radius: 50%;
            animation: typing-bounce 1.4s ease-in-out infinite;
        }

        .typing-indicator span:nth-child(2) {
            animation-delay: 0.2s;
        }

        .typing-indicator span:nth-child(3) {
            animation-delay: 0.4s;
        }

        @keyframes typing-bounce {
            0%, 60%, 100% { transform: translateY(0); opacity: 0.4; }
            30% { transform: translateY(-6px); opacity: 1; }
        }

        .chat-input-area {
            background: #121212;
            border-top: 1px solid #1a1a1a;
            padding: 18px 24px;
            flex-shrink: 0;
        }

        .chat-input-wrapper {
            display: flex;
            gap: 12px;
            align-items: flex-end;
            background: #0a0a0a;
            border: 1px solid #1a1a1a;
            border-radius: 14px;
            padding: 8px 8px 8px 18px;
            transition: border-color 0.2s ease;
        }

        .chat-input-wrapper:focus-within {
            border-color: #FFD40066;
        }

        .chat-input-wrapper textarea {
            flex: 1;
            background: transparent;
            border: none;
            outline: none;
            color: #FFFFFF;
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 14px;
            line-height: 1.5;
            resize: none;
            max-height: 120px;
            padding: 8px 0;
        }

        .chat-input-wrapper textarea::placeholder {
            color: #888888;
        }

        .send-button {
            width: 42px;
            height: 42px;
            background: linear-gradient(135deg, #FFD400 0%, #FFC000 100%);
            border: none;
            border-radius: 10px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all 0.2s ease;
            flex-shrink: 0;
        }

        .send-button:hover {
            transform: scale(1.05);
            box-shadow: 0 0 20px #FFD40033;
        }

        .send-button:active {
            transform: scale(0.95);
        }

        .send-button:disabled {
            opacity: 0.4;
            cursor: not-allowed;
            transform: none;
            box-shadow: none;
        }

        .send-button svg {
            width: 20px;
            height: 20px;
            fill: #050505;
        }

        .welcome-message {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            text-align: center;
            padding: 60px 24px;
            gap: 16px;
        }

        .welcome-message .welcome-icon {
            width: 72px;
            height: 72px;
            background: linear-gradient(135deg, #FFD40022 0%, #FFD40008 100%);
            border: 1px solid #FFD40033;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .welcome-message .welcome-icon svg {
            width: 36px;
            height: 36px;
            fill: #FFD400;
        }

        .welcome-message h2 {
            font-family: 'Space Mono', monospace;
            font-size: 22px;
            color: #FFD400;
        }

        .welcome-message p {
            color: #888888;
            font-size: 14px;
            max-width: 420px;
            line-height: 1.6;
        }

        .welcome-suggestions {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            justify-content: center;
            margin-top: 12px;
        }

        .welcome-suggestions button {
            background: #121212;
            border: 1px solid #1a1a1a;
            color: #CFCFCF;
            padding: 10px 16px;
            border-radius: 10px;
            font-family: 'IBM Plex Sans', sans-serif;
            font-size: 13px;
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .welcome-suggestions button:hover {
            border-color: #FFD40066;
            color: #FFD400;
            background: #1a1a1a;
        }

        @media (max-width: 640px) {
            .message {
                max-width: 90%;
            }
            .chat-messages {
                padding: 16px;
            }
            .chat-input-area {
                padding: 14px 16px;
            }
            .chat-header {
                padding: 14px 16px;
            }
            .welcome-message {
                padding: 40px 16px;
            }
        }
    </style>
</head>
<body>
    <div class="chat-container" x-data="chatApp()">
        <div class="chat-header">
            <div class="robot-icon">
                <svg viewBox="0 0 24 24"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1.07A7.001 7.001 0 0 1 7.07 19H6a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M9.5 14a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3m5 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3"/></svg>
            </div>
            <div class="branding">
                <h1>Nano AI Tutor</h1>
                <span>Always here to help you learn</span>
            </div>
            <div class="status-dot"></div>
            <span class="status-text">Online</span>
        </div>

        <div class="chat-messages" x-ref="messagesContainer" @scroll="handleScroll">
            <template x-if="messages.length === 0">
                <div class="welcome-message">
                    <div class="welcome-icon">
                        <svg viewBox="0 0 24 24"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1.07A7.001 7.001 0 0 1 7.07 19H6a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M9.5 14a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3m5 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3"/></svg>
                    </div>
                    <h2>Welcome to Nano AI Tutor</h2>
                    <p>Your personal AI learning assistant. Ask me anything about programming, science, math, or any topic you need help with.</p>
                    <div class="welcome-suggestions">
                        <button @click="sendSuggestion('Explain Python list comprehension')">Python Lists</button>
                        <button @click="sendSuggestion('What is a binary search tree?')">Data Structures</button>
                        <button @click="sendSuggestion('Help me understand CSS Flexbox')">CSS Flexbox</button>
                        <button @click="sendSuggestion('Explain the CAP theorem')">Databases</button>
                    </div>
                </div>
            </template>

            <template x-for="(msg, index) in messages" :key="index">
                <div class="message" :class="msg.role">
                    <div class="message-avatar">
                        <template x-if="msg.role === 'bot'">
                            <svg viewBox="0 0 24 24"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1.07A7.001 7.001 0 0 1 7.07 19H6a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M9.5 14a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3m5 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3"/></svg>
                        </template>
                        <template x-if="msg.role === 'user'">
                            <svg viewBox="0 0 24 24"><path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4m0 2c-2.67 0-8 1.34-8 4v2h16v-2c0-2.66-5.33-4-8-4"/></svg>
                        </template>
                    </div>
                    <div class="message-content" x-html="renderMarkdown(msg.content)"></div>
                </div>
            </template>

            <template x-if="loading">
                <div class="message bot">
                    <div class="message-avatar">
                        <svg viewBox="0 0 24 24"><path d="M12 2a2 2 0 0 1 2 2c0 .74-.4 1.39-1 1.73V7h1a7 7 0 0 1 7 7h1a1 1 0 0 1 1 1v3a1 1 0 0 1-1 1h-1.07A7.001 7.001 0 0 1 7.07 19H6a1 1 0 0 1-1-1v-3a1 1 0 0 1 1-1h1a7 7 0 0 1 7-7h1V5.73c-.6-.34-1-.99-1-1.73a2 2 0 0 1 2-2M9.5 14a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3m5 0a1.5 1.5 0 1 0 0 3 1.5 1.5 0 0 0 0-3"/></svg>
                    </div>
                    <div class="message-content">
                        <div class="typing-indicator">
                            <span></span>
                            <span></span>
                            <span></span>
                        </div>
                    </div>
                </div>
            </template>
        </div>

        <div class="chat-input-area">
            <div class="chat-input-wrapper">
                <textarea
                    x-model="userInput"
                    @keydown.enter.prevent="if (!$event.shiftKey) sendMessage()"
                    placeholder="Ask Nano AI Tutor anything..."
                    rows="1"
                    x-ref="inputField"
                    @input="autoResize($event.target)"
                ></textarea>
                <button
                    class="send-button"
                    @click="sendMessage()"
                    :disabled="loading || userInput.trim() === ''"
                    title="Send message"
                >
                    <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"/></svg>
                </button>
            </div>
        </div>
    </div>

    <script>
        function chatApp() {
            return {
                messages: [],
                userInput: '',
                loading: false,

                async sendMessage() {
                    const text = this.userInput.trim();
                    if (!text || this.loading) return;

                    this.messages.push({ role: 'user', content: text });
                    this.userInput = '';
                    this.loading = true;
                    this.$nextTick(() => this.scrollToBottom());

                    if (this.$refs.inputField) {
                        this.$refs.inputField.style.height = 'auto';
                    }

                    try {
                        const response = await fetch('{{ route("ai.chat.send") }}', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                                'Accept': 'application/json',
                            },
                            body: JSON.stringify({ message: text }),
                        });

                        const data = await response.json();

                        if (response.ok && data.response) {
                            this.messages.push({ role: 'bot', content: data.response });
                        } else {
                            const errorMsg = data.message || data.error || 'AI Tutor is temporarily unavailable. Please try again in a moment.';
                            this.messages.push({ role: 'bot', content: errorMsg });
                        }
                    } catch (err) {
                        this.messages.push({
                            role: 'bot',
                            content: 'Network error. Please check your connection and try again.'
                        });
                    } finally {
                        this.loading = false;
                        this.$nextTick(() => this.scrollToBottom());
                    }
                },

                sendSuggestion(text) {
                    this.userInput = text;
                    this.sendMessage();
                },

                scrollToBottom() {
                    const container = this.$refs.messagesContainer;
                    if (container) {
                        container.scrollTop = container.scrollHeight;
                    }
                },

                handleScroll() {},

                autoResize(el) {
                    el.style.height = 'auto';
                    el.style.height = Math.min(el.scrollHeight, 120) + 'px';
                },

                renderMarkdown(text) {
                    if (!text) return '';
                    let html = text;

                    // Code blocks with language tag
                    html = html.replace(/```(\w*)\n([\s\S]*?)```/g, function (match, lang, code) {
                        const escaped = code.replace(/&/g, '&amp;').replace(/</g, '&lt;').replace(/>/g, '&gt;');
                        return '<pre><code class="language-' + lang + '">' + escaped + '</code></pre>';
                    });

                    // Inline code
                    html = html.replace(/`([^`]+)`/g, '<code>$1</code>');

                    // Bold
                    html = html.replace(/\*\*(.+?)\*\*/g, '<strong>$1</strong>');

                    // Italic
                    html = html.replace(/\*(.+?)\*/g, '<em>$1</em>');

                    // Unordered lists
                    html = html.replace(/^[\-\*] (.+)$/gm, '<li>$1</li>');
                    html = html.replace(/(<li>.*<\/li>\n?)+/g, '<ul>$&</ul>');

                    // Line breaks to paragraphs
                    html = html.split(/\n\n+/).map(function (block) {
                        block = block.trim();
                        if (!block) return '';
                        if (block.startsWith('<pre>') || block.startsWith('<ul>') || block.startsWith('<ol>')) return block;
                        return '<p>' + block.replace(/\n/g, '<br>') + '</p>';
                    }).join('');

                    return html;
                }
            };
        }
    </script>
</body>
</html>
