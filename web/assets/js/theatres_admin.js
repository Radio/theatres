(function($) {

    var $theatresForm = $('#edit-theatres-form');
    if ($theatresForm.length) {
        $theatresForm.find('.delete-theatre').bind('click', function() {
            var $this = $(this);
            if ($this.data('confirmed')) {
                var $deleteForm = $('#delete-theatre-form');
                $deleteForm.find('[name="id"]').val($(this).data('id'));
                $deleteForm.submit();
            } else {
                $this.data('confirmed', true).text('Точно?');
            }
        })
    }

    var $scenesForm = $('#edit-scenes-form');
    if ($scenesForm.length) {
        $scenesForm.find('.delete-scene').bind('click', function() {
            var $this = $(this);
            if ($this.data('confirmed')) {
                var $deleteForm = $('#delete-scene-form');
                $deleteForm.find('[name="id"]').val($this.data('id'));
                $deleteForm.submit();
            } else {
                $this.data('confirmed', true).text('Точно?');
            }
        })
    }

    var $fetchForm = $('#fetch-form');
    if ($fetchForm.length) {
        $fetchForm.find('.fetch-theatre').click(function() {
            var theatreKey = $(this).data('key');
            var $theatre = $(this).parents('.theatre');
            var $progress = $theatre.find('.progress');

            $theatre.find('.fetching-details').removeClass('hidden');

            fetchSchedule(theatreKey)
                .done(function(response) {
                    if (response.status == 'success') {
                        onSuccess(response.message);
                    } else {
                        onFailure(response.message);
                    }
                })
                .fail(function() {
                    onFailure('Ошибка сервера.');
                });
            function onSuccess(message) {
                $progress.removeClass('active progress-striped')
                    .find('.progress-bar').addClass('progress-bar-success');
                $progress.after($('<p></p>').html(message))
            }
            function onFailure(message) {
                $progress.removeClass('active progress-striped')
                    .find('.progress-bar').addClass('progress-bar-danger');
                $progress.after($('<p></p>').html(message))
            }
        });

        function fetchSchedule(theatreKey)
        {
            var url = fetchUrl + '/' + theatreKey;
            return $.get(url);
        }
    }
})(jQuery);