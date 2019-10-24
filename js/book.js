jQuery(document).ready(function( $ ) {

    var scannedIsbn = jQuery("#scanned-isbn");
    var listTable = jQuery("#birdbook-list-table");
    var listTableBody = jQuery("#birdbook-list-table tbody");

    var postIdInputField = jQuery("#birdbook-post-id");
    var titleInputField = jQuery("#birdbook-title");
    var subtitleInputField = jQuery("#birdbook-subtitle");
    var authorsInputField = jQuery("#birdbook-authors");
    var descriptionTextArea = jQuery("#birdbook-description");
    var languageInputField = jQuery("#birdbook-language");
    var pageCountInputField = jQuery("#birdbook-pagecount");
    var maturityRatingInputField = jQuery("#birdbook-maturityrating");
    var categoriesInputField = jQuery("#birdbook-categories");
    var publishedDateInputField = jQuery("#birdbook-publisheddate");
    var priceInputField = jQuery("#birdbook-price");

    jQuery("#birdbook-confirm").on( 'click', function() {
        var data = {
            'postId' : postIdInputField.val(),
            'isbn' : scannedIsbn.val(),
            'title' : titleInputField.val(),
            'subtitle' : subtitleInputField.val(),
            'authors' : authorsInputField.val(),
            'description' : descriptionTextArea.val(),
            'language' : languageInputField.val(),
            'pageCount' : pageCountInputField.val(),
            'maturityRating' : maturityRatingInputField.val(),
            'categories' : categoriesInputField.val(),
            'publishedDate' : publishedDateInputField.val(),
            'price' : priceInputField.val()
        };
        jQuery.post( 'http://localhost:8000/wp-admin/admin-ajax.php?action=addtostock', data, function() {
        }, 'json' );
    });


    listTable.on('click', 'tr.birdbook-row', function() {
        var selectedRow = jQuery(this).attr('id').substr(11);

        titleInputField.val( jQuery('#birdbook-r' + selectedRow + '-title').text() );
        subtitleInputField.val( jQuery('#birdbook-r' + selectedRow + '-subtitle').text() );
        authorsInputField.val( jQuery('#birdbook-r' + selectedRow + '-authors').text() );
        descriptionTextArea.val( jQuery('#birdbook-r' + selectedRow + '-description').text() );
        languageInputField.val( jQuery('#birdbook-r' + selectedRow + '-language').text() );
        pageCountInputField.val( jQuery('#birdbook-r' + selectedRow + '-pageCount').text() );
        maturityRatingInputField.val( jQuery('#birdbook-r' + selectedRow + '-maturityRating').text() );
        categoriesInputField.val( jQuery('#birdbook-r' + selectedRow + '-categories').text() );
        publishedDateInputField.val( jQuery('#birdbook-r' + selectedRow + '-publishedDate').text() );
        priceInputField.val( jQuery('#birdbook-r' + selectedRow + '-price').text() );
    });

    scannedIsbn.change(function(){
        var results = jQuery.get(
            'http://localhost:8000/wp-admin/admin-ajax.php?action=searchbookbyisbn&isbn=' + scannedIsbn.val(),
            function(result){
                listTableBody.empty();

                if( Array.isArray( result.data ) ) {
                    var counter = 0;
                    result.data.forEach( function(element) {
                        var title = element['title'] ? element['title'] : '';
                        var subtitle = element['subtitle'] ? element['subtitle'] : '';
                        var authors = element['authors'] ? element['authors'].toString() : '';
                        var description = element['description'] ? element['description'] : '';
                        var language = element['language'] ? element['language'] : '';
                        var pageCount = element['pageCount'] ? element['pageCount'] : '';
                        var maturityRating = element['maturityRating'] ? element['maturityRating'] : '';
                        var categories = element['categories'] ? element['categories'].toString() : '';
                        var publishedDate = element['publishedDate'] ? element['publishedDate'] : '';
                        var price = element['price'] ? element['price'] : '';


                        var newRow = '<tr id="birdbook-list-' + counter + '" class="birdbook-row" >';
                        newRow = newRow + '<td class="birdbook-col" id="birdbook-r' + counter + '-title">' + title + '</td>';
                        newRow = newRow + '<td class="birdbook-col" id="birdbook-r' + counter + '-subtitle">' + subtitle + '</td>';
                        newRow = newRow + '<td class="birdbook-col" id="birdbook-r' + counter + '-authors">' + authors + '</td>';
                        newRow = newRow + '<td class="birdbook-col"><div class="birdbook-col-truncated" id="birdbook-r' + counter + '-description">' + description + '</div></td>';
                        newRow = newRow + '<td class="birdbook-col" id="birdbook-r' + counter + '-language">' + language + '</td>';
                        newRow = newRow + '<td class="birdbook-col" id="birdbook-r' + counter + '-pageCount">' + pageCount + '</td>';
                        newRow = newRow + '<td class="birdbook-col" id="birdbook-r' + counter + '-maturityRating">' + maturityRating + '</td>';
                        newRow = newRow + '<td class="birdbook-col" id="birdbook-r' + counter + '-categories">' + categories + '</td>';
                        newRow = newRow + '<td class="birdbook-col" id="birdbook-r' + counter + '-publishedDate">' + publishedDate + '</td>';
                        newRow = newRow + '<td class="birdbook-col" id="birdbook-r\' + counter + \'-price">' + price + '</td>';
                        newRow = newRow + '</tr>';

                        listTable.append( newRow );
                        counter++;
                    } );
                } else {
                    postIdInputField.val( result.data.post_id);
                    titleInputField.val( result.data.title );
                    subtitleInputField.val( result.data.subtitle );
                    authorsInputField.val( result.data.authors );
                    descriptionTextArea.val( result.data.description );
                    languageInputField.val( result.data.language );
                    pageCountInputField.val( result.data.pageCount );
                    maturityRatingInputField.val( result.data.maturityRating );
                    //categories.val( result.data.categories );
                    publishedDateInputField.val( result.data.publishedDate );
                    priceInputField.val( result.data.price );
                }

            }
        );

    });
});


