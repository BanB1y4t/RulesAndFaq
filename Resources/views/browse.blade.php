@extends(tt('layout.blade.php'))

@section('title')
    {{ t('rulesandfaq.browse.title') }}
@endsection

@push('header')
    @at(mm('RulesAndFaq', 'Resources/assets/styles/browse.scss'))
@endpush

@push('content')
    @navbar
    <div class="container">
        @navigation
        @breadcrumb
        @flash
        @editor
        <div class="row gx-3 gy-3">

            <div class="col-md-{{  3 ? 9 : 12 }}">
                <section class="card rulesandfaq-table">
                    <div class="rulesandfaq-header">
                        <div class="rulesandfaq-header">
                            <h2 class="rulesandfaq-title">@t('rulesandfaq.browse.title')</h2>
                            <div class="rulesandfaq-block">
                                @if($block)
                                    {!! $block !!}
                                @else
                                    <p>@t('rulesandfaq.browse.no_content_selected')</p>
                                @endif
                            </div>
                        </div>
                    </div>
                </section>
            </div>

            <div class="col-md-3">
                <div class="menu menu-block">
                    <div class="menu_rulesandfaq_block">
                        <button class="menu-rulesandfaq">
                            <div class="menu-rulesandfaq-header" data-filter-type="rule">
                                <p>@t('rulesandfaq.browse.menu.rules')</p>
                                <i class="ph ph-caret-down"></i>
                            </div>

                            <div class="menu-rulesandfaq-content hidden">
                                @foreach($rule as $item)
                                    <div class="menu-rulesandfaq-content-item" data-filter-rule="{{ $item->rulesId }}">
                                        {{ __($item->rule) }}
                                    </div>
                                @endforeach
                            </div>
                        </button>
                        <button class="menu-rulesandfaq">
                            <div class="menu-rulesandfaq-header" data-filter-type="rule">
                                <p>@t('rulesandfaq.browse.menu.faq')</p>
                                <i class="ph ph-caret-down"></i>
                            </div>

                            <div class="menu-rulesandfaq-content hidden">
                                @foreach($question as $item)
                                    <div class="menu-rulesandfaq-content-item" data-filter-question="{{ $item->faqId }}">
                                        {{ __($item->question) }}
                                    </div>
                                @endforeach
                            </div>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endpush

@push('footer')
    @at(mm('RulesAndFaq', 'Resources/assets/js/browse.js'))
@endpush

@footer