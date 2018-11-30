var Sortable = function () {

    var self = this,
        priv = {};

    priv.init = function () {
        priv.$container = $('#items-list');
        priv.initSortable();
    };

    priv.initSortable = function() {
      priv.$container.sortable({
          update: priv.onSort
      });
    };

    priv.onSort = function (event, ui) {
        event.stopImmediatePropagation();
        $.ajax({
            dataType: "json",
            url: Routing.generate('item_change_order', {id : priv.$container.data('list-id')}),
            type: 'POST',
            data: priv.getOrder()
        })
    };

    priv.getOrder = function() {
        var ids = [];

        priv.$container.find('> div').each(function () {
            console.log($(this).data('id'));

           ids.push($(this).data('id'));
        });

        return {data: ids};
    };

    priv.init();

};

Sortable = new Sortable();