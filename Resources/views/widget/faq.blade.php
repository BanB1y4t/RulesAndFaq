<section class="faq-wrapper">
    <div class="faq-title">{{ $faqTitle }}</div>

    @foreach ($question as $item)
        <div class="faq-wrapper-container">
            <h3 class="question">
                {{ $item->question }}
                <i class="ph ph-plus"></i>
            </h3>
            <div class="answercont">
                <div class="answer">
                    {!! $service->parseBlocks(null, $item)!!}
                </div>
            </div>
        </div>
    @endforeach
</section>