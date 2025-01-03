@extends('Core.Admin.Http.Views.layout', [
    'title' => __('rulesandfaq.admin.list.header.'. $type, ['type' => $type]),
])

@push('header')
    @at(mm('RulesAndFaq', 'Resources/assets/styles/admin/sortable.scss'))
@endpush

@push('content')
    <div class="admin-header d-flex justify-content-between align-items-center">
        <div>
            @if(isset($rules))
                <h2>@t('rulesandfaq.admin.rules.header')</h2>
                <p>@t('rulesandfaq.admin.rules.description')</p>
                <input type="hidden" id="rulesAndFaqType" value="rules">
            @elseif(isset($faq))
                <h2>@t('rulesandfaq.admin.faq.header')</h2>
                <p>@t('rulesandfaq.admin.faq.description')</p>
                <input type="hidden" id="rulesAndFaqType" value="faq">
            @endif
        </div>
        <div>
            @if(isset($rules))
                <a href="{{ url('admin/rulesandfaq/rules/add?type=rules') }}" class="btn size-s outline">
                    @t('rulesandfaq.admin.rules.add')
                </a>
            @elseif(isset($faq))
                <a href="{{ url('admin/rulesandfaq/faq/add?type=faq') }}" class="btn size-s outline">
                    @t('rulesandfaq.admin.faq.add')
                </a>
            @endif
        </div>
    </div>

    @if(sizeof(isset($rules) ? $rules :  $faq) > 0)
        <ul class="sortable-group rulesandfaq-list-nav-nested-sortable">
            @foreach(isset($rules) ? $rules :  $faq as $item)
                <li class="sortable-item sortable-dropdown draggable" data-id="{{ isset($rules) ? $item->rulesId : $item->faqId }}" id="{{ isset($rules) ? $item->rulesId : $item->faqId }}"
                    style="">
                    <div class="rulesandfaq-sortable">
                        <div class="rulesandfaq-body d-flex justify-content-between">
                            <span class="sortable-text">
                                <i class="ph ph-arrows-out-cardinal sortable-handle"></i>
                                <span class="badge">{{ isset($rules) ? __($item->rule) : __($item->question) }}</span>
                            </span>

                            <div class="rulesandfaq-list-action sortable-buttons">
                                <a href="{{ url('admin/rulesandfaq/' . (isset($rules) ? 'rules' : 'faq') . '/edit/' . (isset($rules) ? $item->rulesId : $item->faqId)) }}" class="change"
                                   data-tooltip="@t('def.edit')" data-tooltip-conf="left">
                                    <i class="ph ph-pencil"></i>
                                </a>
                                <div class="rulesandfaq_delete" data-deleteitem="{{ isset($rules) ? $item->rulesId : $item->faqId }}" data-tooltip="@t('def.delete')"
                                     data-tooltip-conf="left">
                                    <i class="ph ph-trash"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </li>
            @endforeach
        </ul>

        <button id="saveRulesAndFaqList" class="btn primary size-s mt-4">@t('def.save')</button>
    @else
        <div class="navigation_empty">
            @if(isset($rules))
                @t('rulesandfaq.admin.rules.empty')
            @elseif(isset($faq))
                @t('rulesandfaq.admin.faq.empty')
            @endif
        </div>
    @endif
@endpush

@push('footer')
    <script src="https://SortableJS.github.io/Sortable/Sortable.js"></script>
    @at(mm('RulesAndFaq', 'Resources/assets/js/admin/list.js'))
@endpush
