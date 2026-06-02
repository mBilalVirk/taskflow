 @php
     $subscription = $team->subscription;
     // \Log::debug(' subscription: ' . $subscription);
 @endphp
 <div class="bg-white rounded-lg shadow p-6 border-l-4 border-green-500 dark:bg-dark-card">
     <div class="flex items-center justify-between">
         <div>
             <h3 class="text-lg font-bold text-gray-900 dark:text-gray-400">Current Plan</h3>
             <p class="text-gray-600 mt-1">
                 <span class="text-2xl font-bold">{{ ucfirst($subscription->plan) }}</span>
                 <span class="text-gray-500 ml-2">
                     @if ($subscription->status === 'active')
                         • Active until
                         {{ $subscription->current_period_end ? $subscription->current_period_end->format('M d, Y') : '' }}
                     @else
                         • {{ ucfirst($subscription->status) }}
                     @endif
                 </span>
             </p>
         </div>
         <a href="{{ route('billing.dashboard', $team->id) }}"
             class="bg-blue-500 text-white px-6 py-3 rounded-lg hover:bg-blue-600 font-semibold">
             Manage Billing
         </a>
     </div>
 </div>
 {{-- <a href="{{ route('billing.pricing') }}">Billing</a> --}}
