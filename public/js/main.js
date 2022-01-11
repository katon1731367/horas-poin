    function previewImg() {
      const user_image = document.querySelector('#user_image');
      const label = document.querySelector('.custom-file-label');
      const imgPreview = document.querySelector('.img-preview');

      label.textContent = user_image.files[0].name;

      const fileGambar = new FileReader();
      fileGambar.readAsDataURL(user_image.files[0]);

      fileGambar.onload = function(e) {
         imgPreview.src = e.target.result
      }
    }
   
    function showFilter() {
        filter = document.getElementById('filter');
        if (filter.hidden === false) {
            document.getElementById('filter').hidden = true;
        } else {
            document.getElementById('filter').hidden = false;
        }
    }

    function fetch_data(page) {
        $.ajax({
            url: url_fetch,
            method: "post",
            dataType: "html",
            data: {
                page: page
            }
        }).done(function(data) {
            $('#get_data').html(data);
        });
    }
    function filter_data(page) {
        var product = $('#product_name').val();
        var from = $('#from').val();
        var to = $('#to').val();
        var keyword = $('#keyword').val();
        $.ajax({
            url: url_filter,
            method: "post",
            dataType: "html",
            data: {
                page: page,
                from: from,
                to: to,
                product: product,
                keyword: keyword,
            }
        }).done(function(data) {
            $('#get_data').html(data);
        });
    }
    function clearForm(){
        fetch_data();
        $('#product_name').prop('selectedIndex', 0);
        $('#from').val('');
        $('#to').val('');
        $('#keyword').val('');
    }
    function filterAcquisitationUser(){
        var product = $('#product_name').val();
        var from = $('#from').val();
        var to = $('#to').val();
        var keyword = $('#keyword').val();
        var page = $(this).attr('id');

        if (product || from || to || keyword) {
            filter_data(page);
        } else {
            fetch_data(page);
        }
    }
    function pagePaginationUser(){
        var page = $('.page-link').attr('id');
        filter_data(page);
    }
    