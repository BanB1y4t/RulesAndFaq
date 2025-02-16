@extends('Core.Admin.Http.Views.layout', [
    'title' => __('rulesandfaq.admin.settings.title'),
])

@push('content')
    <div class="admin-header d-flex justify-content-between align-items-center">
        <div>
            <a class="back-btn" href="{{ url('admin/rulesandfaq/settings') }}">
                <i class="ph ph-arrow-left ignore"></i>
                @t('def.back')
            </a>
            <h2>@t('rulesandfaq.admin.settings.title')</h2>
            <p>@t('rulesandfaq.admin.settings.description')</p>
        </div>
    </div>

    <form data-form="settings" data-page="rulesandfaq" action="{{ url('api/rulesandfaq/settings') }}" method="POST">
        @csrf
        <!-- Поле для ввода названия правил -->
        <div class="position-relative row form-group">
            <div class="col-sm-3 col-form-label required">
                <label for="rules_title">
                    @t('rulesandfaq.admin.settings.rules_title')
                </label>
            </div>
            <div class="col-sm-9">
                <input name="rules_title" id="rules_title" value="{{ old('rules_title', $rulesTitle) }}" placeholder="@t('rulesandfaq.admin.settings.rules_placeholder')" type="text" class="form-control" required="">
            </div>
        </div>

        <!-- Поле для ввода названия FAQ -->
        <div class="position-relative row form-group">
            <div class="col-sm-3 col-form-label required">
                <label for="faq_title">
                    @t('rulesandfaq.admin.settings.faq_title')
                </label>
            </div>
            <div class="col-sm-9">
                <input name="faq_title" id="faq_title" value="{{ old('faq_title', $faqTitle) }}" placeholder="@t('rulesandfaq.admin.settings.faq_placeholder')" type="text" class="form-control" required="">
            </div>
        </div>

        <!-- Кнопка отправки -->
        <div class="position-relative row form-check">
            <div class="col-sm-9 offset-sm-3">
                <button type="submit" class="btn size-m btn--with-icon primary">
                    @t('def.save')
                    <span class="btn__icon arrow"><i class="ph ph-arrow-right"></i></span>
                </button>
            </div>
        </div>
    </form>
@endpush

@push('footer')
    @at(mm('RulesAndFaq', 'Resources/assets/js/admin/form.js'))
@endpush
