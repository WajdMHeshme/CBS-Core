<div
    x-show="open"
    x-cloak
    x-transition.opacity
    class="fixed  inset-0 z-[9999] flex items-center justify-center  bg-black/60"
    style="display: none;">
    <div class="w-full max-w-md mx-auto transform transition-all"
        @click.away="open = false">

        <div class="bg-white rounded-2xl shadow-2xl overflow-hidden">

            <!-- Header -->
            <div class="px-5 py-4 border-b flex justify-between items-center">
                <h2 class="font-semibold text-gray-800">Notification</h2>

                <button @click="open = false"
                    class="text-gray-400 hover:text-gray-700 text-lg">
                    ✕
                </button>
            </div>

            <!-- Body -->
            <div class="p-5 space-y-3">
                <p class="text-gray-800">
                    {{ $notification->data['message'] ?? 'No message' }}
                </p>

                <div class="text-sm text-gray-500 border-t pt-3">
                    By: {{ $notification->data['by'] ?? 'System' }}
                    <br>
                    {{ $notification->created_at->format('Y-m-d H:i') }}
                </div>
            </div>

            <!-- Footer -->
            <div class="px-5 py-4 bg-gray-50 flex justify-end">
                <button @click="open = false"
                    class="px-4 py-2 bg-black text-white rounded-lg text-sm">
                    Close
                </button>
            </div>

        </div>
    </div>
</div>

<div
    @click.away="showModal = false"
    class="w-full max-w-lg bg-white rounded-3xl shadow-2xl overflow-hidden">

    <!-- Header -->
    <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">

        <div>

            <h2
                x-text="selectedNotification.title"
                class="text-lg font-bold text-gray-800">
            </h2>

            <p class="text-xs text-gray-500 mt-1">

                <span x-text="selectedNotification.by"></span>

                •

                <span x-text="selectedNotification.date"></span>

            </p>

        </div>

        <button
            @click="showModal = false"
            class="text-gray-400 hover:text-gray-700 text-lg">

            ✕

        </button>

    </div>

    <!-- Body -->
    <div class="px-6 py-5">

        <p
            x-text="selectedNotification.message"
            class="text-sm leading-7 text-gray-700">
        </p>

    </div>

    <!-- Footer -->
    <div class="px-6 py-4 bg-gray-50 flex justify-end">

        <button
            @click="showModal = false"
            class="px-5 py-2 rounded-xl bg-black text-white text-sm font-medium hover:opacity-90 transition">

            Close

        </button>

    </div>

</div>

</div>
