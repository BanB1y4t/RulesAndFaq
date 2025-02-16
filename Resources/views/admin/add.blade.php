@extends('Core.Admin.Http.Views.layout', [
    'title' => __('rulesandfaq.admin.add.title.' . $type, [ 'type' => $type ]),
])


@push('content')
    <div class="admin-header d-flex justify-content-between align-items-center">
        <div>
            <a class="back-btn" href="{{ url('admin/rulesandfaq/rules/list') }}">
                <i class="ph ph-arrow-left ignore"></i>
                @t('def.back')
            </a>
            <h2>@t('rulesandfaq.admin.add.title.' . $type)</h2>
            <p>@t('rulesandfaq.admin.add.description.' . $type)</p>
        </div>
    </div>

    <form data-form="add" data-page="rulesandfaq/{{ $type }}">
    @csrf
        <input type="hidden" name="type" value="{{ $type }}">

        <!-- Поля формы в зависимости от типа -->
        @if($type === 'rules')
            <div class="position-relative row form-group">
                <div class="col-sm-3 col-form-label required">
                    <label for="rule">
                        @t('rulesandfaq.admin.add.name_rule')
                    </label>
                </div>
                <div class="col-sm-9">
                    <input name="rule" id="rule" placeholder="@t('rulesandfaq.admin.add.name_rule')" type="text" class="form-control" required>
                </div>
            </div>
        @elseif($type === 'faq')
            <div class="position-relative row form-group">
                <div class="col-sm-3 col-form-label required">
                    <label for="question">
                        @t('rulesandfaq.admin.add.question')
                    </label>
                </div>
                <div class="col-sm-9">
                    <input name="question" id="question" placeholder="@t('rulesandfaq.admin.add.question')" type="text" class="form-control" required>
                </div>
            </div>
        @endif

        <!-- Поле редактора -->
        <div class="position-relative row form-group">
            <div class="col-sm-3 col-form-label">
                <label for="blocks">@t('rulesandfaq.admin.add.content')</label>
            </div>
            <div class="col-sm-9">
                <div data-editorjs id="editorRulesAndFaqAdd"></div>
                <input type="hidden" name="blocks" id="blocks">
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
    <script>
        var rulesAndFaqItemBlocks = {!! json_encode($blocks) !!};
    </script>
    <script src="@asset('assets/js/editor/table.js')"></script>
    <script src="@asset('assets/js/editor/alignment.js')"></script>
    <script src="@asset('assets/js/editor/raw.js')"></script>
    <script src="@asset('assets/js/editor/delimiter.js')"></script>
    <script src="@asset('assets/js/editor/embed.js')"></script>
    <script src="@asset('assets/js/editor/header.js')"></script>
    <script src="@asset('assets/js/editor/image.js')"></script>
    <script src="@asset('assets/js/editor/list.js')"></script>
    <script src="@asset('assets/js/editor/marker.js')"></script>


    <script src="@asset('assets/js/editor.js')"></script>

    @at('Core/Admin/Http/Views/assets/js/editor.js')
    @at(mm('RulesAndFaq', 'Resources/assets/js/admin/form.js'))
@endpush
