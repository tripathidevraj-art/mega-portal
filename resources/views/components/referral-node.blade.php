<ul>
@foreach($nodes as $node)
    <li>
        {{ $node['user']->full_name }} ({{ $node['user']->email }})
        @if(count($node['children']))
            <button onclick="toggleChildren(this)">Show/Hide</button>
            @include('components.referral-node', ['nodes' => $node['children']])
        @endif
    </li>
@endforeach
</ul>