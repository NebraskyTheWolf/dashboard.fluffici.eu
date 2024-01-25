// adding a nice animation on image loading.

document.register('loadable-image', {
    prototype: Object.create(HTMLImageElement.prototype)
});
$('img[is="loadable-image"]').each(function () {
    const currentContext = $(this)

    $(this).append(`<div id='loadable'> <i id="icon" class="spinner circle notch icon"></i></div>`)

    if (currentContext.completed) {
        $('#loadable').remove()
    } else if (currentContext.failed) {
        const icon = $('#icon');
        if (icon.hasClass('circle')) {
            icon.removeClass('circle')
            icon.removeClass('notch')
            icon.removeClass('icon')
            icon.removeClass('spinner')

            icon.addClass('close')
            icon.addClass('icon')
        }
    }
})
