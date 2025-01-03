@extends('Core.Admin.Http.Views.layout', [
    'title' => __('rulesandfaq.admin.edit.title.' . $type, ['type' => $type, 'name' => $item->question ?? $item->rule]),
])

@push('content')
    <div class="admin-header d-flex justify-content-between align-items-center">
        <div>
            <a class="back-btn" href="{{ url('admin/rulesandfaq/' . $type . '/list') }}">
                <i class="ph ph-arrow-left ignore"></i>
                @t('def.back')
            </a>
            <h2>@t('rulesandfaq.admin.edit.title.' . $type, ['type' => $type, 'name' => $item->question ?? $item->rule])</h2>
            <p>@t('rulesandfaq.admin.edit.description.' . $type)</p>
        </div>
    </div>

    <form data-form="edit" data-page="rulesandfaq/{{ $type }}" data-id="{{ $item->faqId ?? $item->rulesId }}">
        @csrf
        <input type="hidden" name="id" value="{{ $item->faqId ?? $item->rulesId }}">

        <div class="position-relative row form-group">
            <label class="col-sm-3 col-form-label required" for="item_name">
                @t('rulesandfaq.admin.edit.name_' . $type)
            </label>
            <div class="col-sm-9">
                <input name="name" id="item_name" placeholder="@t('rulesandfaq.admin.edit.name_' . $type)"
                       type="text" class="form-control" value="{{ $item->question ?? $item->rule }}" required>
            </div>
        </div>

        <div class="position-relative row form-group">
            <label class="col-sm-3 col-form-label" for="blocks">@t('rulesandfaq.admin.edit.content')</label>
            <div class="col-sm-9">
                <div data-editorjs id="editorRulesAndFaqEdit-{{ $item->faqId ?? $item->rulesId }}"></div>
            </div>
        </div>

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
    <script data-loadevery>
        window.defaultEditorData["editorRulesAndFaqEdit-{{ $item->faqId ?? $item->rulesId }}"] = {
            blocks: {!! $item->blocks->json ?? '[]' !!}
        };
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