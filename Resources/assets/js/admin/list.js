$(function () {
    const createRulesAndFaqListSortableInstances = () => {
        const nestedSortables = document.querySelectorAll('.rulesandfaq-list-nav-nested-sortable');
        return Array.from(nestedSortables).map(
            (nestedSortable) =>
                new Sortable(nestedSortable, {
                    group: 'nested',
                    handle: '.sortable-handle',
                    animation: 150,
                    fallbackOnBody: true,
                    swapThreshold: 0.65,
                    onMove: (event) => {
                        const level = $(event.to).parents('.rulesandfaq-list-nav-nested-sortable').length;
                        const length = $(event.dragged).find('.rulesandfaq-list-nav-nested-sortable > li').length;
                        return !((length > 0 && level > 0) || level > 1);
                    },
                })
        );
    };

    let sortablesRulesAndFaqList = createRulesAndFaqListSortableInstances();

    document.querySelector('.chrome-tabs')?.addEventListener('contentRender', () => {
        sortablesRulesAndFaqList = createRulesAndFaqListSortableInstances();
    });

    $(document).on('click', '#saveRulesAndFaqList', () => {
        const type = $('#rulesAndFaqType').val();
        const orderedIds = getNestedOrder(sortablesRulesAndFaqList, type);
        saveRulesAndFaqOrder(orderedIds, type);
    });

    function getNestedOrder(sortables, type) {
        const order = [];
        sortables.forEach((sortable) => {
            const items = sortable.toArray();
            items.forEach((itemId, index) => {
                const element = document.getElementById(itemId);
                const parentSortable = element.closest('.rulesandfaq-list-nav-nested-sortable');
                const parentId = parentSortable?.getAttribute('id') || null;

                order.push({
                    idField: type === 'rules' ? 'rulesId' : 'faqId',
                    idValue: itemId,
                    parentId: parentId,
                    position: index,
                });
                console.log(order)
            });
        });
        return order;
    }

    async function saveRulesAndFaqOrder(order, type) {
        const data = {
            type: type,
            order: order.map((item) => ({
                [item.idField]: item.idValue,
                position: item.position + 1,
            })),
        };

        try {
            const response = await fetch(u(`admin/api/rulesandfaq/${type}/save-order`), {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'x-csrf-token': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                },
                body: JSON.stringify(data),
            });

            const result = await response.json();

            if (result.error) throw new Error(result.error);

            toast({
                type: 'success',
                message: result.success ?? translate('def.success'),
            });
        } catch (error) {
            toast({
                type: 'error',
                message: error.message ?? translate('def.unknown_error'),
            });
        }
    }

    $(document).on('click', '.rulesandfaq_delete', async function () {
        const type = $('#rulesAndFaqType').val();
        const itemId = $(this).data('deleteitem');

        if (await asyncConfirm(translate('rulesandfaq.admin.list.delete'))) {
            try {
                await sendRequest({ itemId, type }, u(`admin/api/rulesandfaq/${type}/${itemId}`), 'delete');
                $(this).closest('.draggable').remove();
            } catch (error) {
                toast({
                    type: 'error',
                    message: error.message ?? translate('def.unknown_error'),
                });
            }
        }
    });
});
