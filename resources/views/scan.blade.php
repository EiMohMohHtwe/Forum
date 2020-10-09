@extends('layouts.app')

@section('content')
<div class="container">
    <ais-index
        app-id="latency"
        api-key="eb4db1398214f45a933160eb06b00abc"
        index-name="bestbuy"
    >
        <ais-search-box></ais-search-box>
        <aix-results>
            <template scope="{ result }">
                <p>
                    <ais-highlight :result="result" attribute-name="name"></ais-highlight>
                </p>
            </template>
        </aix-results>
    </ais-index>
    <p>Test</p>
</div>

@endsection