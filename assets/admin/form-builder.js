// Adapted from Grav CMS Admin Plugin
// Original source: https://github.com/getgrav/grav-plugin-admin/blob/develop/themes/grav/app/pages/page/add.js
// Original author: Team Grav https://getgrav.org
// License: MIT

let customKey = false;
let customId = false;
let key = $('[data-collection-holder="header.form.fields"] li input.field-key');
let id = $('[data-collection-holder="header.form.fields"] li input.field-id');
let label = $('[data-collection-holder="header.form.fields"] li input.field-label');
let getFields = (type, target) => {
    target = $(target);
    let query = `[data-collection-item="${target.closest('[data-collection-holder="header.form.fields"] li').data('collection-item')}"]`;

    return {
        label: type === 'label' ? $(target) : $(`${query} input.field-label`),
        key: type === 'key' ? $(target) : $(`${query} input.field-key`),
        id: type === 'id' ? $(target) : $(`${query} input.field-id`)
    };
};

label.on('input focus blur', (event) => {
    if (customKey || customId) { return true; }
    let elements = getFields('label', event.currentTarget);

    let slug = $.slugify(elements.label.val(), {custom: { "'": '', '‘': '', '’': '' }});
    elements.key.val(slug);
    elements.id.val(slug);
});

key.on('input', (event) => {
    let elements = getFields('key', event.currentTarget);

    let input = elements.key.get(0);
    let value = elements.key.val();
    let selection = {
        start: input.selectionStart,
        end: input.selectionEnd
    };

    value = value.toLowerCase().replace(/\s/g, '-').replace(/[^a-z0-9_\-]/g, '');
    elements.key.val(value);
    customKey = !!value;

    // restore cursor position
    input.setSelectionRange(selection.start, selection.end);

});

key.on('focus blur', (event) => {
  getFields('label').label.trigger('input');
});

id.on('input', (event) => {
    let elements = getFields('id', event.currentTarget);

    let input = elements.id.get(0);
    let value = elements.id.val();
    let selection = {
        start: input.selectionStart,
        end: input.selectionEnd
    };

    value = value.toLowerCase().replace(/\s/g, '-').replace(/[^a-z0-9_\-]/g, '');
    elements.id.val(value);
    customId = !!value;

    // restore cursor position
    input.setSelectionRange(selection.start, selection.end);

});

id.on('focus blur', (event) => {
  getFields('label').label.trigger('input');
});
