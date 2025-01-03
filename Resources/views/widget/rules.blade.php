<section class="rules-wrapper">
    <div class="rules-title">{{ $rulesTitle }}</div>

    @foreach ($rules as $item)
        <div class="rules-wrapper-container">
            <h3 class="rule">
                {{ $item->rule }}
                <i class="ph ph-plus"></i>
            </h3>
            <div class="answercont">
                <div class="answer">
                    {!! $service->parseBlocks($item, null)!!}
                </div>
            </div>
        </div>
    @endforeach
</section>