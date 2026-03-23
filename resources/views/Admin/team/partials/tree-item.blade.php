@php
$user = $item['user'];
$hasChildren = $item['hasChildren'];
$children = $item['children'] ?? [];
$subordinates = $item['subordinates'] ?? [];
@endphp

<div class="team-item" style="margin-left: {{ $level * 24 }}px;">
    <!-- User Card -->
    <div class="flex items-center p-3 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-all mb-2 relative">
        @if($hasChildren)
            <button onclick="toggleBranch(this)" class="mr-2 p-1 rounded hover:bg-gray-100 cursor-pointer flex-shrink-0">
                <i class="fas fa-chevron-down toggle-icon text-gray-400 text-xs"></i>
            </button>
        @else
            <div class="w-6 mr-2 flex-shrink-0">
                <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
            </div>
        @endif
        
        <!-- Avatar -->
        <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 flex-shrink-0 {{ $hasChildren ? 'bg-blue-500' : 'bg-gray-400' }}">
            <span class="text-white font-semibold text-xs">{{ substr($user->first_name ?? $user->name, 0, 1) }}</span>
        </div>
        
        <!-- User Info -->
        <div class="flex-1 min-w-0">
            <div class="flex items-center space-x-2">
                <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $user->name }}</h3>
                @if($hasChildren)
                    <span class="px-2 py-0.5 bg-blue-100 text-blue-700 text-xs font-medium rounded-full">
                        Manager
                    </span>
                @endif
            </div>
            <p class="text-xs text-gray-500 truncate">
                {{ $user->designation->name ?? 'No Designation' }}
                @if($user->department)
                    <span class="mx-1">•</span>
                    {{ $user->department->name }}
                @endif
            </p>
        </div>
        
        <!-- Employee Code -->
        <div class="text-right flex-shrink-0 ml-2">
            <span class="text-xs text-gray-400 font-medium">{{ $user->employee_code ?? 'N/A' }}</span>
        </div>
    </div>
    
    <!-- Children (Subordinates) -->
    @if($hasChildren)
        <div class="child-container collapsed border-l-2 border-blue-200 ml-3">
            @foreach($children as $child)
                @include('Admin.team.partials.tree-item', ['item' => $child, 'level' => 0])
            @endforeach
            
            <!-- Also show direct subordinates that have no further children -->
            @foreach($subordinates as $subordinate)
                @if(!collect($children)->pluck('user.id')->contains($subordinate->id))
                    <div class="team-item" style="margin-left: 0px;">
                        <div class="flex items-center p-3 bg-white border border-gray-200 rounded-lg hover:shadow-md transition-all mb-2 relative">
                            <div class="w-6 mr-2 flex-shrink-0">
                                <div class="w-2 h-2 bg-gray-300 rounded-full"></div>
                            </div>
                            
                            <!-- Avatar -->
                            <div class="w-8 h-8 rounded-full flex items-center justify-center mr-3 flex-shrink-0 bg-gray-400">
                                <span class="text-white font-semibold text-xs">{{ substr($subordinate->first_name ?? $subordinate->name, 0, 1) }}</span>
                            </div>
                            
                            <!-- User Info -->
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center space-x-2">
                                    <h3 class="text-sm font-semibold text-gray-900 truncate">{{ $subordinate->name }}</h3>
                                </div>
                                <p class="text-xs text-gray-500 truncate">
                                    {{ $subordinate->designation->name ?? 'No Designation' }}
                                    @if($subordinate->department)
                                        <span class="mx-1">•</span>
                                        {{ $subordinate->department->name }}
                                    @endif
                                </p>
                            </div>
                            
                            <!-- Employee Code -->
                            <div class="text-right flex-shrink-0 ml-2">
                                <span class="text-xs text-gray-400 font-medium">{{ $subordinate->employee_code ?? 'N/A' }}</span>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>
    @endif
</div>
