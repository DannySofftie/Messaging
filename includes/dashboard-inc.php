
<?php
require_once 'dbconfig.php';

?>

<style type="text/css" media="screen">
    #business_post {
        margin: 1em;
    }

    #post_options {
        height: 25px;
    }

        #post_options img {
            height: 100%;
        }

    .card {
        border: none;
    }

    .card-header {
    }

    #fileInputIcon {
        cursor: pointer;
    }

        #fileInputIcon:hover {
            border-color: teal;
        }

    .image_holder {
        width: 25px;
        height: 25px;
        margin-right: 8px;
        padding: 0px;
        cursor: pointer;
    }

        .image_holder img:hover {
            -webkit-filter: contrast(200%) brightness(150%); /* Safari */
            filter: contrast(200%) brightness(150%);
            -webkit-transform: rotateY(180deg); /* Safari */
            transform: rotateY(180deg);
            transition-duration: 1s;
            filter: hue-rotate(80deg);
        }

    img:hover {
        -webkit-filter: brightness(120%); /* Safari */
        filter: brightness(120%);
        filter: hue-rotate(30deg);
    }

    input[type='file'] {
        -moz-border-radius: 2px;
        -webkit-border-radius: 2px;
        border-radius: 2px;
        height: 100%;
        width: 100%;
        font-size: 0;
    }

    .load_posts .card {
        box-shadow: 1px 1px 3px 1px rgba(0,0,0,0.1);
        margin-top: 4px;
    }

    .share_toggler, .instagram_share, .twitter_share, .facebook_share {
        box-shadow: 1px 4px 10px 1px rgba(0,0,0,0.3);
    }

    .social_hide {
        display: none;
    }

    .postview img {
        width: 100%;
    }

    .postview {
        clear: both;
    }

    .post_entry_box {
        box-shadow: 1px 1px 20px 1px rgba(0,0,0,0.1);
        padding: 0 0 9px 0;
    }

    .load_posts {
        padding: 10px 80px;
    }

    @media(max-width: 720px) {
        .load_posts {
            padding: 10px 10px;
        }
    }
    .btn , .allFormData{
        z-index: 0;
    }
    
</style>

<div class="container animated fadeIn row col-lg-12 col-md-12" style="height: 100%;">
    <div class="col-lg-9 col-md-9 col-sm-12" style="height: 100%; overflow-y: scroll;">
        <div class="row post_entry_box">
            <div class="card col-lg-10 col-md-10">
                <div class="card-header">
                    <h5>Let other businesses know what you are up to? Share a marketing trick? Watch your competitors moves!</h5>
                </div>
                <form class="allFormData" method="post" enctype="multipart/form-data">
                    <div class="input-group business_post_cont">
                        <textarea rows="3" spellcheck="false" name="business_post" id="business_post" class="form-control" autofocus="autofocus"></textarea>
                    </div>
                </form>
                <div class="text-center">
                    <div id="post_result" class="float-left" style="display: none;">
                        <!-- POST RESULT WILL BE SHOWN HERE -->
                    </div>
                    <div id="post_options" class="float-right row">
                        <form class="allFormData" method="post" enctype="multipart/form-data">
                            <span class="input-group image_holder text-right">
                                <img src="../images/camera.jpg" alt="" id="image_selector" title="Upload an image" />
                                <input type="file" id="post_image" name="post_image" />
                            </span>
                        </form>&nbsp;
                        <span class="btn btn-sm btn-outline-info text-uppercase" style="margin-right: 2px" id="post_post">
                            post
                            <span class="mdi mdi-youtube-play"></span>
                        </span>
                        <span class="btn btn-sm btn-outline-warning text-uppercase" id="discard">
                            discard
                            <span class="mdi mdi-incognito"></span>
                        </span>
                    </div>
                </div>
            </div>
            <div class="col-lg-2 col-md-2 hidden-md-down" style="height: 100%">
                <h6>You can also post an image. You might be the next big thing to a certain business</h6>
                <div class="image_display" style="height: 100px; width: 110px; display: none;">
                    <img src="#" id="image_display" alt="" style="height: 100%; width: 100%" />
                </div>
            </div>
        </div>
        <div class="load_posts animated fadeIn">

            <!-- POSTS, NEW POSTS AND FEEDS WILL BE LOADED HERE -->
            <div class="text-center post_preloader" style="padding: 100px 0">
                <img src="../images/preloader/25.gif" alt="" style="height: 30px; width: 30px" />
            </div>

        </div>

        

        <!-- LOAD MORE BUTTON TO BE PLACED HERE -->
        <div class="preloader_holder" style="z-index: -1;">
            <button class="btn btn-sm btn-info text-uppercase" id="load_more_feeds">
                load more
                <span class="mdi mdi-account-convert"></span>
            </button>
        </div>
        <div class="preloader_content" style="display: none;">
            <img src="../images/preloader/25.gif" alt="" style="height: 30px; width: 30px" />
        </div>
    </div>

    <div class="col-lg-3 col-md-3 hidden-md-down" style="height: 100%; overflow-y: scroll; z-index: -1;">
        <h6>Share your marketing strategies</h6>
        <span class="btn btn-sm btn-outline-warning text-uppercase">
            share?
            <span class="mdi mdi-call-merge"></span>
        </span>
        <hr />
        <h6>Buy mailing list from your competitor</h6>
        <span class="btn btn-sm btn-outline-info text-uppercase">
            buy?
            <span class="mdi mdi-microscope"></span>
        </span>
        <div class="dropdown-divider"></div>
        <h6>
            Business dealing with goods under your business category are reaching thousands of clients through digital marketing. Don't be left out.
			Utilize the power of this platform to reach your clients. &raquo;&nbsp;
            <span class="mdi mdi-emoticon mdi-18px" style="color: teal"></span>
        </h6>
        <hr />
        <h6>
            ReachCleints.com is collecting thousands of emails online to improve your marketing techniques. Go premium to access a ton of email addresses for potential clients based on your location.
        </h6>
        <span class="btn btn-sm btn-outline-success text-uppercase">
            go premium
            <span class="mdi mdi-magnet-on"></span>
        </span>
        <hr />
        <h6>
            For a limited time, we are offering free marketing training for free to our loyal digital marketters. We are offering this training online.
        </h6>
        <span class="btn btn-sm btn-outline-warning text-uppercase">
            access freemium
            <span class="mdi mdi-webhook"></span>
        </span>
        <hr />

    </div>
    
</div>

<script type="text/javascript">
    $( function () {
        $( '#discard' ).click( function ( event ) {
            /* Act on the event */
            $( '#business_post' ).val( "" ).focus();
            $( '#business_post' ).attr( 'placeholder', 'Type what you are up to.......' );
        } );

        $( '#image_selector' ).click( function ( event ) {
            /* Act on the event */
            $( '#post_image' ).click();
        } );


        // LOAD MORE FEEDS TO CURRENT PAGE
        var $track_page = 1;

        // call function by default
        load_more_content( $track_page );

        $( '#load_more_feeds' ).click( function ( e ) {
            // $(event.target).closest('span').addClass('mdi-spin');
            $track_page++;
            load_more_content( $track_page );
        } );

        function load_more_content( page_number ) {
            $.post( '../includes/pull-feeds-tome.php?fetchNew=true', { 'page_number': page_number }, function ( data ) {
                if ( data.trim().length == 0 ) {
                    $( '#load_more_feeds' ).text( "You have reached the end of the current feeds" ).prop( 'disabled', true );
                } else {
                    $( '.post_preloader' ).hide();
                    $( '.load_posts' ).append( data );
                }
            } );
        }

        // function to check news feed for new updates

        ( function updateFeed() {
            $.post( '../includes/pull-feeds-tome.php?fetchNew=true', { 'page_number': $track_page }, function ( data ) {

                $( '.load_posts' ).html( data );
                setTimeout( updateFeed, 15000 );
            } );
        } )();

        $( '#post_post' ).click( function ( event ) {
            /* Act on the event */
            var $postToPost = $( '#business_post' ).val().trim();
            $( '#post_result' ).html( $( '.preloader_content' ).html() );
            if ( $postToPost == '' ) {
                $( '.business_post_cont' ).addClass( 'has-danger' );
                $( '#business_post' ).addClass( 'form-control-danger' );
                $( '#business_post' ).focus();
                $( '#business_post' ).attr( 'placeholder', 'Your post is empty' );
            } else {
                $( '.business_post_cont' ).removeClass( 'has-danger' );
                $.ajax( {
                    url: '../includes/pull-feeds-tome.php?postContent=' + $postToPost,
                    method: 'POST',
                    data: new FormData( $( '.allFormData' )[1] ),
                    async: false,
                    success: function ( resultText ) {
                        // execute after success

                        // reset input of type file to blank
                        $( '.allFormData' )[1].reset();

                        // reset text area to blank
                        $( '#business_post' ).val( '' );

                        $( '.image_display' ).slideUp();
                        $( '#business_post' ).focus();
                        $( '#business_post' ).attr( 'placeholder', 'Post a new post, you can also add images........' );
                        $( '#post_result' ).fadeIn( 'slow', function () {
                            $( this ).slideUp( 5000 );
                            $( '#post_result' ).html( resultText );
                        } );
                        $.post( '../includes/pull-feeds-tome.php?fetchNew=true', { 'page_number': page_number }, function ( data ) {
                            /*optional stuff to do after success */
                            $( '.load_posts' ).html( data );
                        } );
                    },
                    error: function () {
                        alert( "Your post was not submitted." );
                    },
                    cache: false,
                    contentType: false,
                    processData: false
                } )
            }
        } );

        // function for image preview before upload
        function readImageURL( input ) {
            if ( input.files && input.files[0] ) {
                var reader = new FileReader();
                reader.onload = function ( e ) {
                    /* Act on the event */
                    $( '#image_display' ).attr( 'src', e.target.result );
                    $( '.image_display' ).show();
                }
                reader.readAsDataURL( input.files[0] );
            }
        }
        $( '#post_image' ).change( function ( input ) {
            /* Act on the event */
            // $('#select_image').hide();
            $( '.image_display' ).show();
            readImageURL( this );
        } );

        // profile view request
       
    } )
</script>