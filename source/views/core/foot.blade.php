    <div class="container has-text-centered">
        <small>
            Copyright {{ \Carbon\Carbon::now()->format('Y') }} &copy; <a href="{{ localAddress() }}">{{ localAddress() }}</a> v{{ config('app.version') }} | <a href="/help">help and support</a><br />
            {{ 'IP address ' . remoteAddress() . ' recorded at ' . \Carbon\Carbon::now()->format('h:i:s d-m-Y') }}
        </small>
    </div>