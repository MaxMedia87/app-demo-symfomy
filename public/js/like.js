$(function () {
  $('[data-item=likes]').each(function () {
    const $container = $(this);

    $container.on('click', function (e) {
      e.preventDefault();

      const type = $container.data('type');

      $.ajax({
        url: '/articles/10/like/' + type,
        method: 'POST'
      }).then(function (data) {
        $container.data('type', 'like' === type ? 'dislike' : 'like');
        $container.find('.fa-heart').toggleClass('far, fas');
        $container.find('[data-item=likesCount]').text(data.likes);
      });
    });
  });
});