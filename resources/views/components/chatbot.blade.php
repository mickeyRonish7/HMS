<div x-data="{ 
    open: false, 
    messages: [{sender: 'bot', text: 'Hi! I am the Hostel Assistant. How can I help you today?'}], 
    input: '',
    loading: false,
    sendMessage() {
        if (this.input.trim() === '') return;
        
        const userMsg = this.input;
        this.messages.push({sender: 'user', text: userMsg});
        this.input = '';
        this.loading = true;

        fetch('/chatbot/ask', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name=csrf-token]').getAttribute('content')
            },
            body: JSON.stringify({ message: userMsg })
        })
        .then(response => response.json())
        .then(data => {
            this.messages.push({sender: 'bot', text: data.reply});
            this.loading = false;
            this.$nextTick(() => {
                const chatBody = document.getElementById('chat-body');
                chatBody.scrollTop = chatBody.scrollHeight;
            });
        })
        .catch(error => {
            console.error('Error:', error);
            this.messages.push({sender: 'bot', text: 'Sorry, I encountered an error. Please try again.'});
            this.loading = false;
        });
    }
}" 
     class="fixed bottom-6 right-6 z-[9999]">
    
    <!-- Chat Icon -->
    <button @click="open = !open" 
            class="bg-indigo-600 hover:bg-indigo-700 text-white rounded-full p-4 shadow-lg transition-transform transform hover:scale-110 flex items-center justify-center">
        <svg x-show="!open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z"></path></svg>
        <svg x-show="open" class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
    </button>

    <!-- Chat Window -->
    <div x-show="open" 
         x-transition
         class="absolute bottom-20 right-0 w-80 bg-white rounded-lg shadow-2xl border border-gray-200 overflow-hidden flex flex-col h-96">
        
        <!-- Header -->
        <div class="bg-indigo-600 text-white p-4 font-bold flex justify-between items-center">
            <span>Hostel Assistant</span>
            <span class="text-xs bg-indigo-500 px-2 py-1 rounded">Online</span>
        </div>

        <!-- Body -->
        <div class="flex-1 p-4 overflow-y-auto bg-gray-50" id="chat-body">
            <template x-for="msg in messages">
                <div :class="msg.sender === 'user' ? 'text-right' : 'text-left'" class="mb-2">
                     <span :class="msg.sender === 'user' ? 'bg-indigo-600 text-white' : 'bg-white text-gray-800 border'" 
                           class="inline-block rounded-lg px-3 py-2 text-sm max-w-xs shadow-sm" 
                           x-html="msg.text"></span>
                </div>
            </template>
            <div x-show="loading" class="text-left mb-2">
                <span class="bg-gray-100 text-gray-500 inline-block rounded-lg px-3 py-2 text-sm shadow-sm">Typing...</span>
            </div>
        </div>

        <!-- Input -->
        <div class="p-3 bg-white border-t border-gray-200">
            <div class="flex outline-none">
                <input type="text" x-model="input" @keydown.enter="sendMessage()"
                class="flex-1 border border-gray-300 rounded-l-md px-3 py-2 text-sm focus:outline-none focus:ring-1 focus:ring-indigo-500" placeholder="Type a message...">
                <button @click="sendMessage()" class="bg-indigo-600 text-white px-4 rounded-r-md text-sm hover:bg-indigo-700">Send</button>
            </div>
        </div>
    </div>
</div>
