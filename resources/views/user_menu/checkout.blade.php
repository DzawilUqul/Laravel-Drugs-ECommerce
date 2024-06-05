<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Checkout') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h1 class="text-2xl font-bold mb-4 dark:text-gray-100">Keranjang</h1>
            <div class="md:flex md:items-start gap-5">
                <!-- Items keranjang -->
                <div class="@if(count($cartItems) != 0) md:w-2/3 @endif w-full mb-4 sm:mb-0" id="cart-items">
                    @if (count($cartItems) == 0)
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                Belum ada item di keranjang
                            </div>
                        </div>
                    @else
                        <!-- Select All -->
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4">
                            <div class="p-6 text-gray-900 dark:text-gray-100">
                                <div class="flex items-center gap-4">
                                    <input type="checkbox" name="" id="check_all" checked>
                                    <span class="font-bold">Pilih Semua ({{ count($cartItems) }})</span>
                                </div>
                            </div>
                        </div>

                        @foreach ($cartItems as $cartItem)
                            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-4 cart-item" id="cart_item_{{ $cartItem->id }}">
                                <div class="p-6 text-gray-900 dark:text-gray-100">
                                    <div class="flex gap-4 items-center">
                                        <input type="checkbox" name="" class="item-checkbox" id="check_{{ $cartItem->id }}" checked>
                                        <img src="https://via.placeholder.com/100" alt="Item Image" class="w-20 h-20">
                                        <div>
                                            <p>{{ $cartItem->nama_obat }}</p>
                                        </div>
                                        <div class="ml-auto text-right">
                                            <p class="font-semibold">Rp{{ number_format($cartItem->harga, 0, ',', '.') }}</p>
                                            <div class="flex items-center justify-between p-1 gap-2 mt-2 border border-gray-300 rounded-lg">
                                                <button class="text-gray-500 hover:text-gray-700 reduce-quantity" data-id="{{ $cartItem->id }}">
                                                    <svg class="w-6 h-6 text-gray-500 dark:text-white" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2" d="M5 12h14" />
                                                    </svg>
                                                </button>
                                                <span id="quantity_{{ $cartItem->id }}">{{ $cartItem->pivot->quantity }}</span>
                                                <button class="text-gray-500 hover:text-gray-700 add-quantity" data-id="{{ $cartItem->id }}">
                                                    <svg class="w-6 h-6 text-gray-500 dark:text-white" aria-hidden="true"
                                                        xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                                        fill="none" viewBox="0 0 24 24">
                                                        <path stroke="currentColor" stroke-linecap="round"
                                                            stroke-linejoin="round" stroke-width="2" d="M5 12h14m-7 7V5" />
                                                    </svg>
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    @endif
                </div>

                @if (count($cartItems) !== 0)
                    <!-- Ringkasan belanja -->
                    <div class="md:flex-1 bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg self-start">
                        <div class="p-6 text-gray-900 dark:text-gray-100">
                            <h2 class="font-bold text-lg mb-4">Ringkasan belanja</h2>
                            <p>Total</p>
                            <p class="font-bold text-2xl mb-4" id="total-amount">Rp{{ number_format($total, 0, ',', '.') }}</p>
                            <button class="bg-green-600 text-white py-2 px-4 rounded-lg w-full" id="purchase-button">Beli</button>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>

    @push('scripts')
    <script>
        $(document).ready(function() {
            function updateTotal() {
                var total = 0;
                $('.item-checkbox:checked').each(function() {
                    var itemId = $(this).attr('id').replace('check_', '');
                    var itemQuantity = parseInt($('#quantity_' + itemId).text());
                    var itemPrice = parseFloat($('#cart_item_' + itemId).find('.font-semibold').text().replace('Rp', '').replace(/\./g, ''));
                    total += itemPrice * itemQuantity;
                });
                $('#total-amount').text('Rp' + total.toLocaleString('id-ID'));
            }
    
            $('#check_all').on('change', function() {
                $('.item-checkbox').prop('checked', this.checked);
                updateTotal();
            });
    
            $('.item-checkbox').on('change', function() {
                if ($('.item-checkbox:checked').length === $('.item-checkbox').length) {
                    $('#check_all').prop('checked', true);
                } else {
                    $('#check_all').prop('checked', false);
                }
                updateTotal();
            });
    
            $('.add-quantity').on('click', function() {
                var obatId = $(this).data('id');
                $.ajax({
                    url: "{{ route('checkout.addQuantity') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        obat_id: obatId
                    },
                    success: function(response) {
                        $('#quantity_' + obatId).text(response.quantity);
                        updateTotal();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error: ', textStatus, errorThrown);
                        console.error('Response Text: ', jqXHR.responseText);
                        alert('An error occurred while updating the quantity. Please try again later.');
                    }
                });
            });

            $('.reduce-quantity').on('click', function() {
                var obatId = $(this).data('id');
                $.ajax({
                    url: "{{ route('checkout.reduceQuantity') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        obat_id: obatId
                    },
                    success: function(response) {
                        if (response.deleted) {
                            $('#cart_item_' + obatId).remove();
                        } else {
                            $('#quantity_' + obatId).text(response.quantity);
                        }
                        updateTotal();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error: ', textStatus, errorThrown);
                        console.error('Response Text: ', jqXHR.responseText);
                        alert('An error occurred while updating the quantity. Please try again later.');
                    }
                });
            });
    
            $('#purchase-button').on('click', function() {
                var checkedItems = $('.item-checkbox:checked').map(function() {
                    return $(this).attr('id').replace('check_', '');
                }).get();

                $.ajax({
                    url: "{{ route('checkout.createTransaction') }}",
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        checked_items: checkedItems
                    },
                    success: function(response) {
                        alert(response.message);
                        location.reload();
                    },
                    error: function(jqXHR, textStatus, errorThrown) {
                        console.error('Error: ', textStatus, errorThrown);
                        console.error('Response Text: ', jqXHR.responseText);
                        alert('An error occurred while processing the transaction. Please try again later.');
                    }
                });
            });

            // Initialize total on page load
            updateTotal();
        });
    </script>
    
    @endpush
</x-app-layout>
