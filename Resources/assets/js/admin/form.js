function serializeFormRulesAndFaq($form) {

    let formData = new FormData($form[0]);
    let additionalParams = {};

    // Append additional parameters to FormData
    for (let key in additionalParams) {
        if (additionalParams.hasOwnProperty(key)) {
            formData.append(key, additionalParams[key]);
        }
    }

    return formData;
}

$(document).on('submit', '[data-rulesandfaqform]', async (ev) => {
    let $form = $(ev.currentTarget);

    ev.preventDefault();

    let path = $form.data('rulesandfaqform'),
        form = serializeFormRulesAndFaq($form),
        page = $form.data('page'),
        id = $form.data('id');
    console.log(form);

    let basePath = 'admin/api/rulesandfaq';
    let url = `${basePath}/${page}/${path}`;

    if (path === 'edit') {
        url = `admin/api/${page}/${id}`;
    }

    let activeEditorElement = document.querySelector(
        '.tab-content:not([hidden]) [data-editorjs]',
    );
    let activeEditor = window['editorInstance_' + activeEditorElement.id];

    let editorData = await activeEditor.save();
    form.append('blocks', JSON.stringify(editorData.blocks));

    if (ev.target.checkValidity()) {
        sendRequest(form, url, method);
    }
});
