<li>
    <a href="javascript:void(0);">
        <div class="member-view-box">
            <div class="member-header">
                <span>{{ $node['user']->isSuperAdmin() ? 'Super Admin' : ($node['user']->isAdmin() ? 'Admin' : 'Member') }}</span>
            </div>
            <div class="member-image">
                <img 
                    src="{{ $node['user']->profile_image 
                        ? asset('storage/'.$node['user']->profile_image) 
                        : 'https://cdn-icons-png.flaticon.com/512/1077/1077114.png' }}" 
                    alt="{{ $node['user']->full_name }}"
                >
            </div>
            <div class="member-footer">
                <div class="name">
                    <span>{{ Str::limit($node['user']->full_name, 12) }}</span>
                </div>
                <div class="downline">
                    <span>{{ count($node['children']) }} downlines</span>
                </div>
            </div>
        </div>
    </a>
    
    @if (!empty($node['children']))
        <ul class="{{ $node['level'] <= 2 ? 'active' : '' }}">
            @foreach($node['children'] as $child)
                @include('user.referral.tree-node', ['node' => $child, 'level' => ($node['level'] ?? 0) + 1])
            @endforeach
        </ul>
    @endif
</li>